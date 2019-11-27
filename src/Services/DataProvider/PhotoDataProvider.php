<?php

namespace App\Services\DataProvider;

use App\Entity\User;
use App\Repository\BandRepository;
use App\Repository\UserRepository;
use App\Services\Handler\SavePhotoOnSeverHandler;
use Symfony\Component\Security\Core\Security;

class PhotoDataProvider
{
    const DEFAULT_USER_PHOTO =  '/web/images/default_user.jpg';
    const DEFAULT_BAND_PHOTO =  '/web/images/default_band.jpg';

    /** @var Security */
    private $security;

    /** @var UserRepository */
    private $userRepository;

    /** @var BandRepository */
    private $bandRepository;

    public function __construct(
        Security $security,
        UserRepository $userRepository,
        BandRepository $bandRepository
    )
    {
        $this->security = $security;
        $this->userRepository = $userRepository;
        $this->bandRepository = $bandRepository;
    }

    public function getCurrentUserPhotoData()
    {
        $username = $this->security->getUser()->getUsername();
        $photo = $this->userRepository->findOneBy([User::COLUMN_USERNAME => $username])->getPhoto();

        if ($photo) {
            return SavePhotoOnSeverHandler::UPLOAD_DIRECTORY . SavePhotoOnSeverHandler::USER_PROFILE_DIR . $photo;
        }

        return self::DEFAULT_USER_PHOTO;
    }

    public function getPhotoByUsername($username)
    {
        $photo = $this->userRepository->findOneBy([User::COLUMN_USERNAME => $username])->getPhoto();
        if ($photo) {
            return SavePhotoOnSeverHandler::UPLOAD_DIRECTORY . SavePhotoOnSeverHandler::USER_PROFILE_DIR . $photo;
        }

        return self::DEFAULT_USER_PHOTO;
    }

    public function getPhotoById($userId)
    {
        $photo = $this->userRepository->findOneBy([User::COLUMN_USER_ID => $userId])->getPhoto();
        if ($photo) {
            return SavePhotoOnSeverHandler::UPLOAD_DIRECTORY . SavePhotoOnSeverHandler::USER_PROFILE_DIR . $photo;
        }

        return self::DEFAULT_USER_PHOTO;
    }

    public function getBandPhotoById($bandId)
    {
        $bandPhoto = $this->bandRepository->findOneBy(['bandId' => $bandId])->getPhoto();
        if ($bandPhoto) {
            return SavePhotoOnSeverHandler::UPLOAD_DIRECTORY . SavePhotoOnSeverHandler::BAND_PROFILE_DIR . $bandPhoto;
        }

        return self::DEFAULT_BAND_PHOTO;
    }
}