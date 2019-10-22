<?php

namespace App\Repository;

use App\Entity\AddUserToBandMessage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method AddUserToBandMessage|null find($id, $lockMode = null, $lockVersion = null)
 * @method AddUserToBandMessage|null findOneBy(array $criteria, array $orderBy = null)
 * @method AddUserToBandMessage[]    findAll()
 * @method AddUserToBandMessage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AddUserToBandMessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AddUserToBandMessage::class);
    }

    // /**
    //  * @return AddUserToBandMessage[] Returns an array of AddUserToBandMessage objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AddUserToBandMessage
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
