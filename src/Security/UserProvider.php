<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface
{
    /** @var UserRepository */
    private $userRepository;
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * @return User
     *
     * @throws UsernameNotFoundException if the user is not found
     */
    public function loadUserByUsername($username)
    {
        return $this->userRepository->findOneBy([User::COLUMN_USERNAME => $username]);
    }
    public function loadUserById($userId)
    {
        return $this->userRepository->findOneBy([User::COLUMN_USER_ID => $userId]);
    }

    /**
     * @return UserInterface
     */
    public function refreshUser(UserInterface $user)
    {
        $currUser = $this->userRepository->findOneBy([User::COLUMN_USERNAME => $user->getUsername()]);
        $user = $currUser;
        return $user;
    }

    /** */
    public function supportsClass($class)
    {
        return User::class === $class;
    }
}