<?php


namespace App\Controller;

use App\Repository\InstrumentRepository;
use App\Repository\MusicGenreRepository;
use App\Repository\UserDbalRepository;
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

    /** @var UserDbalRepository */
    private $userDbalRepository;

    public function __construct(InstrumentRepository $instrumentRepository, MusicGenreRepository $musicGenreReposior, UserProvider $userProvider, UserDbalRepository $userDbalRepository)
    {
        $this->instrumentRepository = $instrumentRepository;
        $this->musicGenresRepository = $musicGenreReposior;
        $this->userProvider = $userProvider;
        $this->userDbalRepository = $userDbalRepository;
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
        //$username = $this->userProvider->loadUserByUsername($username);

        $result = $this->userDbalRepository->userExists($username);

        if ($result)
        {
            return new JsonResponse($result);
        }

        return new JsonResponse($result);
    }
}