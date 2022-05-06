<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\User;
use App\Form\RegisterType;
use App\Form\EditUserType;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{

    private $session;

    function __construct() {
        $this->session = new Session();
    }


    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        //En caso de no estar registrado no puedes entrar
        if (is_object($this->getUser())) {
            return $this->redirectToRoute('home');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUserName = $authenticationUtils->getLastUsername();

        return $this->render('user/login.html.twig', [
            'title' => 'Login',
            'error' => $error,
            'last_username' => $lastUserName,
        ]);
    }

    #[Route('/logout', name: 'logout')]
    public function logout() { }


    #[Route('/account', name: 'account')]
    public function editUser(Request $request) { 

        $user = $this->getUser();
        $form = $this->createForm(EditUserType::class, $user);

        return $this->render('user/editUser.html.twig', [
            'title' => 'Account', 'form'=>$form->createView()
        ]);

    }


    #[Route('/register', name: 'register')]
    public function register(Request $request, UserPasswordHasherInterface $encoder, PersistenceManagerRegistry $doctrine): Response
    {
        //En caso de estar registrado no puedes entrar
        if (is_object($this->getUser())) {
            return $this->redirectToRoute('home');
        }
        //Se crea un nuevo usuario con los datos del formulario
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
        var_dump($result);

        return new Response($result);
    }
}
