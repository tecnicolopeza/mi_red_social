<?php

namespace App\Repository;

use App\Entity\Following;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Following>
 *
 * @method Following|null find($id, $lockMode = null, $lockVersion = null)
 * @method Following|null findOneBy(array $criteria, array $orderBy = null)
 * @method Following[]    findAll()
 * @method Following[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FollowingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Following::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Following $entity, bool $flush = true): void
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
    public function remove(Following $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Following[] Returns an array of Following objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Following
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
    // consulta para las personas a las que sigues
    public function findFollowing($user){
        
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
                SELECT * FROM user u WHERE u.id IN (SELECT followed_id FROM following f
                 WHERE f.user_id = :user) ORDER BY u.id DESC;
                ';
        
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['user'=>$user]);

        return $resultSet->fetchAll();
    }

    // consulta para las personas que te siguen
    public function findFollowed($user){
        
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
                SELECT * FROM user u WHERE u.id IN (SELECT user_id FROM following f
                 WHERE f.followed_id = :user) ORDER BY u.id DESC;
                ';
        
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['user'=>$user]);

        return $resultSet->fetchAll();
    }
}