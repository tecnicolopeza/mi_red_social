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
use Symfony\Component\HttpFoundation\Session\Session;


class UserController extends AbstractController
{

    private $session;

    function __construct() {
        $this->session = new Session();
    }


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
            $flush = $entityManager->flush();

            if ($flush == null) { #si el registro fue correcto
                $msg = 'Registration has been successful.';
                $this->session->getFlashBag()->add('msg',$msg); #funcionabilidad para mensajes de confirmación 
                return $this->redirectToRoute('login'); #redirecciona al login
            }else{
                $msg = 'Registration faile, please try again.';
            }

        }

        return $this->render('user/register.html.twig', [
            'title' => 'Register','form'=>$form->createView()
        ]);
    }

    /**
     * @Route("/nickCheck", name="nickCheck", methods={"POST"})
     */
    public function nickCheck(Request $request, PersistenceManagerRegistry $doctrine): Response
    { #Funcion para comprobar si existe el usuario en la BD
        $nick = $request->get('nick');
        $repository = $doctrine->getRepository(User::class); //acceso repositorio entidad User
        $user_nick = $repository->findOneBy(['nick'=>$nick]);
        $result = "used";

        if ($user_nick != null) {
            $result = "used";
        }else{
            $result = "unused";
        }

        return new Response($result);
    }
}
