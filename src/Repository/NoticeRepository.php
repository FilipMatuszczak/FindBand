<?php

namespace App\Repository;

use App\Entity\Band;
use App\Entity\Instrument;
use App\Entity\Notice;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Notice|null find($id, $lockMode = null, $lockVersion = null)
 * @method Notice|null findOneBy(array $criteria, array $orderBy = null)
 * @method Notice[]    findAll()
 * @method Notice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NoticeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Notice::class);
    }

    public function fetchNoticesByInstrumentsExcludingUser($instruments, User $user)
    {
        $queryBuilder =  $this->createQueryBuilder('n');

        $queryBuilder->where('n.instrument in (:instruments)')
            ->andWhere('n.user != :user')
            ->orderBy('n.timestamp', 'DESC')
            ->setMaxResults(5)
            ->setParameters([
                'instruments'=> $instruments,
                'user' => $user,
            ]);

        return $queryBuilder->getQuery()->getResult();
    }

    public function fetchNoticesByFilters($from, $max, $sorting, ?Instrument $instrument, ?User $user, ?Band $band)
    {
        $queryBuilder =  $this->createQueryBuilder('u');

        if (isset($user)) {
            $queryBuilder->andWhere('u.user = :user')
                ->setParameter('user', $user);
        }

        if (isset($instrument)) {
            $queryBuilder->andWhere('u.instrument = :instrument')
                ->setParameter('instrument', $instrument);
        }

        if (isset($band)) {
            $queryBuilder->andWhere('u.band = :band')
                ->setParameter('band', $band);
        }

        $queryBuilder
            ->setFirstResult($from)
            ->setMaxResults($max)
            ->orderBy('u.' . $sorting['sortKey'], $sorting['dir']);

        return $queryBuilder->getQuery()->getResult();
    }
}
