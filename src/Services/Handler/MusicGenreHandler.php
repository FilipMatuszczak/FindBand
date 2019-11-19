<?php

namespace App\Services\Handler;

use App\Entity\User;
use App\Repository\MusicGenreRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class MusicGenreHandler
{
    /** @var EntityManager */
    private $entityManager;

    /** @var MusicGenreRepository */
    private $musicGenreRepository;

    /**
     * @param EntityManagerInterface $entityManager
     * @param MusicGenreRepository $musicGenreRepository
     */
    public function __construct(EntityManagerInterface $entityManager, MusicGenreRepository $musicGenreRepository)
    {
        $this->entityManager = $entityManager;
        $this->musicGenreRepository = $musicGenreRepository;
    }

    public function addMusicGenresToUser(User $user, $musicGenresData)
    {
        $musicGenres = $this->musicGenreRepository->findBy(['name' => $this->decorateRawData($musicGenresData)]);

        foreach ($user->getMusicGenres() as $musicGenre) {
            $user->removeMusicGenre($musicGenre);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        foreach ($musicGenres as $musicGenre) {
            $user->addMusicGenre($musicGenre);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $musicGenres;
    }

    private function decorateRawData($musicGenresData)
    {
        $musicGenresNames = [];
        foreach ($musicGenresData as $musicGenresDatum) {
            $musicGenresNames[] = $musicGenresDatum['value'];
        }

        return $musicGenresNames;
    }
}