<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use App\Entity\Likes;
use App\Entity\Publications;

class LikeExtension extends AbstractExtension{

    protected $doctrine;

    function __construct(PersistenceManagerRegistry $doctrine) {
        $this->doctrine = $doctrine;
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('likeStats', [$this, 'likesPublicationFunction']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('likeStatus', [$this, 'likeStatusFunction']),
            // new TwigFunction('likesPublication', [$this, 'likesPublicationFunction']),
        ];
    }

    public function likeStatusFunction($user, $publication){

        $entityManager = $this->doctrine->getManager();
        $like_repository = $entityManager->getRepository(Likes::class);
        $user_like = $like_repository->findOneBy([
            'user' => $user,
            'publication' => $publication,
        ]);

        if ($user_like != null && !empty($user_like) && is_object($user_like)) { 
            $result = true;
        }else{
            $result = false;
        }

        return $result;
    }

    // public function likesPublicationFunction($publication = null){

    //     $em = $this->doctrine->getManager();
    //     $repository = $em->getRepository(Publications::class);

    //     $likes = $repository->findBy(array(
    //         'id' => $publication
    //     ));

    //     return count($likes);

    //     // return $this->render('user/countLikes.html.twig', [
    //     //     'title' => 'CountLikes', 'likes' => $likes
    //     // ]);

    // }
}
