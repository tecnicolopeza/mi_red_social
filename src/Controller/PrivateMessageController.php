<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Entity\PrivateMessages;
use App\Form\PrivateMessageType;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Knp\Component\Pager\PaginatorInterface;

class PrivateMessageController extends AbstractController
{

    private $session;

    function __construct() {
        $this->session = new Session();
    }

    #[Route('/privateMessage', name: 'privateMessage')]
    public function privateMessage(Request $request, PersistenceManagerRegistry $doctrine){

        $em = $doctrine->getManager();
        $user = $this->getUser();

        $private_message = new PrivateMessages();
        $form = $this->createForm(PrivateMessageType::class, $private_message, array(
            'empty_data' => $user
        ));


        return $this->render('privateMessage/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
