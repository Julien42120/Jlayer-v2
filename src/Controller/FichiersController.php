<?php

namespace App\Controller;

use App\Entity\Commentaires;
use App\Entity\Fichiers;
use App\Form\CommentairesType;
use App\Form\FichiersType;
use App\Repository\CategoryFichierRepository;
use App\Repository\CommentairesRepository;
use App\Repository\FichiersRepository;
use App\Repository\VoteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/fichiers")
 */
class FichiersController extends AbstractController
{
    /**
     * @Route("/", name="fichiers_index", methods={"GET"})
     */
    public function index(FichiersRepository $fichiersRepository, CategoryFichierRepository $categoryFichierRepository, VoteRepository $voteRepository): Response
    {
        $allCategory = $categoryFichierRepository->findAll();

        return $this->render('fichiers/index.html.twig', [
            'fichierSTL' => $fichiersRepository->findAll(),
            'category' => $allCategory,
        ]);
    }

    /**
     * @Route("/new", name="fichiers_new", methods={"GET","POST"})
     */
    public function new(Request $request, CategoryFichierRepository $categoryFichierRepository): Response
    {
        $fichier = new Fichiers();
        $form = $this->createForm(FichiersType::class, $fichier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($fichier);
            $entityManager->flush();

            return $this->redirectToRoute('fichiers_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fichiers/new.html.twig', [
            'fichier' => $fichier,
            'form' => $form->createView(),
            'category' => $categoryFichierRepository,
        ]);
    }

    /**
     * @Route("/{id}/show", name="fichiers_show", methods={"GET", "POST"})
     */
    public function show(Request $request, Fichiers $fichier, CategoryFichierRepository $category, FichiersRepository $fichiersRepository, CommentairesRepository $commentairesRepository, VoteRepository $voteRepository): Response
    {
        $user = $this->getUser();
        if ($user) {
            $vote = $voteRepository->findOneBy(['fichier' => $fichier->getId(), 'user' => $user->getId()]);
        } else {
            $vote = $voteRepository->findOneBy(['fichier' => $fichier->getId()]);
        };
        if ($vote) {
            $isLiked = true;
        } else {
            $isLiked = false;
        }
        $commentaire = new Commentaires();
        $form = $this->createForm(CommentairesType::class, $commentaire);
        $form->handleRequest($request);

        if ($user) {
            if ($form->isSubmitted() && $form->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();
                $commentaire->setUser($this->getUser('user'));
                $commentaire->setFichier($fichier);
                $entityManager->persist($commentaire);
                $entityManager->flush();

                return $this->redirectToRoute('fichiers_show', [
                    'id' => $fichier->getId(),
                    'user' => $this->getUser(),
                ], Response::HTTP_SEE_OTHER);
            }
        } else {
            $this->addFlash('success', 'Connectez-vous pour laisser un commentaire');
        }
        $produitSimilaire = $fichiersRepository->findBy(['category' => $fichier->getCategory()], [], 3);
        $commentaireById = $commentairesRepository->findBy(['fichier' => $fichier->getId()], [], 10);
        $voteTotal = count($voteRepository->findBy(['fichier' => $fichier->getId()]));

        return $this->render('fichiers/show.html.twig', [
            'fichierSTL' => $fichier,
            'fichiers' => $fichier->getCategory(),
            'category' => $category->findAll('fichierSTL'),
            'produitSimilaire' => $produitSimilaire,
            'user' => $this->getUser(),
            'formComment' => $form->createView(),
            'commentaireById' => $commentaireById,
            'voteTotal' => $voteTotal,
            'liked' => $isLiked,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="fichiers_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Fichiers $fichier, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(FichiersType::class, $fichier);
        $form->handleRequest($request);
        $images = $form->get('images')->getData();
        $fichiersSTL = $form->get('fichierSTL')->getData();
        $folderName = $form->get('nom')->getData();

        if ($form->isSubmitted() && $form->isValid()) {

            if ($images !== null) {
                $this->deleteFichierFiles($this->getParameter('images') . '/' . $fichier->getImages());
                $fichier->setImages($this->upload($images, 'images', $slugger, null));
            }
            if ($fichiersSTL !== null) {

                foreach ($fichiersSTL as $fichierSTL) {
                    $this->deleteFichierFiles($this->getParameter($folderName) . '/' . $fichier->getFichierSTL());
                    $fichier->setFichierSTL($this->upload($fichierSTL, 'fichierSTL', $slugger, $folderName));
                }
            }
            $fichier->setIsVerif(false);

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('fichiers/edit.html.twig', [
            'fichier' => $fichier,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="fichiers_delete", methods={"POST"})
     */
    public function delete(Request $request, Fichiers $fichier): Response
    {
        if ($this->isCsrfTokenValid('delete' . $fichier->getId(), $request->request->get('_token'))) {
            $this->deleteFichierFiles($this->getParameter('images') . '/' . $fichier->getImages());
            $this->deleteFichierFiles($this->getParameter('fichierSTL') . '/' . $fichier->getFichierSTL());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($fichier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
    }


    public function upload($file, $target_directory, $slugger, $folderName)
    {
        if ($file) {
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            // this is needed to safely include the file name as part of the URL
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->getClientOriginalExtension();
            // Move the file to the directory where brochures are stored
            try {
                if ($folderName) {
                    $file->move(
                        $this->getParameter($target_directory) . '/' . $folderName,
                        $newFilename
                    );
                } else {
                    $file->move(
                        $this->getParameter($target_directory),
                        $newFilename
                    );
                }
            } catch (FileException $e) {
                // ... handle exception if something happens during file upload
            }

            // updates the 'brochureFilename' property to store the PDF file name
            // instead of its contents
            return $newFilename;
        }
    }

    public function deleteFichierFiles($profilePicture)
    {
        $filesystem = new Filesystem();
        $filesystem->remove($profilePicture);
    }
}
