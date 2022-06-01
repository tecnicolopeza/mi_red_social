<?php

namespace App\Repository;

use App\Entity\PrivateMessages;
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
class PrivateMessagesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PrivateMessages::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(PrivateMessages $entity, bool $flush = true): void
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
    public function remove(PrivateMessages $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    // /**
    //  * @return PrivateMessages[] Returns an array of PrivateMessages objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PrivateMessages
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getPrivateMessages($user_id, $type = null){

        $conn = $this->getEntityManager()->getConnection();

        if($type == "sended"){

            $sql = '
                SELECT * FROM private_messages p WHERE p.sender_id = :user 
                ORDER BY p.id DESC;
                ';

        }else{

            $sql = '
                SELECT * FROM private_messages p WHERE p.receiver_id = :user 
                ORDER BY p.id DESC;
                ';

        }
        
        $stmt = $conn->prepare($sql);
        $resultSet = $stmt->executeQuery(['user'=>$user_id]);

        return $resultSet->fetchAll();
    }
}
