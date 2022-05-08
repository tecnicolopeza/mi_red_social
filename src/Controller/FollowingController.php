<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class FollowingController extends AbstractController
{
    #[Route('/follow', name: 'follow', methods:['POST'])] #metodo POST para follow y unfollow
    public function follow(Request $request): Response
    {
        return new Response('follow function');
    }

    #[Route('/unfollow', name: 'unfollow', methods:['POST'])]
    public function unfollow(): Response
    {
        return new Response('unfollow function');
    }
}
