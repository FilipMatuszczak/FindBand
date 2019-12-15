<?php

namespace App\Services\Handler;

use App\Entity\Ban;
use App\Entity\User;
use App\Repository\BanRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class BlockedUsersHandler
{
    /** @var EntityManager */
    private $entityManager;

    /** @var BanRepository */
    private $banRepository;

    /** @var UserRepository */
    private $userRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param BanRepository $banRepository
     */
    public function __construct(EntityManagerInterface $entityManager, BanRepository $banRepository, UserRepository $userRepository)
    {
        $this->entityManager = $entityManager;
        $this->banRepository = $banRepository;
        $this->userRepository = $userRepository;
    }

    public function addBlockedUsersForUser(User $user, $blockedUsersData)
    {
        $users = $this->userRepository->findBy(['username' => $this->decorateRawData($blockedUsersData)]);
        $userBans = $this->banRepository->findBy(['user' => $user]);

        foreach ($userBans as $ban) {
            $this->entityManager->remove($ban);
        }

        $this->entityManager->flush();

        foreach ($users as $userToBan) {
            $ban = new Ban();
            $ban->setUser($user);
            $ban->setSubject($userToBan);
            $ban->setTimestamp(new \DateTime());

            $this->entityManager->persist($ban);
        }

        $this->entityManager->flush();

        return $users;
    }

    private function decorateRawData($blockedUsersData)
    {
        $usernames = [];
        if (!empty($blockedUsersData)) {
            foreach ($blockedUsersData as $user) {
                $usernames[] = $user['value'];
            }
        }

        return $usernames;
    }
}