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
    private $passwordHandler;

    public function __construct(EntityManagerInterface $entityManager, UserProvider $userProvider, PasswordHandler $passwordHandler)
    {
        $this->entityManager = $entityManager;
        $this->userProvider = $userProvider;
        $this->passwordHandler = $passwordHandler;
    }

    public function updateAuthenticationLinkHash($username, $authenticationLink)
    {
        $user = $this->userProvider->loadUserByUsername($username);

        $hash = $this->passwordHandler->getHashFromPlainTextAndSalt($authenticationLink, $user->getSalt());
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

    public function generateChangePasswordLinkForUser(User $user, $authenticationLink)
    {
        $hash = $this->passwordHandler->getHashFromPlainTextAndSalt($authenticationLink, $user->getSalt());

        $user->setChangePasswordLink($hash);
        $user->setChangePasswordLinkExpirationDate(new \DateTime('+30 minutes'));
        $user->addOption(User::USER_CHANGING_PASSWORD);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function generateNewPassword(User $user, $password)
    {
        $credentials = $this->passwordHandler->generateHashAndSalt($password);

        $user->setPassword($credentials[User::COLUMN_PASSWORD]);
        $user->setSalt($credentials[User::COLUMN_SALT]);
        $user->unsetOption(User::USER_CHANGING_PASSWORD);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}