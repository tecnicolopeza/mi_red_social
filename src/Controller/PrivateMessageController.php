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

        $form->handleRequest($request);

        if($form->isSubmitted()){
            if($form->isValid()){
                //upload image
                $file = $form['image']->getData();
                
                if(!empty($file) && $file != null){
                    $extension = $file->guessExtension();

                    if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png' || $extension == 'gif') {
                        $name_img = $this->getUser()->getId() . time() . '.' .$extension;

                        $file->move("messages/img", $name_img);
                        $private_message->setImage($name_img);
                    }
                }else{
                    $private_message->setImage("empty");
                }

                //upload file
                $file = $form['file']->getData();
                
                if(!empty($file) && $file != null){
                    $extension = $file->guessExtension();

                    if ($extension == 'pdf') {
                        $name_document = $this->getUser()->getId() . time() . '.' .$extension;

                        $file->move("messages/document", $name_document);
                        $private_message->setFile($name_document);
                    }
                }else{
                    $private_message->setFile(null);
                }
                $private_message->setSender($this->getUser());
                $private_message->setCreated(new \DateTime("now"));
                $private_message->setReaded(0);

                $em->persist($private_message);
                $flush = $em->flush();

                if($flush == null){
                    $status = "El mensaje privado se ha enviado correctamente";
                }else{
                    $status = "El mensaje privado no se ha enviado";
                }

            }else{
                $status = "El mensaje privado no se ha enviado";
            }

            $this->session->getFlashBag()->add('msg',$status);
            return $this->redirectToRoute('privateMessage');
        }


        return $this->render('privateMessage/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    //mensajes enviados 
    #[Route('/sended', name: 'sended')]
    public function sendedAction(Request $request, PersistenceManagerRegistry $doctrine, PaginatorInterface $paginator){

        $private_messages = $this->getPrivateMessages($request, "sended", $doctrine, $paginator);

        return $this->render('privateMessage/sended.html.twig', [
            'pagination' => $private_messages
        ]);
    }

    public function getPrivateMessages($request, $type = null,  $doctrine, $paginator){

        $user = $this->getUser();
        $user_id = $user->getId();
        $repository = $doctrine->getRepository(PrivateMessages::class);
        $publications = $repository->getPrivateMessages($user_id, $type);
        $pagination = $paginator->paginate(
            $publications,
            $request->query->getInt('page',1),
            5
        );

        return $pagination;
    }
}
