<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function login(): Response
    {
        return $this->render('user/login.html.twig', [
            'title' => 'Login',
        ]);
    }

    #[Route('/register', name: 'register')]
    public function register(Request $request, UserPasswordHasherInterface $encoder, PersistenceManagerRegistry $doctrine): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $user->setActive(false);
            $user->setRoles(['ROLE_USER']);
            $encoded = $encoder->hashPassword($user,$user->getPassword()); #codifica la contraseña
            $user->setPassword($encoded);

            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

        }

        return $this->render('user/register.html.twig', [
            'title' => 'Register','form'=>$form->createView()
        ]);
    }
}
