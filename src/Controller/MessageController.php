<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\BanRepository;
use App\Security\UserProvider;
use App\Services\DataProvider\MessagesDataProvider;
use App\Services\Factory\AddUserToBandMessageFactory;
use App\Services\Factory\MessageFactory;
use App\Services\Handler\AddUserToBandHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class MessageController extends AbstractController
{
    /** @var MessageFactory */
    private $messageFactory;

    /** @var MessagesDataProvider */
    private $messagesDataProvider;

    /** @var UserProvider */
    private $userProvider;

    /** @var BanRepository */
    private $banRepository;

    /** @var AddUserToBandMessageFactory */
    private $addUsetToBandMessageFactory;

    /** @var AddUserToBandHandler */
    private $addUserToBandHandler;

    public function __construct(
        MessageFactory $messageFactory,
        MessagesDataProvider $messagesDataProvider,
        UserProvider $userProvider,
        BanRepository $banRepository,
        AddUserToBandMessageFactory $addUserToBandMessageFactory,
        AddUserToBandHandler $addUserToBandHandler
    )
    {
        $this->messageFactory = $messageFactory;
        $this->messagesDataProvider = $messagesDataProvider;
        $this->userProvider = $userProvider;
        $this->banRepository = $banRepository;
        $this->addUsetToBandMessageFactory = $addUserToBandMessageFactory;
        $this->addUserToBandHandler = $addUserToBandHandler;
    }

    public function allMessagesIndexAction()
    {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $shortcuts = $this->messagesDataProvider->getUsersMessagesShortcuts($user);
        $addUserToBandMessages = $this->messagesDataProvider->getAddUserToBandMessagesForUser($user);

        return $this->render('AllMessages.html.twig', ['shortcuts' => $shortcuts, 'addUserToBandMessages' => $addUserToBandMessages]);
    }

    public function messagesIndexAction($userId)
    {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $subject = $this->userProvider->loadUserById((int) $userId);
        if (!$subject) {
            throw new NotFoundHttpException();
        }
        $messages = $this->messagesDataProvider->getMessagesForUser($user, $subject);

        return $this->render('Message.html.twig', ['messages' => $messages, 'user' => $subject]);
    }

    public function sendMessageToUserAction(Request $request)
    {
        if ($this->sendMessage($request)) {
            return new Response('Message was successfully sent');
        }

        return new Response('Blocked');
    }

    public function sendMessageAndRedirectAction(Request $request)
    {
        $this->sendMessage($request);
        $receiverId = $request->get('receiverId');

        return $this->redirectToRoute('messagesIndexAction', ['userId' => $receiverId]);
    }

    public function sendAddUserToBandMessage(Request $request)
    {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $bandId = $request->get('bandId');
        $text = $request->get('text');

        $this->addUsetToBandMessageFactory->createAddUserToBandMessage($bandId, $user, $text);

        return new Response('Success');
    }

    public function decideAddUserToBandMessage(Request $request)
    {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $this->addUserToBandHandler->decideAddUserToBand($request->get('messageId'), $request->get('decision'));

        return $this->redirectToRoute('allMessagesIndexAction');
    }

    /**
     * @param Request $request
     *
     * @return bool
     */
    private function sendMessage(Request $request)
    {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $receiverId = $request->get('receiverId');
        $receiver = $this->userProvider->loadUserById($receiverId);

        $ban = $this->banRepository->findOneBy(['subject' => $user, 'user' => $receiverId]);

        if ($user->getUserId() == $receiverId) {
            throw new BadRequestHttpException('Cannot send message to yourself');
        }

        if ($ban) {
            $session = $this->container->get('session');
            $session->getFlashBag()->add('notice', 'Użytkownik zablokował twoje konto, nie możesz wysyłać mu żadnych wiadomości');

            return false;
        } else {
            $this->messageFactory->createMessage($user, $receiverId, $request->get('text'));

            return true;
        }
    }
}