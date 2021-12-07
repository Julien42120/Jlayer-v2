<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImpressionFilController extends AbstractController
{
    /**
     * @Route("/impression/fil", name="impression_fil")
     */
    public function index(): Response
    {
        return $this->render('impression_fil/index.html.twig', [
            'controller_name' => 'ImpressionFilController',
        ]);
    }
}
