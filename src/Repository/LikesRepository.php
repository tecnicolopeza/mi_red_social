<?php

namespace App\Repository;

use App\Entity\Likes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Likes>
 *
 * @method Likes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Likes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Likes[]    findAll()
 * @method Likes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LikesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Likes::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Likes $entity, bool $flush = true): void
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
    public function remove(Likes $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return Likes[] Returns an array of Likes objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Likes
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    // consulta para las publicaciones a las que has dado like
    public function findLikes($user){
        
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
                SELECT * FROM publications p WHERE p.id IN (SELECT publication_id FROM likes l
                 WHERE l.user_id = :user) ORDER BY p.id DESC;
                ';
        
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['user'=>$user]);

        return $resultSet->fetchAll();
    }

    // consulta cantidad de likes de una publicacion
    public function findLikesPublication($publication){
        
        $conn = $this->getEntityManager()->getConnection();

        $sql = '
                SELECT * FROM publications p WHERE p.id IN (SELECT user_id FROM likes l
                 WHERE l.publication_id = :publication) ORDER BY p.id DESC;
                ';
        
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['publication'=>$publication]);

        return $resultSet->fetchAll();
    }


}
