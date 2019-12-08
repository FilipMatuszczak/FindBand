<?php


namespace App\Services\DataProvider;


use App\Repository\BandRepository;
use App\Repository\InstrumentRepository;
use App\Repository\NoticeRepository;
use App\Repository\UserRepository;

class NoticesPageDataProvider
{
    const NOTICES_PER_PAGE = 10;

    /** @var UserRepository */
    private $userRepository;

    /** @var InstrumentRepository */
    private $instrumentRepository;

    /** @var BandRepository */
    private $bandRepository;

    /** @var NoticeRepository */
    private $noticesRepository;

    public function __construct(
        UserRepository $userRepository,
        InstrumentRepository $instrumentRepository,
        BandRepository $bandRepository,
        NoticeRepository $noticeRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->instrumentRepository = $instrumentRepository;
        $this->bandRepository = $bandRepository;
        $this->noticesRepository = $noticeRepository;
    }

    public function getNoticesRecordsByFilters($page, $authorName, $sorting, $instrumentName, $bandName)
    {
        $from = ($page - 1) * self::NOTICES_PER_PAGE;
        $max = self::NOTICES_PER_PAGE + 1;

        if (!empty($instrumentName)) {
            $instrument = $this->instrumentRepository->findOneBy(['name' => $instrumentName]);
            if (empty($instrument)) {
                return [];
            }
        } else {
            $instrument = null;
        }

        if (!empty($authorName)) {
            $author = $this->userRepository->findOneBy(['username' => $authorName]);
            if (empty($author)) {
                return [];
            }
        } else {
            $author = null;
        }

        if (!empty($bandName)) {
            $band = $this->bandRepository->findOneBy(['title' => $bandName]);
            if (empty($band)) {
                return [];
            }
        } else {
            $band = null;
        }

        return $this->noticesRepository->fetchNoticesByFilters($from, $max, $this->convertToSqlSorting($sorting), $instrument, $author, $band);
    }

    private function convertToSqlSorting($sorting)
    {
        switch ($sorting) {
            case 'A-Z':
                return ['dir' => 'ASC', 'sortKey' => 'title'];
            case 'Z-A':
                return ['dir' => 'DESC', 'sortKey' => 'title'];
            case 'Najnowsze':
                return ['dir' => 'DESC', 'sortKey' => 'timestamp'];
            case 'Najstarsze':
                return ['dir' => 'ASC', 'sortKey' => 'timestamp'];
        }
    }
}