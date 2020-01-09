<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\UserProvider;
use App\Services\DataProvider\MessagesDataProvider;
use App\Services\Factory\MessageFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class MessageController extends AbstractController
{
    /** @var MessageFactory */
    private $messageFactory;

    /** @var MessagesDataProvider */
    private $messagesDataProvider;

    /** @var UserProvider */
    private $userProvider;

    public function __construct(MessageFactory $messageFactory, MessagesDataProvider $messagesDataProvider, UserProvider $userProvider)
    {
        $this->messageFactory = $messageFactory;
        $this->messagesDataProvider = $messagesDataProvider;
        $this->userProvider = $userProvider;
    }

    public function allMessagesIndexAction()
    {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $shortcuts = $this->messagesDataProvider->getUsersMessagesShortcuts($user);

        return $this->render('AllMessages.html.twig', ['shortcuts' => $shortcuts]);
    }

    public function messagesIndexAction($userId)
    {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $messages = $this->messagesDataProvider->getMessagesForUser($user, $this->userProvider->loadUserById((int) $userId));

        return $this->render('Message.html.twig', ['messages' => $messages]);
    }

    public function sendMessageToUserAction(Request $request)
    {
        $this->sendMessage($request);

        return new JsonResponse('Message was successfully sent');
    }

    public function sendMessageAndRedirectAction(Request $request)
    {
        $this->sendMessage($request);
        $receiverId = $request->get('receiverId');

        return $this->redirectToRoute('messagesIndexAction', ['userId' => $receiverId]);
    }

    /**
     * @param Request $request
     */
    private function sendMessage(Request $request): void
    {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $receiverId = $request->get('receiverId');
        if ($user->getUserId() == $receiverId) {
            throw new BadRequestHttpException('Cannot send message to yourself');
        }

        $this->messageFactory->createMessage($user, $receiverId, $request->get('text'));
    }
}