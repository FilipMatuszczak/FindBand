<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\Factory\MessageFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class MessageController extends AbstractController
{
    /** @var MessageFactory */
    private $messageFactory;

    public function __construct(MessageFactory $messageFactory)
    {
        $this->messageFactory = $messageFactory;
    }

    public function sendMessageToUserAction(Request $request)
    {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $receiverId = $request->get('receiverId');
        if ($user->getUserId() == $receiverId) {
            throw new BadRequestHttpException('Cannot send message to yourself');
        }

        $this->messageFactory->createMessage($user, $receiverId, $request->get('text'));

        return new JsonResponse('Message was successfully sent');
    }
}