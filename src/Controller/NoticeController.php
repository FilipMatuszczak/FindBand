<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\Factory\NoticeFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class NoticeController extends AbstractController
{
    /** @var NoticeFactory */
    private $noticeFactory;

    public function __construct(NoticeFactory $noticeFactory)
    {
        $this->noticeFactory = $noticeFactory;
    }

    public function noticeCreateIndexAction()
    {
        return $this->render('new-notice.html.twig');
    }

    public function indexAction()
    {
        return $this->render('search_notices.html.twig');
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
}