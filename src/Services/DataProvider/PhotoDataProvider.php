<?php

namespace App\Services\DataProvider;

use App\Entity\User;
use App\Repository\BandRepository;
use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Security;

class PhotoDataProvider
{
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
        if ($userPhoto = $this->getEncodedPhoto($photo)) {
            return $userPhoto;
        }

        return $this->getEncodedPhoto(fopen(getcwd() . '\web\images\default_user.jpg', "r"));
    }

    public function getPhotoByUsername($username)
    {
        $photo = $this->userRepository->findOneBy([User::COLUMN_USERNAME => $username])->getPhoto();
        if ($userPhoto = $this->getEncodedPhoto($photo)) {
            return $userPhoto;
        }

        return $this->getEncodedPhoto(fopen(getcwd() . '\web\images\default_user.jpg', "r"));
    }

    public function getPhotoById($userId)
    {
        $photo = $this->userRepository->findOneBy([User::COLUMN_USER_ID => $userId])->getPhoto();
        if ($userPhoto = $this->getEncodedPhoto($photo)) {
            return $userPhoto;
        }

        return $this->getEncodedPhoto(fopen(getcwd() . '\web\images\default_user.jpg', "r"));
    }

    public function getBandPhotoById($bandId)
    {
        $photo = $this->bandRepository->findOneBy(['bandId' => $bandId])->getPhoto();
        if ($bandPhoto = $this->getEncodedPhoto($photo)) {
            return $bandPhoto;
        }

        return $this->getEncodedPhoto(fopen(getcwd() . '\web\images\default_band.jpg', "r"));
    }


    private function getEncodedPhoto($photo)
    {
        if ($photo) {
            rewind($photo);
            $photo = stream_get_contents($photo);

            return base64_encode($photo);
        }

        return null;
    }
}