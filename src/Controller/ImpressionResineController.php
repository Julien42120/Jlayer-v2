<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImpressionResineController extends AbstractController
{
    /**
     * @Route("/impression/resine", name="impression_resine")
     */
    public function index(): Response
    {
        return $this->render('impression_resine/index.html.twig', [
            'controller_name' => 'ImpressionResineController',
        ]);
    }
}
