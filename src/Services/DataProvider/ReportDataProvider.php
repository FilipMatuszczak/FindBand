<?php

namespace App\Services\DataProvider;

use App\Entity\Report;
use App\Repository\ReportRepository;
use App\Repository\UserRepository;

class ReportDataProvider
{
    /** @var ReportRepository */
    private $reportRepository;

    /** @var UserRepository */
    private $userRepository;

    public function __construct(ReportRepository $reportRepository, UserRepository $userRepository)
    {
        $this->reportRepository = $reportRepository;
        $this->userRepository = $userRepository;
    }

    public function getAllNewReports()
    {
        return $this->reportRepository->findBy(['options' => Report::OPTIONS_NEW], ['timestamp' => 'DESC']);
    }

    public function getAllBannedUsers()
    {
        $bannedUsers = $this->userRepository->fetchAllBannedUsers();

        return $this->reportRepository->fetchBansByUsers($bannedUsers);
    }
}