<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\DataProvider\NoticesPageDataProvider;
use App\Services\Factory\NoticeFactory;
use App\Services\Handler\PaginationHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class NoticeController extends AbstractController
{
    /** @var NoticeFactory */
    private $noticeFactory;

    /** @var NoticesPageDataProvider */
    private $noticesPageDataProvider;

    public function __construct(NoticeFactory $noticeFactory, NoticesPageDataProvider $noticesPageDataProvider)
    {
        $this->noticeFactory = $noticeFactory;
        $this->noticesPageDataProvider = $noticesPageDataProvider;
    }

    public function noticeCreateIndexAction()
    {
        return $this->render('new-notice.html.twig');
    }

    public function indexAction(Request $request)
    {
        $page = $request->get('page');
        $nextPage = null;

        if (empty($page)) {
            $page = 1;
        }

        $author = $request->get('author');
        $sorting = $request->get('sorting');

        if (!isset($sorting)) {
            $sorting = 'A-Z';
        }

        $instrumentName = !empty($request->get('instrument')) ? $request->get('instrument') : '';
        $bandName = !empty($request->get('band')) ? $request->get('band') : '';

        $notices = $this->noticesPageDataProvider->getNoticesRecordsByFilters((int)$page, $author, $sorting, $instrumentName, $bandName);
        $nextPageUrl = '';
        $previousPageUrl = '';

        if ($this->isNextPageNeeded($notices))
        {
            $nextPage = $page + 1;
            $nextPageUrl = PaginationHandler::getPageQueryUrlForNotices($bandName, $author, $page+1, $instrumentName, $sorting);
        }
        $previousPageUrl = PaginationHandler::getPageQueryUrlForNotices($bandName, $author, $page-1, $instrumentName, $sorting);

        return $this->render('search_notices.html.twig', [
            'notices' => $notices,
            'page' => $page,
            'nextPage' => $nextPage,
            'nextPageUrl' => $nextPageUrl,
            'previousPageUrl' => $previousPageUrl
        ]);
    }

    public function createNoticeAction(Request $request)
    {
        $title = $request->get('title');
        $details = $request->get('description');
        $instrument = $request->get('instrument');
        $who = $request->get('who');

        $author = $this->get('security.token_storage')->getToken()->getUser();

        /** @var User $author */
        $this->noticeFactory->createNotice($title, $details, $instrument, $author, $who);

        return $this->redirectToRoute('searchNoticesIndexAction', ['author' => $author->getUsername()]);
    }

    private function isNextPageNeeded(&$notices)
    {
        if (sizeof($notices) == NoticesPageDataProvider::NOTICES_PER_PAGE + 1)
        {
            array_pop($notices);

            return true;
        }

        return false;
    }
}