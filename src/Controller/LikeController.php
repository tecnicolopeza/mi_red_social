<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Publications;
use App\Entity\Likes;
use Symfony\Component\HttpFoundation\Request;

class LikeController extends AbstractController
{
    #[Route('/like', name: 'like', methods:['POST'])] #metodo POST para like
    public function like(Request $request, PersistenceManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();
        $publication_id = $request->get('publication');

        $entityManager = $doctrine->getManager();
        $publication_repository = $entityManager->getRepository(Publications::class);

        $publication = $publication_repository->find($publication_id);

        $likes = new Likes;

        $likes->setUser($user);
        $likes->setPublication($publication);

        $entityManager->persist($likes);
        $flush = $entityManager->flush();

        if ($flush == null){
            $msg = 'Now you are like this publication.';
        }else{
            $msg = 'Request failed, please try later.';
        }

        return new Response($msg);
    }

    #[Route('/dislike', name: 'dislike', methods:['POST'])]
    public function dislike(Request $request, PersistenceManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();
        $publication_id = $request->get('publication');

        $entityManager = $doctrine->getManager();
        $like_repository = $entityManager->getRepository(Likes::class);

        $like = $like_repository->findOneBy([
            'user' => $user,
            'publication' => $publication_id,
        ]);

        $entityManager->remove($like);
        $flush = $entityManager->flush();

        if ($flush == null){
            $msg = 'Now you are not like this publication.';
        }else{
            $msg = 'Request failed, please try later.';
        }

        return new Response($msg);
    }
}
