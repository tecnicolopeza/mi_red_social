<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use App\Entity\User;

class GetUserExtension extends AbstractExtension{

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
            new TwigFilter('get_user', [$this, 'getUserFilter']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_user', [$this, 'getUserFilter']),
        ];
    }

    public function getUserFilter($user_id){

        $entityManager = $this->doctrine->getManager();
        $user_repository = $entityManager->getRepository(User::class);
        $user = $user_repository->findOneBy([
            'id' => $user_id
        ]);

        if (!empty($user) && is_object($user)) { 
            $result = $user;
        }else{
            $result = false;
        }

        return $result;
    }
}
