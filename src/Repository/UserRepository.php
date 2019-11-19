<?php

namespace App\Repository;

use App\Entity\City;
use App\Entity\Instrument;
use App\Entity\MusicGenre;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function fetchUsersByFilters(
        $from,
        $to,
        $sorting,
        $firstName,
        $lastName,
        ?Instrument $instrument,
        ?City $city,
        ?MusicGenre $musicGenre
    )
    {
        $queryBuilder =  $this->createQueryBuilder('u');

        if (!empty($firstName)) {
            $queryBuilder->andWhere('u.firstname like :firstname')
                ->setParameter('firstname', $firstName . '%');
        }

        if (!empty($lastName)) {
            $queryBuilder->andWhere('u.lastname like :lastname')
                ->setParameter('lastname', $lastName . '%');
        }

        if (isset($instrument)) {
            $queryBuilder->andWhere(':instrument MEMBER OF u.instruments')
                ->setParameter('instrument', $instrument);
        }

        if (isset($city)) {
            $queryBuilder->andWhere('u.city = :city')
                ->setParameter('city', $city);
        }

        if (isset($musicGenre)) {
            $queryBuilder->andWhere(':musicGenre MEMBER OF u.musicGenre')
                ->setParameter('musicGenre', $musicGenre);
        }

        $queryBuilder
            ->andWhere('BIT_AND(u.options, ' . User::USER_ADMIN .') = 0')
            ->setFirstResult($from)
            ->setMaxResults($to)
            ->orderBy('u.' . $sorting['sortKey'], $sorting['dir']);

        return $queryBuilder->getQuery()->getResult();
    }

}
