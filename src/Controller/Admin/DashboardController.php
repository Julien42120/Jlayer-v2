<?php

namespace App\Controller\Admin;

use App\Entity\CategoryFichier;
use App\Entity\Commentaires;
use App\Entity\Fichiers;
use App\Entity\User;
use App\Form\CategoryFichierType;
use App\Repository\CategoryFichierRepository;
use App\Repository\CommentairesRepository;
use App\Repository\FichiersRepository;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    protected $userRepository;
    protected $fichiersRepository;
    protected $commentairesRepository;
    protected $categoryFichierRepository;

    public function __construct(
        UserRepository $userRepository,
        FichiersRepository $fichiersRepository,
        CommentairesRepository $commentairesRepository,
        CategoryFichierRepository $categoryFichierRepository
    ) {
        $this->userRepository = $userRepository;
        $this->fichiersRepository = $fichiersRepository;
        $this->commentairesRepository = $commentairesRepository;
        $this->categoryFichierRepository = $categoryFichierRepository;
    }


    /**
     * @Route("/admin", name="admin")
     */

    public function indexHome(): Response
    {
        return $this->render('bundles/EasyAdminBundle/welcome.html.twig', []);
    }

    ///////////////////////////////////////////////////
    //////////////  ADMIN USER  ////////////////////
    ///////////////////////////////////////////////////


    /**
     * @Route("/admin/users", name="admin_users")
     */

    public function index(): Response
    {

        return $this->render('bundles/EasyAdminBundle/user.html.twig', [
            'totalUsers' => count($this->userRepository->findAll()),
            'AllUsers' => $this->userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/delete/{id}", name="admin_user_delete", methods={"POST"})
     */
    public function deleteUsers(HttpFoundationRequest $request, User $user): Response
    {

        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $this->deletePicture($this->getParameter('avatar') . '/' . $user->getAvatar());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_users', [], Response::HTTP_SEE_OTHER);
    }

    public function deletePicture($profilePicture)
    {
        $filesystem = new Filesystem();
        $filesystem->remove($profilePicture);
    }

    ///////////////////////////////////////////////////
    //////////////  ADMIN FICHIER  ////////////////////
    ///////////////////////////////////////////////////

    /**
     * @Route("/admin/fichiers", name="admin_fichiers")
     */

    public function fichiers(): Response
    {
        return $this->render('bundles/EasyAdminBundle/fichiers.html.twig', [
            'AllFichiers' => $this->fichiersRepository->findAll(),
            'totalFichier' => count($this->fichiersRepository->findAll()),
        ]);
    }


    /**
     * @Route("/admin/fileDelete/{id}", name="admin_fichiers_delete", methods={"POST"})
     */
    public function deleteFichiers(HttpFoundationRequest $request, Fichiers $fichier): Response
    {
        if ($this->isCsrfTokenValid('delete' . $fichier->getId(), $request->request->get('_token'))) {
            $this->deletePicture($this->getParameter('images') . '/' . $fichier->getImages());
            if ($fichier->getFichierSTL()) {
                foreach ($fichier->getFichierSTL() as $fichierSTL) {
                    $this->deletePicture($this->getParameter('fichierSTL') . '/' . $fichierSTL);
                }
                $this->deletePicture($this->getParameter('fichierSTL') . '/' . $fichier->getNom());
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($fichier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_fichiers', [], Response::HTTP_SEE_OTHER);
    }


    ///////////////////////////////////////////////////
    //////////////  ADMIN COMMENTAIRES  ////////////////////
    ///////////////////////////////////////////////////


    /**
     * @Route("/admin/commentaires", name="admin_commentaires")
     */

    public function commentaires(): Response
    {
        $AllAvatarComment = $this->commentairesRepository->findBy(['user' => $this->getUser()]);

        return $this->render('bundles/EasyAdminBundle/commentaires.html.twig', [
            'AllComments' => $this->commentairesRepository->findAll(),
            'AllAvatarComments' => $AllAvatarComment

        ]);
    }

    /**
     * @Route("/admin/commentaires-delete/{id}", name="admin_commentaires_delete", methods={"POST"})
     */
    public function deleteCommentaires(HttpFoundationRequest $request, Commentaires $commentaire): Response
    {
        if ($this->isCsrfTokenValid('delete' . $commentaire->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($commentaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_commentaires', [], Response::HTTP_SEE_OTHER);
    }


    ///////////////////////////////////////////////////
    //////////////  ADMIN CATEGORY  ////////////////////
    ///////////////////////////////////////////////////


    /**
     * @Route("/admin/category", name="admin_category")
     */

    public function category(HttpFoundationRequest $request): Response
    {

        $categoryFichier = new CategoryFichier();
        $form = $this->createForm(CategoryFichierType::class, $categoryFichier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($categoryFichier);
            $entityManager->flush();

            return $this->redirectToRoute('admin_category', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('bundles/EasyAdminBundle/category.html.twig', [
            'AllCategorys' => $this->categoryFichierRepository->findAll(),
            'form' => $form->createView(),
            'totalCat' => count($this->categoryFichierRepository->findAll()),
        ]);
    }

    /**
     * @Route("/admin/category-delete/{id}", name="admin_category_fichier_delete", methods={"POST"})
     */
    public function delete(HttpFoundationRequest $request, CategoryFichier $categoryFichier): Response
    {
        if ($this->isCsrfTokenValid('delete' . $categoryFichier->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($categoryFichier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('admin_category', [], Response::HTTP_SEE_OTHER);
    }


    ///////////////////////////////////////////////////
    ///////////////////////////////////////////////////
    ///////////////////////////////////////////////////

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Jlayers');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linkToRoute('Accueil', 'fa fa-home', 'admin');
        yield MenuItem::linkToRoute('Utilisateurs', 'fa fa-user', 'admin_users');
        yield MenuItem::linkToRoute('Fichiers', 'fa fa-file', 'admin_fichiers');
        yield MenuItem::linkToRoute('Commentaires', 'fa fa-comment', 'admin_commentaires');
        yield MenuItem::linkToRoute('Catégories', 'fas fa-search-plus', 'admin_category');
        yield MenuItem::linkToRoute('Retour', 'fas fa-arrow-left', 'user_index');
        // yield MenuItem::linkToCrud('The Label', 'fas fa-list', EntityClass::class);
    }
}
