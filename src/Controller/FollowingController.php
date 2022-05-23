<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\User;
use App\Entity\Following;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;


class FollowingController extends AbstractController
{
    #[Route('/follow', name: 'follow', methods:['POST'])] #metodo POST para follow y unfollow
    public function follow(Request $request, PersistenceManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();
        $followed_id = $request->get('followed');

        $entityManager = $doctrine->getManager();
        $user_repository = $entityManager->getRepository(User::class);

        $followed = $user_repository->find($followed_id);

        $following = new Following;

        $following->setUser($user);
        $following->setFollowed($followed);

        $entityManager->persist($following);
        $flush = $entityManager->flush();

        if ($flush == null){
            $msg = 'Now you are following this user.';
        }else{
            $msg = 'Request failed, please try later.';
        }

        return new Response($msg);
    }

    #[Route('/unfollow', name: 'unfollow', methods:['POST'])]
    public function unfollow(Request $request, PersistenceManagerRegistry $doctrine): Response
    {
        $user = $this->getUser();
        $followed_id = $request->get('followed');

        $entityManager = $doctrine->getManager();
        $user_repository = $entityManager->getRepository(Following::class);

        $followed = $user_repository->findOneBy([
            'user' => $user,
            'followed' => $followed_id,
        ]);

        $entityManager->remove($followed);
        $flush = $entityManager->flush();

        if ($flush == null){
            $msg = 'Now you are not following this user.';
        }else{
            $msg = 'Request failed, please try later.';
        }

        return new Response($msg);
    }

        // usuarios que esta siguiendo
        #[Route('/following', name: 'following')]
        public function following(Request $request, PaginatorInterface $paginator, PersistenceManagerRegistry $doctrine)
        {
    
            $em = $doctrine->getManager();
            $repository = $em->getRepository(Following::class);
            $userId = $this->getUser()->getId();

            //consulta para obtener los seguidos por el usuario
            $query = $repository->createQueryBuilder('f')
            ->orderBy('f.id','ASC')
            ->where('f.user_id = '.$userId)->getQuery();

            // $emUser = $doctrine->getManager();
            // $repositoryUser = $emUser->getRepository(User::class);

            // $query2 = $repositoryUser->createQueryBuilder('u')
            // ->orderBy('u.id','ASC')
            // ->where('u.id == '.$userId)->getQuery();

            $users = $paginator->paginate(
                $query,
                $request->query->getInt('page',1),
                5
            );
    
            return $this->render('user/following.html.twig', [
                'title' => 'Following', 'users' => $users
            ]);
        }
}
