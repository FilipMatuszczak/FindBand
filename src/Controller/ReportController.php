<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\Factory\ReportFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class ReportController extends AbstractController
{
    /** @var ReportFactory */
    private $reportFactory;

    public function __construct(ReportFactory $reportFactory)
    {
        $this->reportFactory = $reportFactory;
    }

    public function reportNoticeAction(Request $request)
    {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $noticeId = $request->get('noticeId');
        $reason = $request->get('reason');

        $this->reportFactory->createNoticeReport($user, $noticeId, $reason);

        return new JsonResponse('Report saved successfully');
    }

    public function reportPostAction(Request $request)
    {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $postId = $request->get('postId');
        $reason = $request->get('reason');

        $this->reportFactory->createPostReport($user, $postId, $reason);

        return new JsonResponse('Report saved successfully');
    }
}