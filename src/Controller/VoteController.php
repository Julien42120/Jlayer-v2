<?php

namespace App\Controller;

use App\Entity\Vote;
use App\Form\VoteType;
use App\Repository\FichiersRepository;
use App\Repository\UserRepository;
use App\Repository\VoteRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/vote")
 */
class VoteController extends AbstractController
{
    /**
     * @Route("/", name="vote_index", methods={"GET"})
     */
    public function index(VoteRepository $voteRepository): Response
    {
        return $this->render('vote/index.html.twig', [
            'votes' => $voteRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="vote_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $vote = new Vote();
        $form = $this->createForm(VoteType::class, $vote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($vote);
            $entityManager->flush();

            return $this->redirectToRoute('vote_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vote/new.html.twig', [
            'vote' => $vote,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="vote_show", methods={"GET"})
     */
    public function show(Vote $vote): Response
    {
        return $this->render('vote/show.html.twig', [
            'vote' => $vote,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="vote_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Vote $vote): Response
    {
        $form = $this->createForm(VoteType::class, $vote);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('vote_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('vote/edit.html.twig', [
            'vote' => $vote,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="vote_delete", methods={"POST"})
     */
    public function delete(Request $request, Vote $vote): Response
    {
        if ($this->isCsrfTokenValid('delete' . $vote->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($vote);
            $entityManager->flush();
        }

        return $this->redirectToRoute('vote_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/verifLiked", name="fichiers_Liked", methods={"POST"} , priority=1)
     */
    public function verifLikeFichier(VoteRepository $voteRepository, UserRepository $user, FichiersRepository $fichiersRepository): Response
    {
        $user = $this->getUser();
        if ($_POST['idFichier']) {
            $fichier = $fichiersRepository->findOneBy(['id' => $_POST['idFichier']]);
            $vote = $voteRepository->findOneBy(['fichier' => $fichier->getId(), 'user' => $user->getId()]);
            if ($vote) {
                // delete
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($vote);
                $entityManager->flush();
                $action = "Delete";
            } else {
                // création
                $vote = new Vote();
                $vote->setFichier($fichier);
                $vote->setUser($user);
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($vote);
                $entityManager->flush();
                $action = "Creation";
            }
            $voteTotal = count($voteRepository->findBy(['fichier' => $fichier->getId()]));
        }
        return new Response(json_encode(array('action' => $action, 'voteTotal' => $voteTotal)));
    }
}
