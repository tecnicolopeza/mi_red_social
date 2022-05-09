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
use Knp\Component\Pager\PaginatorInterface;

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



    //Editar user desde account
    #[Route('/account', name: 'account')]
    public function editUser(Request $request, PersistenceManagerRegistry $doctrine): Response { 

        $user_image = $this->getUser()->getImage();
        $form = $this->createForm(EditUserType::class, $this->getUser());


        $form->handleRequest($request);

        $repository = $doctrine->getRepository(User::class); //acceso repositorio entidad User
        $user_bbdd = $repository->findOneBy(['email'=>$this->getUser()->getEmail()]);

        if($form->isSubmitted() && $form->isValid()){

            if ($user_bbdd != null && $user_bbdd->getEmail() == $this->getUser()->getEmail() &&
            $user_bbdd->getNick() == $this->getUser()->getNick() || $user_bbdd == null) {
                
                $file = $form['image']->getData();
                
                if(!empty($file) && $file != null){
                    $extension = $file->guessExtension();

                    if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png' || $extension == 'gif') {
                        $name_img = $this->getUser()->getId() . time() . '.' .$extension;

                        $file->move("img", $name_img);
                        $this->getUser()->setImage($name_img);
                    }
                }else{
                    $this->getUser()->setImage($user_image);
                }


                $entityManager = $doctrine->getManager();
                $entityManager->persist($this->getUser());
                $flush = $entityManager->flush();
            }
            if ($flush == null) { #si el registro fue correcto
                $msg = 'Data modification has been successful.';

            }else{
                $msg = 'Data modification faile, please try again.';
            }
            $this->session->getFlashBag()->add('msg',$msg); #funcionabilidad para mensajes de confirmación 
            return $this->redirectToRoute('account'); #redirecciona al login
        }
        return $this->render('user/editUser.html.twig', [
            'title' => 'Account', 'form'=>$form->createView()
        ]);

    }

    #[Route('/people', name: 'people')]
    public function users(Request $request, PaginatorInterface $paginator, PersistenceManagerRegistry $doctrine)
    {

        $em = $doctrine->getManager();
        $repository = $em->getRepository(User::class);
        $query = $repository->createQueryBuilder('p')
        ->orderBy('p.id','ASC')->getQuery();
        $users = $paginator->paginate(
            $query,
            $request->query->getInt('page',1),
            5
        );

        return $this->render('user/users.html.twig', [
            'title' => 'People', 'users' => $users
        ]);
    }

    #[Route('/search', name: 'search')]
    public function userSearch(Request $request, PaginatorInterface $paginator, PersistenceManagerRegistry $doctrine)
    {

        $search = $request->query->get('search', null);

        if($search == null){
            return $this->redirectToRoute('home');
        }
        $em = $doctrine->getManager();
        $repository = $em->getRepository(User::class);
        $query = $repository->createQueryBuilder('p')
        ->orderBy('p.id','ASC')
        ->where('p.name LIKE :searchTerm OR p.surname LIKE :searchTerm OR p.nick LIKE :searchTerm')
        ->setParameter('searchTerm','%' . $search . '%')->getQuery();
        $users = $paginator->paginate(
            $query,
            $request->query->getInt('page',1),
            5
        );

        return $this->render('user/users.html.twig', [
            'title' => 'People', 'users' => $users
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
        $result = "";

        if ($user_nick != null) {
            $result = "used";
        }else{
            $result = "unused";
        }

        return new Response($result);
    }
}
