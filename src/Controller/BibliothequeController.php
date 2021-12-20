<?php

namespace App\Controller;

use App\Entity\CategoryFichier;
use App\Entity\Fichiers;
use App\Repository\CategoryFichierRepository;
use App\Repository\FichiersRepository;
use App\Repository\VoteRepository;
use Symfony\Component\HttpFoundation\Request as HttpFoundationRequest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Proxies\__CG__\App\Entity\CategoryFichier as EntityCategoryFichier;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\BrowserKit\Request;

class BibliothequeController extends AbstractController
{
    /**
     * @Route("/bibliotheque", name="bibliotheque")
     */
    public function index(HttpFoundationRequest $request, FichiersRepository $fichiersRepository, CategoryFichierRepository $categoryFichierRepository, VoteRepository $voteRepository): Response
    {
        $allCategory = $categoryFichierRepository->findAll();
        $vote = $voteRepository->findAll();
        $inputValue = $request->get('searchInput');
        $fichierTab = [];

        // Nombres d'élément par page 
        $limit = 12;
        // Recuperation du n° de page 
        $page = (int)$request->query->get('page', 1);
        // Nombre de Fichiers total
        $total = $fichiersRepository->getTotalFichiers();


        // input de recherche
        if ($inputValue != "") {
            $result = $fichiersRepository->findAllResearchMatch($inputValue);
            if ($result == null) {
                $this->addFlash('success', 'Le fichier que vous cherché n\'est pas encore disponible');
            }
            if ($result) {
                $fichierTab = $result;
            } else {
                // tout les fichiers
                $fichierTab = $fichiersRepository->findAll();
            }
        } else {
            $fichierTab = $fichiersRepository->getPaginatedFichiers($page, $limit);
        }

        return $this->render('bibliotheque/index.html.twig', [
            'controller_name' => 'BibliothequeController',
            'fichiersSTL' => $fichierTab,
            'category' => $allCategory,
            'votes' => $vote,
            'inputValue' => $inputValue,
            'total' => $total,
            'limit' => $limit,
            'page' => $page
        ]);
    }

    // public function myAction(PaginatorInterface $paginator, $fichierTab, Request $request)
    // {
    //     $this->get('knp_paginator');
    //     $fichierTab = $paginator->paginate(
    //         $fichierTab,
    //         $request->query->getInt('page', 1),
    //         15
    //     );
    //     return $this->render('bibliotheque/index.html.twig', [
    //         'fichierTab' => $fichierTab,
    //     ]);
    // }
}
