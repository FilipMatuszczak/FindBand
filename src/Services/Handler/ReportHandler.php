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

        $report->addOption(Report::OPTIONS_CANCELLED);
        $this->entityManager->flush();
    }

    public function banUser($reportId, $userId)
    {
        $report = $this->reportRepository->findOneBy(['reportId' => $reportId]);
        $report->addOption(Report::OPTIONS_USER_BANNED);
        $report->setNotice(null);
        $report->setPost(null);
        $user = $this->userProvider->loadUserById($userId);
        $user->addOption(User::USER_BANNED);
        $report->setUser($user);

        $this->entityManager->flush();
    }

    public function deleteItem($reportId)
    {
        $report = $this->reportRepository->findOneBy(['reportId' => $reportId]);
        $report->addOption(Report::OPTIONS_DELETED);
        $reports = [];
        if ($notice = $report->getNotice()) {
            $reports = $this->reportRepository->findBy(['notice' => $notice]);

            $this->entityManager->remove($notice);
        } else {
            $post = $report->getPost();
            $reports = $this->reportRepository->findBy(['post' => $post]);

            $this->entityManager->remove($post);
        }

        foreach ($reports as $report) {
            $report->addOption(Report::OPTIONS_DELETED);
        }
        $this->entityManager->flush();
    }

    public function unbanUser($userId)
    {
        $user = $this->userProvider->loadUserById($userId);
        $user->unsetOption(User::USER_BANNED);
        $reports = $this->reportRepository->findBy(['user' => $user, 'post' => null, 'notice' => null]);
        foreach ($reports as $report) {
            $report->unsetOption(Report::OPTIONS_USER_BANNED);
            $report->addOption(Report::OPTIONS_CANCELLED);
        }

        $this->entityManager->flush();
    }

}