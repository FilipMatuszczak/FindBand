<?php

namespace App\Services\Factory;

use App\Entity\Message;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\UserProvider;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class MessageFactory
{
    /** @var UserProvider */
    private $userProvider;

    /** @var EntityManager */
    private $entityManager;

    /** @var UserRepository */
    private $userRepository;

    public function __construct(UserProvider $userProvider, EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        $this->userProvider = $userProvider;
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
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

    public function createMessagesForAllNewsletterUsers($text)
    {
        $users = $this->userRepository->fetchAllNewsletterUsers();
        $admin = $this->userProvider->loadUserByUsername('admin');

        foreach ($users as $user)
        {
            $message = new Message();

            $message
                ->setSender($admin)
                ->setReceiver($user)
                ->setTimestamp(new \DateTime())
                ->setText($text);

            $this->entityManager->persist($message);
        }

        $this->entityManager->flush();
    }
}