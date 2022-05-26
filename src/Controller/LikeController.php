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
use Knp\Component\Pager\PaginatorInterface;
use App\Services\NotificationService;

class LikeController extends AbstractController
{
    #[Route('/like', name: 'like', methods:['POST'])] #metodo POST para like
    public function like(Request $request, PersistenceManagerRegistry $doctrine, NotificationService $notificationService): Response
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
            $notificationService->set($publication->getUser(), 'like', $user->getId(), $publication->getId());
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

    // Publicaciones a las que has dado me gusta
    #[Route('/likesAction', name: 'likesAction')]
        public function likesAction(Request $request, PaginatorInterface $paginator, PersistenceManagerRegistry $doctrine)
        {
    
            $em = $doctrine->getManager();
            $nickname = $request->get('nick');

            if($nickname != null){
                $repository = $em->getRepository(User::class);
                $user = $repository->findOneBy(array("nick" => $nickname));
            }else{
                $user = $this->getUser();
            }

            if(empty($user) || !is_object($user)){
                return $this->redirectToRoute('home');
            }

            $repositoryLikes = $em->getRepository(Likes::class);

            $user_id = $user->getId();

            $likes = $repositoryLikes->findLikes($user_id);

            $publications = $paginator->paginate(
                $likes,
                $request->query->getInt('page',1),
                5
            );
    
            return $this->render('user/likes.html.twig', [
                'title' => 'Likes', 'pagination' => $publications, 'user' => $user
            ]);
        }
}
