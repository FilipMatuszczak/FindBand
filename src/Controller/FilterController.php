<?php


namespace App\Controller;

use App\Repository\InstrumentRepository;
use App\Repository\MusicGenreReposiory;
use Symfony\Component\HttpFoundation\JsonResponse;

/** */
class FilterController
{
    /** @var InstrumentRepository */
    private $instrumentRepository;

    /** @var MusicGenreReposiory */
    private $musicGenresRepository;

    public function __construct(InstrumentRepository $instrumentRepository, MusicGenreReposiory $musicGenreReposiory)
    {
        $this->instrumentRepository = $instrumentRepository;
        $this->musicGenresRepository = $musicGenreReposiory;
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
        $intsruments = $this->instrumentRepository->findOneBy(['name' => $name]);

        if ($intsruments)
        {
            return new JsonResponse([true]);
        }

        return new JsonResponse([false]);
    }
}