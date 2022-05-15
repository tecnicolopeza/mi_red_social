<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Publications;
use App\Form\PublicationsType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Knp\Component\Pager\PaginatorInterface;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;


class PublicationsController extends AbstractController
{
    private $session;

    function __construct() {
        $this->session = new Session();
    }

    #[Route('/home', name: 'home')]
    public function publications(Request $request, PersistenceManagerRegistry $doctrine, PaginatorInterface $paginator): Response
    {
        $publication = new Publications();
        $form = $this->createForm(PublicationsType::class, $publication);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $file = $form['image']->getData();
                
                if(!empty($file) && $file != null){
                    $extension = $file->guessExtension();

                    if ($extension == 'jpg' || $extension == 'jpeg' || $extension == 'png' || $extension == 'gif') {
                        $name_img = $this->getUser()->getId() . time() . '.' .$extension;

                        $file->move("img", $name_img);
                        $publication->setImage($name_img);
                    }
                }else{
                    $publication->setImage("empty");
                }

                $file = $form['document']->getData();
                
                if(!empty($file) && $file != null){
                    $extension = $file->guessExtension();

                    if ($extension == 'pdf') {
                        $name_document = $this->getUser()->getId() . time() . '.' .$extension;

                        $file->move("document", $name_document);
                        $publication->setDocument($name_document);
                    }
                }else{
                    $publication->setDocument(null);
                }
                $publication->setCreated(new \DateTime("now"));
                $publication->setUser($this->getUser());
                $publication->setStatus("public");
                
                $entityManager = $doctrine->getManager();
                $entityManager->persist($publication);
                $flush = $entityManager->flush();

                if ($flush == null) { #si el registro fue correcto
                    $msg = 'Publication has been successful.';
    
                }else{
                    $msg = 'Publication faile, please try again.';
                }
                $this->session->getFlashBag()->add('msg',$msg); #funcionabilidad para mensajes de confirmación 
                return $this->redirectToRoute('home'); #redirecciona al home
        }

        $pagination = $this->getPublicationsPag($paginator,$request,$doctrine);

        return $this->render('publications/home.html.twig', [
            'title' => 'Publications', 'form'=>$form->createView(),
            'pagination' => $pagination,
        ]);
    }

    public function getPublicationsPag($paginator,$request, $doctrine){

        $repository = $doctrine->getRepository(Publications::class);
        $publications = $repository->findAllPublications($this->getUser()->getId());
        $pagination = $paginator->paginate(
            $publications,
            $request->query->getInt('page',1),
            5
        );

        return $pagination;
    }
}
