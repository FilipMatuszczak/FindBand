<?php

namespace App\Services\DataProvider;

use App\Entity\Message;
use App\Entity\Dto\MessageShorcutDto;
use App\Entity\User;
use App\Repository\AddUserToBandMessageRepository;
use App\Repository\BandRepository;
use App\Repository\MessageRepository;
use App\Repository\UserBandRepository;

class MessagesDataProvider
{
    /** @var MessageRepository */
    private $messagesRepository;

    /** @var AddUserToBandMessageRepository */
    private $addUserToBandMessageRepository;

    /** @var UserBandRepository */
    private $userBandRepository;

    /** @var BandRepository */
    private $bandRepository;

    public function __construct(
        MessageRepository $messageRepository,
        AddUserToBandMessageRepository $addUserToBandMessageRepository,
        UserBandRepository $userBandRepository,
        BandRepository $bandRepository
    )
    {
        $this->messagesRepository = $messageRepository;
        $this->addUserToBandMessageRepository = $addUserToBandMessageRepository;
        $this->userBandRepository = $userBandRepository;
        $this->bandRepository = $bandRepository;
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
        usort($shortcuts, function(MessageShorcutDto $a, MessageShorcutDto $b) {return ($a->getDate() < $b->getDate());});

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

    public function getAddUserToBandMessagesForUser(User $user)
    {
        $bandIds = $this->userBandRepository->getBandsByAuthorId($user->getUserId());
        $bands = $this->bandRepository->fetchBandsById($bandIds);

        return $this->addUserToBandMessageRepository->fetchNewMessagesByBands($bands);
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