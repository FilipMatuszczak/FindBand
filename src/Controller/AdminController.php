<?php

namespace App\Controller;

use App\Services\DataProvider\ReportDataProvider;
use App\Services\Factory\MessageFactory;
use App\Services\Handler\ReportHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminController extends AbstractController
{
    /** @var ReportDataProvider */
    private $reportDataProvider;

    /** @var ReportHandler */
    private $reportHandler;

    /** @var MessageFactory */
    private $messageFactory;

    public function __construct(ReportDataProvider $reportDataProvider, ReportHandler $reportHandler, MessageFactory $messageFactory)
    {
        $this->reportDataProvider = $reportDataProvider;
        $this->reportHandler = $reportHandler;
        $this->messageFactory = $messageFactory;
    }

    public function adminReportsIndexAction()
    {
        $reports = $this->reportDataProvider->getAllNewReports();

        return $this->render('admin-reports.html.twig', ['reports' => $reports]);
    }

    public function adminSendNewsletterAction(Request $request)
    {
        $this->messageFactory->createMessagesForAllNewsletterUsers($request->get('text'));

        return $this->redirectToRoute('adminReportsIndexAction');
    }

    public function cancelReportAction(Request $request)
    {
        $this->reportHandler->cancelReport($request->get('reportId'));

        return new Response('Report cancelled');
    }

    public function blockUserAction(Request $request)
    {
        $this->reportHandler->banUser($request->get('reportId'), $request->get('userId'));

        return new Response('User banned');
    }

    public function deleteItemAction(Request $request)
    {
        $reportId = $request->get('reportId');

        $this->reportHandler->deleteItem($reportId);
        $this->reportHandler->banUser($request->get('reportId'), $request->get('userId'));

        return $this->redirectToRoute('adminReportsIndexAction');
    }

    public function adminBansIndexAction()
    {
        return $this->render('admin-history.html.twig', ['bans' => $this->reportDataProvider->getAllBannedUsers()]);
    }

    public function unbanUserAction(Request $request)
    {
        $this->reportHandler->unbanUser($request->get('userId'));

        return $this->redirectToRoute('adminBanUserAction');
    }
}