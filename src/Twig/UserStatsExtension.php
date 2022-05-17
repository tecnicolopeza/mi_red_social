<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use App\Entity\Likes;
use App\Entity\Following;
use App\Entity\Publications;

class UserStatsExtension extends AbstractExtension{

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
            new TwigFilter('user_stats', [$this, 'userStatsFilter']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('userStatus', [$this, 'userStatsFilter']),
        ];
    }

    public function userStatsFilter($user){

        $entityManager = $this->doctrine->getManager();
        $following_repository = $entityManager->getRepository(Following::class);
        $like_repository = $entityManager->getRepository(Likes::class);
        
        $publication_repository = $entityManager->getRepository(Publications::class);

        $user_following = $following_repository->findBy(array('user' => $user));
        $user_followers = $following_repository->findBy(array('followed'=>$user));
        $user_publications = $publication_repository->findBy(array('user'=>$user));
        $user_likes = $like_repository->findBy(array('user'=>$user));

        $result = array(
            'following' => count($user_following),
            'followers' => count($user_followers),
            'publications' => count($user_publications),
            'likes' => count($user_likes)
        );
        return $result;
    }
}
