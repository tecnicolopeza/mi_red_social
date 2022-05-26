<?php

namespace App\Controller;

use App\Entity\Notifications;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use App\Services\NotificationService;

class NotificationController extends AbstractController
{

    #[Route('/notification', name: 'notification')]
    public function notification(Request $request, PaginatorInterface $paginator, PersistenceManagerRegistry $doctrine){

        $em = $doctrine->getManager();

        $user = $this->getUser();
        $repositoryNotification = $em->getRepository(Notifications::class);

        $user_id = $user->getId();

        $notification = $repositoryNotification->findNotification($user_id);

        $notifications = $paginator->paginate(
            $notification,
            $request->query->getInt('page',1),
            5
        );

        return $this->render('user/notification.html.twig', [
            'title' => 'Notifications',
            'user' => $user,
            'pagination' => $notifications
        ]);
    }
}