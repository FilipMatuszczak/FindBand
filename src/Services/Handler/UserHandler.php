<?php


namespace App\Services\Handler;


use App\Entity\User;
use App\Security\UserProvider;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class UserHandler
{
    /** @var EntityManager */
    private $entityManager;

    /** @var UserProvider */
    private $userProvider;

    /** @var PasswordHandler */
    private $passwordHanlder;

    public function __construct(EntityManagerInterface $entityManager, UserProvider $userProvider, PasswordHandler $passwordHandler)
    {
        $this->entityManager = $entityManager;
        $this->userProvider = $userProvider;
        $this->passwordHanlder = $passwordHandler;
    }

    public function updateAuthenticationLinkHash($username, $authenticationLink)
    {
        $user = $this->userProvider->loadUserByUsername($username);

        $hash = $this->passwordHanlder->getHashFromPlainTextAndSalt($authenticationLink, $user->getSalt());
        $user->setAuthenticationLink($hash);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function authenticateUser(User $user)
    {
        $user->setOptions(User::USER_VERIFIED);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}