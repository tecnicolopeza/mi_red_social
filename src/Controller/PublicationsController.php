<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PublicationsController extends AbstractController
{
    #[Route('/publications', name: 'app_publications')]
    public function index(): Response
    {
        return $this->render('publications/index.html.twig', [
            'controller_name' => 'PublicationsController',
        ]);
    }
}
