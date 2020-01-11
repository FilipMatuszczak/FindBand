<?php

namespace App\Services\Handler;

use App\Entity\Report;
use App\Entity\User;
use App\Repository\NoticeRepository;
use App\Repository\PostRepository;
use App\Repository\ReportRepository;
use App\Security\UserProvider;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class ReportHandler
{
    /** @var EntityManager */
    private $entityManager;

    /** @var ReportRepository */
    private $reportRepository;

    /** @var UserProvider */
    private $userProvider;

    /** @var PostRepository */
    private $postRepository;

    /** @var NoticeRepository */
    private $noticeRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        ReportRepository $reportRepository,
        UserProvider $userProvider,
        PostRepository $postRepository,
        NoticeRepository $noticeRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->reportRepository = $reportRepository;
        $this->userProvider = $userProvider;
        $this->postRepository = $postRepository;
        $this->noticeRepository = $noticeRepository;
    }

    public function cancelReport($reportId)
    {
        $report = $this->reportRepository->findOneBy(['reportId' => $reportId]);

        $report->setOptions(Report::OPTIONS_CANCELLED);
        $this->entityManager->flush();
    }

    public function banUser($reportId, $userId)
    {
        $report = $this->reportRepository->findOneBy(['reportId' => $reportId]);
        $report->setOptions(Report::OPTIONS_USER_BANNED);
        $user = $this->userProvider->loadUserById($userId);
        $user->addOption(User::USER_BANNED);

        $this->entityManager->flush();
    }

    public function deleteItem($reportId)
    {
        $report = $this->reportRepository->findOneBy(['reportId' => $reportId]);
        $report->setOptions(Report::OPTIONS_DELETED);
        if ($report->getNotice()) {
            $this->entityManager->remove($report->getNotice());
        } else {
            $this->entityManager->remove($report->getPost());
        }

        $this->entityManager->flush();
    }

}