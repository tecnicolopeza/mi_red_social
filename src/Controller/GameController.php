<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Game;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use App\Services\NotificationService;

class GameController extends AbstractController
{
    #[Route('/juego', name: 'juego')]
    public function juego(Request $request, PersistenceManagerRegistry $doctrine, PaginatorInterface $paginator){

        $em = $em = $doctrine->getManager();

        $game_repo = $em->getRepository(Game::class);
        // Busca las puntuaciones del juego
        $scores = $game_repo->findBy(array(
            'name' => 'AciertaImagen'
        ),array('score' => 'DESC'));
        $scores = array_slice($scores, 0, 10);

        return $this->render('juego_adivina_la_imagen/index.html.twig', [
            'title' => 'Game',
            'pagination' => $scores,
        ]);
    }

    #[Route('/scoreUp', name: 'scoreUp')] 
    public function like(Request $request, PersistenceManagerRegistry $doctrine, NotificationService $notificationService): Response
    {
        $entityManager = $doctrine->getManager();
        $user = $this->getUser();
        $score = $request->get('score');
        $name = $request->get('name');

        $game = new Game;

        $game->setUser($user);
        $game->setScore($score);
        $game->setCreated(new \DateTime("now"));
        $game->setName($name);

        $entityManager->persist($game);
        $flush = $entityManager->flush();


        if ($flush == null){
            $notificationService->set($user, 'AciertaImagen', $user->getId(), $score);
            $msg = 'Score Up.';
        }else{
            $msg = 'Request failed, please try later.';
        }

        return new Response($msg);
    }

}
