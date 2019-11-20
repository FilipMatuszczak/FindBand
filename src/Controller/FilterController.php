<?php


namespace App\Controller;

use App\Repository\InstrumentRepository;
use App\Repository\MusicGenreRepository;
use App\Security\UserProvider;
use Symfony\Component\HttpFoundation\JsonResponse;

/** */
class FilterController
{
    /** @var InstrumentRepository */
    private $instrumentRepository;

    /** @var MusicGenreRepository */
    private $musicGenresRepository;

    /** @var UserProvider */
    private $userProvider;

    public function __construct(InstrumentRepository $instrumentRepository, MusicGenreRepository $musicGenreReposior, UserProvider $userProvider)
    {
        $this->instrumentRepository = $instrumentRepository;
        $this->musicGenresRepository = $musicGenreReposior;
        $this->userProvider = $userProvider;
    }

    public function filterInstrumentsAction($limit, $prefix)
    {
        $names = $this->instrumentRepository->fetchSimilarNamesByPrefix($prefix, $limit);

        return new JsonResponse([
            'names' => $names,
        ]);
    }

    public function filterMusicGenresAction($limit, $prefix)
    {
        $names = $this->musicGenresRepository->fetchSimilarNamesByPrefix($prefix, $limit);

        return new JsonResponse([
            'names' => $names,
        ]);
    }

    public function instrumentExistsAction($name)
    {
        $instruments = $this->instrumentRepository->findOneBy(['name' => $name]);

        if ($instruments)
        {
            return new JsonResponse([true]);
        }

        return new JsonResponse([false]);
    }

    public function musicGenreExistsAction($name)
    {
        $musicGenre = $this->musicGenresRepository->findOneBy(['name' => $name]);

        if ($musicGenre)
        {
            return new JsonResponse([true]);
        }

        return new JsonResponse([false]);
    }

    public function usernameExistsAction($username)
    {
        $username = $this->userProvider->loadUserByUsername($username);

        if ($username)
        {
            return new JsonResponse([true]);
        }

        return new JsonResponse([false]);
    }
}