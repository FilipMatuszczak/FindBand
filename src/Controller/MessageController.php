<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\BanRepository;
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

    /** @var BanRepository */
    private $banRepository;

    public function __construct(
        MessageFactory $messageFactory,
        MessagesDataProvider $messagesDataProvider,
        UserProvider $userProvider,
        BanRepository $banRepository
    )
    {
        $this->messageFactory = $messageFactory;
        $this->messagesDataProvider = $messagesDataProvider;
        $this->userProvider = $userProvider;
        $this->banRepository = $banRepository;
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
        $subject = $this->userProvider->loadUserById((int) $userId);

        $messages = $this->messagesDataProvider->getMessagesForUser($user, $subject);

        return $this->render('Message.html.twig', ['messages' => $messages, 'user' => $subject]);
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
        $receiver = $this->userProvider->loadUserById($receiverId);

        $ban = $this->banRepository->findOneBy(['subject' => $user, 'user' => $receiverId]);

        if ($user->getUserId() == $receiverId) {
            throw new BadRequestHttpException('Cannot send message to yourself');
        }

        if ($ban) {
            $session = $this->container->get('session');
            $session->getFlashBag()->add('notice', 'Użytkownik zablokował twoje konto, nie możesz wysyłać mu żadnych wiadomości');
        } else {
            $this->messageFactory->createMessage($user, $receiverId, $request->get('text'));
        }
    }
}