<?php

namespace App\Services\DataProvider;

use App\Repository\CityRepository;
use App\Repository\InstrumentRepository;
use App\Repository\MusicGenreRepository;
use App\Repository\UserRepository;

class UserPageDataProvider
{
    const USERS_PER_PAGE = 10;

    /** @var UserRepository */
    private $userRepository;

    /** @var MusicGenreRepository */
    private $musicGenresRepository;

    /** @var CityRepository */
    private $cityRepository;

    /** @var InstrumentRepository */
    private $instrumentRepository;

    public function __construct(
        UserRepository $userRepository,
        MusicGenreRepository $musicGenresRepository,
        CityRepository $cityRepository,
        InstrumentRepository $instrumentRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->musicGenresRepository = $musicGenresRepository;
        $this->cityRepository = $cityRepository;
        $this->instrumentRepository = $instrumentRepository;
    }

    public function getUserRecordsByFilters($page, $firstname, $lastname, $sorting, $instrumentName, $musicGenreName, $cityName)
    {
        $from = ($page - 1) * self::USERS_PER_PAGE;
        $max = self::USERS_PER_PAGE + 1;

        if (!empty($instrumentName)) {
            $instrument = $this->instrumentRepository->findOneBy(['name' => $instrumentName]);
            if (empty($instrument)) {
                return [];
            }
        } else {
            $instrument = null;
        }

        if (!empty($cityName)) {
            $city = $this->cityRepository->findOneBy(['name' => $cityName]);
            if (empty($city)) {
                return [];
            }
        } else {
            $city = null;
        }

        if (!empty($musicGenreName)) {
            $musicGenre = $this->musicGenresRepository->findOneBy(['name' => $musicGenreName]);
            if (empty($musicGenre)) {
                return [];
            }
        } else {
            $musicGenre = null;
        }

        return $this->userRepository->fetchUsersByFilters(
            $from,
            $max,
            $this->convertToSqlSorting($sorting),
            $firstname,
            $lastname,
            $instrument,
            $city,
            $musicGenre
        );
    }

    private function convertToSqlSorting($sorting)
    {
        switch ($sorting) {
            case 'Z-A':
                return ['dir' => 'DESC', 'sortKey' => 'firstname'];
            case 'Najstarsi':
                return ['dir' => 'ASC', 'sortKey' => 'dateOfBirth'];
            case 'Najmlodsi':
                return ['dir' => 'DESC', 'sortKey' => 'dateOfBirth'];
            default:
                return ['dir' => 'ASC', 'sortKey' => 'firstname'];
        }
    }
}