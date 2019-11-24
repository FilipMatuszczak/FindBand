<?php

namespace App\Services\Factory;

use App\Entity\Band;
use App\Entity\User;
use App\Repository\MusicGenreRepository;
use App\Repository\UserBandRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\File;

class BandFactory
{
    /** @var MusicGenreRepository */
    private $musicGenreRepository;

    /** @var EntityManager */
    private $entityManager;

    /** @var UserBandRepository */
    private $userBandRepository;

    public function __construct(
        MusicGenreRepository $musicGenreRepository,
        EntityManagerInterface $entityManagerInterface,
        UserBandRepository $userBandRepository
    )
    {
        $this->musicGenreRepository = $musicGenreRepository;
        $this->entityManager = $entityManagerInterface;
        $this->userBandRepository = $userBandRepository;
    }

    public function createBand($bandName, $description, ?File $photo, array $genreNames, User $author)
    {
        $band = new Band();

        $band->setTitle($bandName)->setDescription($description)->setCreatedDate(new \DateTime('now'));

        if ($photo) {
            $strm = fopen($photo->getRealPath(), 'rb');
            $band->setPhoto(stream_get_contents($strm));
        }

        $band->addUser($author);

        foreach ($genreNames as $musicGenre) {
            $band->addMusicGenre($this->musicGenreRepository->findOneBy(['name' => $musicGenre]));
        }

        $this->entityManager->persist($band);
        $this->entityManager->flush();

        $this->userBandRepository->setUserAsAuthor($author->getUserId(), $band->getBandId());
    }
}