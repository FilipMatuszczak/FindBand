<?php

namespace App\Services\DataProvider;

use App\Entity\Message;
use App\Entity\Dto\MessageShorcutDto;
use App\Entity\User;
use App\Repository\MessageRepository;

class MessagesDataProvider
{
    /** @var MessageRepository */
    private $messagesRepository;

    public function __construct(MessageRepository $messageRepository)
    {
        $this->messagesRepository = $messageRepository;
    }

    public function getUsersMessagesShortcuts(User $user)
    {
        $sendMessages = $this->messagesRepository->getAllSendersByReceiver($user);
        $receiveMessages = $this->messagesRepository->getAllReceiversBySender($user);
        $users = array_unique(array_merge($sendMessages,$receiveMessages), SORT_REGULAR);

        /** @var Message[] $messages */
        $messages = [];
        foreach ($users as $messageUser) {
            $tmp = $this->getMessagesForUser($user, $messageUser);
            $messages[] = end($tmp);
        }

        $shortcuts = [];
        foreach ($messages as $message) {
            if ($message->getSender() === $user) {
                $shortcuts[] = $this->createSenderShortcutFromMessage($message);
            } else {
                $shortcuts[] = $this->createReceiverShortcutFromMessage($message);
            }
        }

        return $shortcuts;
    }

    public function getMessagesForUser(User $currentUser, User $user)
    {
        $messagesSender = $this->messagesRepository->findBy(['sender' => $currentUser, 'receiver' => $user]);
        $messagesReceiver = $this->messagesRepository->findBy(['receiver' => $currentUser, 'sender' => $user]);

        $messages = array_merge($messagesSender, $messagesReceiver);

        usort($messages, function(Message $a, Message $b) {return ($a->getTimestamp() > $b->getTimestamp());});

        return $messages;
    }

    private function createSenderShortcutFromMessage(Message $message)
    {
        $dto = new MessageShorcutDto();

        $dto->setDate($message->getTimestamp()->format('Y-m-d H:i:s'));
        $dto->setLastMessageText($message->getText());
        $dto->setLastMessageUsername($message->getSender()->getUsername());
        $dto->setUsername($message->getReceiver()->getUsername());
        $dto->setUserId($message->getReceiver()->getUserId());

        return $dto;
    }

    private function createReceiverShortcutFromMessage(Message $message)
    {
        $dto = new MessageShorcutDto();

        $dto->setDate($message->getTimestamp()->format('Y-m-d H:i:s'));
        $dto->setLastMessageText($message->getText());
        $dto->setLastMessageUsername($message->getSender()->getUsername());
        $dto->setUsername($message->getSender()->getUsername());
        $dto->setUserId($message->getSender()->getUserId());

        return $dto;
    }
}