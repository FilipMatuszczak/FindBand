<?php


namespace App\Services\DataProvider;


use App\Repository\BandRepository;
use App\Repository\MusicGenreRepository;
use App\Repository\UserRepository;

class BandsPageDataProvider
{

    const BANDS_PER_PAGE = 1;

    /** @var UserRepository */
    private $userRepository;

    /** @var MusicGenreRepository */
    private $musicGenreRepository;

    /** @var BandRepository */
    private $bandRepository;

    public function __construct(UserRepository $userRepository, MusicGenreRepository $musicGenreRepository, BandRepository $bandRepository)
    {
        $this->musicGenreRepository = $musicGenreRepository;
        $this->userRepository = $userRepository;
        $this->bandRepository = $bandRepository;
    }

    public function getBandRecordsByFilters($page, $memberName, $sorting, $musicGenreName, $title)
    {
        $from = ($page - 1) * self::BANDS_PER_PAGE;
        $max = self::BANDS_PER_PAGE + 1;

        if (!empty($musicGenreName)) {
            $musicGenre = $this->musicGenreRepository->findOneBy(['name' => $musicGenreName]);
            if (empty($musicGenre)) {
                return [];
            }
        } else {
            $musicGenre = null;
        }

        if (!empty($memberName)) {
            $member = $this->userRepository->findOneBy(['username' => $memberName]);
            if (empty($member)) {
                return [];
            }
        } else {
            $member = null;
        }

        return $this->bandRepository->fetchBandsByFilters($from, $max, $this->convertToSqlSorting($sorting), $title, $musicGenre, $member);
    }

    private function convertToSqlSorting($sorting)
    {
        switch ($sorting) {
            case 'A-Z':
                return ['dir' => 'ASC', 'sortKey' => 'title'];
            case 'Z-A':
                return ['dir' => 'DESC', 'sortKey' => 'title'];
            case 'Najnowsze':
                return ['dir' => 'DESC', 'sortKey' => 'createdDate'];
            case 'Najstarsze':
                return ['dir' => 'ASC', 'sortKey' => 'createdDate'];
        }
    }
}