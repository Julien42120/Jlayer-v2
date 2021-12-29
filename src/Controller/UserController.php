<?php

namespace App\Controller;

use App\Entity\Fichiers;
use App\Entity\User;
use App\Form\FichiersType;
use App\Form\UserType;
use App\Repository\CommentairesRepository;
use App\Repository\FichiersRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET", "POST"})
     */
    public function index(Request $request, FichiersRepository $fichiersRepository, SluggerInterface $slugger, CommentairesRepository $commentairesRepository): Response
    {
        $fichier = new Fichiers();
        $form = $this->createForm(FichiersType::class, $fichier);

        $form->handleRequest($request);
        $images = $form->get('images')->getData();
        $fichiersSTL = $form->get('fichierSTL')->getData();
        $folderName = $form->get('nom')->getData();

        if ($form->isSubmitted() && $form->isValid()) {
            if ($images !== null) {
                $fichier->setImages($this->upload($images, 'images', $slugger, null));
            }

            if ($fichiersSTL !== null) {

                foreach ($fichiersSTL as $fichierSTL) {
                    $fichier->setFichierSTL($this->upload($fichierSTL, 'fichierSTL', $slugger, $folderName));
                }
            }

            $fichier->setUser($this->getUser());
            $fichier->setIsVerif(false);
            $this->getDoctrine()->getManager()->persist($fichier);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('user_index', [
                $this->addFlash('fichier', ' Votre fichier a été mis en ligne ')
            ], Response::HTTP_SEE_OTHER);
        }


        $commentaireByUser = $commentairesRepository->findBy(['user' => $this->getUser()], [], 4);

        return $this->render('user/index.html.twig', [
            'files' => $fichiersRepository->findBy(['user' => $this->getUser()->getId()]),
            'user' => $this->getUser(),
            'form' => $form->createView(),
            'commentaireByUser' => $commentaireByUser,
        ]);
    }

    /**
     * @Route("/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="user_edit", methods={"GET","POST"}, requirements={"id"="\d+"})
     */
    public function edit(Request $request, User $user, SluggerInterface $slugger, Filesystem $filesystem): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        $avatar = $form->get('avatar')->getData();
        $avatarNow = $user->getAvatar();

        if ($form->isSubmitted() && $form->isValid()) {
            if ($avatar !== null) {
                if ($avatarNow === "avatarNull.png") {
                    $user->setAvatar($this->upload($avatar, 'avatar', $slugger, null));
                } else {
                    $user->setAvatar($this->upload($avatar, 'avatar', $slugger, null));
                    $this->deletePicture($this->getParameter('avatar') . '/' . $user->getAvatar());
                }
            }
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('user_index', [
                'id' => $user->getId()
            ], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, TokenStorageInterface $tokenStorage): Response
    {
        $avatarNow = $user->getAvatar();
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            if ($avatarNow !== "avatarNull.png") {
                $this->deletePicture($this->getParameter('avatar') . '/' . $user->getAvatar());
            }
            $entityManager = $this->getDoctrine()->getManager();
            $tokenStorage->setToken();
            $entityManager->remove($user);
            $entityManager->flush();
        }


        return $this->redirectToRoute('home', [], Response::HTTP_SEE_OTHER);
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

            return $folderName . '/' . $newFilename;
        }
    }

    public function deletePicture($profilePicture)
    {
        $filesystem = new Filesystem();
        $filesystem->remove($profilePicture);
    }
}
