<?php


namespace App\Services\Handler;


use App\Entity\User;
use App\Repository\CityRepository;
use App\Security\UserProvider;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File;

class UserHandler
{
    /** @var EntityManager */
    private $entityManager;

    /** @var UserProvider */
    private $userProvider;

    /** @var PasswordHandler */
    private $passwordHandler;

    /** @var CityRepository */
    private $cityRepository;

    public function __construct(EntityManagerInterface $entityManager, UserProvider $userProvider, PasswordHandler $passwordHandler, CityRepository $cityRepository)
    {
        $this->entityManager = $entityManager;
        $this->userProvider = $userProvider;
        $this->passwordHandler = $passwordHandler;
        $this->cityRepository = $cityRepository;
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

    public function editCommonUserData(User $user, $firstname, $lastname, $dateOfBirth, $info, $newsletter, $photo, $cityName)
    {
        $user->setFirstname($firstname);
        $user->setLastname($lastname);
        $user->setDateOfBirth(new \DateTime($dateOfBirth));
        $user->setInfo($info);
        $newsletter ? $user->addOption(User::USER_NEWSLETTER) : $user->unsetOption(User::USER_NEWSLETTER);

        $fileName = SavePhotoOnSeverHandler::savePhotoOnServer($photo, SavePhotoOnSeverHandler::USER_PROFILE_DIR);
        $user->setPhoto($fileName);
        if ($city = $this->cityRepository->findOneBy(['name' => $cityName])) {
            $user->setCity($city);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}