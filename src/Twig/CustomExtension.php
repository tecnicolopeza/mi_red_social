<?php

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;
use App\Entity\User;

class CustomExtension extends AbstractExtension
{
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
            new TwigFilter('userById', [$this, 'userByIdFilter']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('function_name', [$this, 'doSomething']),
        ];
    }

    public function userByIdFilter($user_id){

        $entityManager = $this->doctrine->getManager();
        $user_repository = $entityManager->getRepository(User::class);
        $user = $user_repository->find($user_id);

        return $user;
    }
}
