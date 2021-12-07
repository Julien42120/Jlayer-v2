<?php

namespace App\Controller;

use App\Entity\CategoryFichier;
use App\Form\CategoryFichierType;
use App\Repository\CategoryFichierRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category/fichier")
 */
class CategoryFichierController extends AbstractController
{
    /**
     * @Route("/", name="category_fichier_index", methods={"GET"})
     */
    public function index(CategoryFichierRepository $categoryFichierRepository): Response
    {
        return $this->render('category_fichier/index.html.twig', [
            'category_fichiers' => $categoryFichierRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="category_fichier_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $categoryFichier = new CategoryFichier();
        $form = $this->createForm(CategoryFichierType::class, $categoryFichier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($categoryFichier);
            $entityManager->flush();

            return $this->redirectToRoute('category_fichier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category_fichier/new.html.twig', [
            'category_fichier' => $categoryFichier,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="category_fichier_show", methods={"GET"})
     */
    public function show(CategoryFichier $categoryFichier): Response
    {
        return $this->render('category_fichier/show.html.twig', [
            'category_fichier' => $categoryFichier,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="category_fichier_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CategoryFichier $categoryFichier): Response
    {
        $form = $this->createForm(CategoryFichierType::class, $categoryFichier);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('category_fichier_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('category_fichier/edit.html.twig', [
            'category_fichier' => $categoryFichier,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="category_fichier_delete", methods={"POST"})
     */
    public function delete(Request $request, CategoryFichier $categoryFichier): Response
    {
        if ($this->isCsrfTokenValid('delete'.$categoryFichier->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($categoryFichier);
            $entityManager->flush();
        }

        return $this->redirectToRoute('category_fichier_index', [], Response::HTTP_SEE_OTHER);
    }
}
