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

    public function fetchNewMessagesByBands($bands)
    {
        return $this->createQueryBuilder('m')
            ->where('m.band in (:bands)')
            ->andWhere('m.options = ' . AddUserToBandMessage::OPTION_NEW)
            ->setParameter('bands', $bands)
            ->getQuery()
            ->getResult();
    }
}
