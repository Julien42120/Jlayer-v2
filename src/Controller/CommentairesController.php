<?php

namespace App\Controller;

use App\Entity\Commentaires;
use App\Form\CommentairesType;
use App\Repository\CommentairesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/commentaires")
 */
class CommentairesController extends AbstractController
{
    /**
     * @Route("/", name="commentaires_index", methods={"GET"})
     */
    public function index(CommentairesRepository $commentairesRepository): Response
    {
        return $this->render('commentaires/index.html.twig', [
            'commentaires' => $commentairesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="commentaires_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $commentaire = new Commentaires();
        $form = $this->createForm(CommentairesType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($commentaire);
            $entityManager->flush();

            return $this->redirectToRoute('commentaires_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commentaires/new.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="commentaires_show", methods={"GET"})
     */
    public function show(Commentaires $commentaire): Response
    {
        return $this->render('commentaires/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="commentaires_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Commentaires $commentaire): Response
    {
        $form = $this->createForm(CommentairesType::class, $commentaire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('commentaires_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('commentaires/edit.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="commentaires_delete", methods={"POST"})
     */
    public function delete(Request $request, Commentaires $commentaire): Response
    {
        if ($this->isCsrfTokenValid('delete' . $commentaire->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($commentaire);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
    }
}
