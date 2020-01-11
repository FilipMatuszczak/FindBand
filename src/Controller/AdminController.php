<?php

namespace App\Controller;

use App\Services\DataProvider\ReportDataProvider;
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

    public function __construct(ReportDataProvider $reportDataProvider, ReportHandler $reportHandler)
    {
        $this->reportDataProvider = $reportDataProvider;
        $this->reportHandler = $reportHandler;
    }

    public function adminReportsIndexAction()
    {
        $reports = $this->reportDataProvider->getAllNewReports();

        return $this->render('admin-reports.html.twig', ['reports' => $reports]);
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
        $banUser = $request->get('banUser');

        $this->reportHandler->deleteItem($reportId);
        $request->setMethod('PATCH');
        $this->reportHandler->banUser($request->get('reportId'), $request->get('userId'));

        return $this->redirectToRoute('adminReportsIndexAction');
    }

    public function adminBansIndexAction()
    {
        return $this->render('admin-history.html.twig');
    }
}