<?php

namespace App\Services\Factory;

use App\Entity\User;
use App\Services\Handler\PasswordHandler;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class UserFactory
{
    /** @var EntityManager */
    private $entityManager;

    /** @var PasswordHandler */
    private $passwordHandler;

    /**
     * @param EntityManagerInterface $entityManager
     * @param PasswordHandler        $passwordHandler
     */
    public function __construct(EntityManagerInterface $entityManager, PasswordHandler $passwordHandler)
    {
        $this->entityManager = $entityManager;
        $this->passwordHandler = $passwordHandler;
    }

    /**
     * @param string $username
     * @param string $firstname
     * @param string $lastname
     * @param string $email
     * @param string $password
     */
    public function createUser($username, $firstname, $lastname, $email, $password)
    {
        $user = new User();

        $user->setUsername($username);
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setEmail($email);

        $credentials = $this->passwordHandler->generateHashAndSalt($password);

        $user->setPassword($credentials[User::COLUMN_PASSWORD]);
        $user->setSalt($credentials[User::COLUMN_SALT]);

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}