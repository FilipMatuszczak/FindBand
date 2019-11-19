<?php

namespace App\Repository;

use App\Entity\MusicGenre;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MusicGenre|null find($id, $lockMode = null, $lockVersion = null)
 * @method MusicGenre|null findOneBy(array $criteria, array $orderBy = null)
 * @method MusicGenre[]    findAll()
 * @method MusicGenre[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MusicGenreRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MusicGenre::class);
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
