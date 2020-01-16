<?php

namespace App\Repository;

use App\Entity\Ban;
use App\Entity\Report;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Report|null find($id, $lockMode = null, $lockVersion = null)
 * @method Report|null findOneBy(array $criteria, array $orderBy = null)
 * @method Report[]    findAll()
 * @method Report[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Report::class);
    }

    public function fetchBansByUsers($users)
    {
        $queryBuilder =  $this->createQueryBuilder('b');

        return $queryBuilder
            ->select('b')
            ->where('BIT_AND(b.options, ' . Report::OPTIONS_USER_BANNED .') = 1')
            ->andWhere('b.user in (:users)')
            ->groupBy('b.user')
            ->setParameter('users', $users)
            ->getQuery()->execute();
    }
}
