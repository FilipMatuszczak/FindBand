<?php

namespace App\Services\Factory;

use App\Entity\Report;
use App\Repository\NoticeRepository;
use App\Repository\PostRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class ReportFactory
{
    /** @var EntityManager */
    private $entityManager;

    /** @var UserRepository */
    private $userRepository;

    /** @var NoticeRepository */
    private $noticeRepository;

    /** @var PostRepository */
    private $postRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserRepository $userRepository,
        NoticeRepository $noticeRepository,
        PostRepository $postRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->noticeRepository = $noticeRepository;
        $this->postRepository = $postRepository;
    }

    public function createNoticeReport($user, $noticeId, $reason)
    {
        $notice = $this->noticeRepository->findOneBy(['noticeId' => $noticeId]);

        $report = new Report();
        $report->setTimestamp(new \DateTime());
        $report->setUser($user);
        $report->setNotice($notice);
        $report->setReason($reason);

        $this->entityManager->persist($report);
        $this->entityManager->flush($report);
    }

    public function createPostReport($user, $postId, $reason)
    {
        $post = $this->postRepository->findOneBy(['postId' => $postId]);
        $report = new Report();
        $report->setTimestamp(new \DateTime());
        $report->setUser($user);
        $report->setPost($post);
        $report->setReason($reason);

        $this->entityManager->persist($report);
        $this->entityManager->flush($report);
    }
}