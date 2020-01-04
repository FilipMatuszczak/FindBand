<?php

namespace App\Services\Factory;

use App\Entity\Message;
use App\Entity\User;
use App\Security\UserProvider;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class MessageFactory
{
    /** @var UserProvider */
    private $userProvider;

    /** @var EntityManager */
    private $entityManager;

    public function __construct(UserProvider $userProvider, EntityManagerInterface $entityManager)
    {
        $this->userProvider = $userProvider;
        $this->entityManager = $entityManager;
    }

    public function createMessage(User $sender, $receiverId, $text)
    {
        $message = new Message();

        $message
            ->setSender($sender)
            ->setReceiver($this->userProvider->loadUserById($receiverId))
            ->setTimestamp(new \DateTime())
            ->setText($text);

        $this->entityManager->persist($message);
        $this->entityManager->flush($message);
    }
}