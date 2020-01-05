<?php

namespace App\Services\Handler;

use App\Repository\BandRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class BandHandler
{
    /** @var BandRepository */
    private $bandRepository;

    /** @var EntityManager */
    private $entityManager;

    /** @var MusicGenreHandler */
    private $musicGenreHandler;

    public function __construct(BandRepository $bandRepository, EntityManagerInterface $entityManager, MusicGenreHandler $musicGenreHandler)
    {
        $this->bandRepository = $bandRepository;
        $this->entityManager = $entityManager;
        $this->musicGenreHandler = $musicGenreHandler;
    }

    public function editBand($bandId, $title, $text, $photo, $musicGenres)
    {
        $band = $this->bandRepository->findOneBy(['bandId' => $bandId]);

        if (!empty($photo)) {
            $fileName = SavePhotoOnSeverHandler::savePhotoOnServer($photo, SavePhotoOnSeverHandler::BAND_PROFILE_DIR);
            $band->setPhoto($fileName);
        }

        $band->setTitle($title)->setDescription($text);

        $this->musicGenreHandler->addMusicGenresToBand($band, $musicGenres);

        $this->entityManager->persist($band);
        $this->entityManager->flush();
    }
}