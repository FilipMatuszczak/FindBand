<?php

namespace App\Services\DataProvider;

use App\Entity\User;
use App\Repository\BandRepository;
use App\Repository\NoticeRepository;

class MainPageItemsDataProvider
{
    /** @var NoticeRepository */
    private $noticeRepository;

    /** @var BandRepository */
    private $bandRepository;

    public function __construct(NoticeRepository $noticeRepository, BandRepository $bandRepository)
    {
        $this->noticeRepository = $noticeRepository;
        $this->bandRepository = $bandRepository;
    }

    public function getNoticesForUser(User $user)
    {
        return $this->noticeRepository->fetchNoticesByInstrumentsExcludingUser($user->getInstrument(), $user);
    }

    public function getBandsForUser(User $user)
    {
        return $this->bandRepository->fetchBandsByMusicGenreExcludingUser($user->getMusicGenres(), $user);
    }
}