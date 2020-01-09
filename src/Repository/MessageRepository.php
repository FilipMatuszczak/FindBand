<?php

namespace App\Repository;

use App\Entity\Message;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    public function getAllReceiversBySender(User $sender)
    {
        $qb = $this->createQueryBuilder('m');

        /** @var Message[] $messages */
        $messages = $qb->select('m')->distinct()
            ->where('m.sender = :sender')
            ->setParameter('sender', $sender)
            ->getQuery()
            ->execute();
        $receivers = [];

        foreach ($messages as $message) {
            $receivers[] = $message->getReceiver();
        }

        return $receivers;
    }

    public function getAllSendersByReceiver(User $receiver)
    {
        $qb = $this->createQueryBuilder('m');

        /** @var Message[] $messages */
        $messages = $qb->select('m')->distinct()
            ->where('m.receiver = :receiver')
            ->setParameter('receiver', $receiver)
            ->getQuery()
            ->execute();
        $senders = [];

        foreach ($messages as $message) {
            $senders[] = $message->getSender();
        }

        return $senders;
    }
}
