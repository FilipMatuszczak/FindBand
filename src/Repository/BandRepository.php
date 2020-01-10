<?php

namespace App\Repository;

use App\Entity\Band;
use App\Entity\MusicGenre;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Band|null find($id, $lockMode = null, $lockVersion = null)
 * @method Band|null findOneBy(array $criteria, array $orderBy = null)
 * @method Band[]    findAll()
 * @method Band[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BandRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Band::class);
    }

    public function fetchBandsByFilters($from, $to, $sorting, $title, ?MusicGenre $musicGenre, ?User $member)
    {
        $queryBuilder =  $this->createQueryBuilder('u');

        if (!empty($title)) {
            $queryBuilder->andWhere('u.title like :title')
                ->setParameter('title', $title . '%');
        }

        if (isset($member)) {
            $queryBuilder->andWhere(':user MEMBER OF u.users')
                ->setParameter('user', $member);
        }

        if (isset($musicGenre)) {
            $queryBuilder->andWhere(':musicGenre MEMBER OF u.musicGenre')
                ->setParameter('musicGenre', $musicGenre);
        }

        $queryBuilder
            ->setFirstResult($from)
            ->setMaxResults($to)
            ->orderBy('u.' . $sorting['sortKey'], $sorting['dir']);

        return $queryBuilder->getQuery()->getResult();
    }

    public function fetchBandsByMusicGenreExcludingUser($musicGenres, User $user)
    {
        $queryBuilder =  $this->createQueryBuilder('b');

        $queryBuilder
            ->select('b')
            ->andWhere(':musicGenre MEMBER OF b.musicGenre')
            ->andWhere(':user NOT MEMBER OF b.users')
            ->orderBy('b.createdDate', 'DESC')
            ->setMaxResults(5)
            ->setParameters([
                'musicGenre' => $musicGenres,
                'user' => $user,
            ]);

        return $queryBuilder->getQuery()->getResult();
    }

    public function fetchBandsById($bandIds)
    {
        $queryBuilder =  $this->createQueryBuilder('b');

        $queryBuilder
            ->select('b')
            ->andWhere('b.bandId in (:bandIds)')
            ->setParameter('bandIds', $bandIds);

        return $queryBuilder->getQuery()->getResult();
    }
}
