<?php

namespace App\Services\DataProvider;

use App\Entity\Report;
use App\Repository\ReportRepository;

class ReportDataProvider
{
    /** @var ReportRepository */
    private $reportRepository;

    public function __construct(ReportRepository $reportRepository)
    {
        $this->reportRepository = $reportRepository;
    }

    public function getAllNewReports()
    {
        return $this->reportRepository->findBy(['options' => Report::OPTIONS_NEW], ['timestamp' => 'DESC']);
    }
}