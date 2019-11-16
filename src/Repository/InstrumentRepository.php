<?php

namespace App\Repository;

use App\Entity\Instrument;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Instrument|null find($id, $lockMode = null, $lockVersion = null)
 * @method Instrument|null findOneBy(array $criteria, array $orderBy = null)
 * @method Instrument[]    findAll()
 * @method Instrument[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InstrumentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Instrument::class);
    }

    public function fetchSimilarNamesByPrefix($prefix, $limit)
    {
        return $this->createQueryBuilder('p')
            ->select('p.name')
            ->where('p.name like :prefix' )
            ->setParameter('prefix', $prefix .'%')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
