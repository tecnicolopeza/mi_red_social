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
use App\Services\NotificationService;

class FollowingController extends AbstractController
{
    #[Route('/follow', name: 'follow', methods:['POST'])] #metodo POST para follow y unfollow
    public function follow(Request $request, PersistenceManagerRegistry $doctrine, NotificationService $notificationService): Response
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
            $notificationService->set($followed, 'follow', $user->getId());
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
        #[Route('/following/{nickname}', name: 'following')]
        public function following(Request $request, $nickname = null, PaginatorInterface $paginator, PersistenceManagerRegistry $doctrine)
        {
    
            $em = $doctrine->getManager();
            // $nickname = $request->get('nick');

            if($nickname != null){
                $repository = $em->getRepository(User::class);
                $user = $repository->findOneBy(array("nick" => $nickname));
            }else{
                $user = $this->getUser();
            }

            if(empty($user) || !is_object($user)){
                return $this->redirectToRoute('home');
            }

            $repositoryFollowing = $em->getRepository(Following::class);

            $user_id = $user->getId();

            $following = $repositoryFollowing->findFollowing($user_id);

            $users = $paginator->paginate(
                $following,
                $request->query->getInt('page',1),
                5
            );
    
            return $this->render('user/following.html.twig', [
                'title' => 'Following', 'pagination' => $users, 'profile_user' => $user
            ]);
        }

        // usuarios que le siguen
        #[Route('/followed/{nickname}', name: 'followed')]
        public function followed(Request $request, $nickname = null, PaginatorInterface $paginator, PersistenceManagerRegistry $doctrine)
        {
    
            $em = $doctrine->getManager();
            // $nickname = $request->get('nick');

            if($nickname != null){
                $repository = $em->getRepository(User::class);
                $user = $repository->findOneBy(array("nick" => $nickname));
            }else{
                $user = $this->getUser();
            }

            if(empty($user) || !is_object($user)){
                return $this->redirectToRoute('home');
            }

            $repositoryFollowing = $em->getRepository(Following::class);

            $user_id = $user->getId();

            $following = $repositoryFollowing->findFollowed($user_id);

            $users = $paginator->paginate(
                $following,
                $request->query->getInt('page',1),
                5
            );
    
            return $this->render('user/followed.html.twig', [
                'title' => 'Followed', 'users' => $users, 'profile_user' => $user
            ]);
        }
}
