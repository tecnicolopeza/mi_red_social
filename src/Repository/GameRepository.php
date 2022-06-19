<?php

namespace App\Repository;

use App\Entity\Game;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PrivateMessages>
 *
 * @method PrivateMessages|null find($id, $lockMode = null, $lockVersion = null)
 * @method PrivateMessages|null findOneBy(array $criteria, array $orderBy = null)
 * @method PrivateMessages[]    findAll()
 * @method PrivateMessages[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class GameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Game::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Game $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Game $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }


    // public function getGameTop(){

    //     $conn = $this->getEntityManager()->getConnection();

    //     $sql = '
    //             SELECT * FROM game g WHERE g.name="AciertaImagen" ORDER BY g.score DESC;
    //             ';
        
    //     $stmt = $conn->prepare($sql);
    //     $resultSet = $stmt->executeQuery();

    //     return $resultSet->fetchAll();
    // }
}
