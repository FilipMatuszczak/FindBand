<?php

namespace App\Services\Handler;

use App\Entity\AddUserToBandMessage;
use App\Repository\AddUserToBandMessageRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class AddUserToBandHandler
{
    /** @var EntityManager */
    private $entityManager;

    /** @var AddUserToBandMessageRepository */
    private $addUserToBandMessageRepository;

    public function __construct(EntityManagerInterface $entityManager, AddUserToBandMessageRepository $addUserToBandMessageRepository)
    {
        $this->entityManager = $entityManager;
        $this->addUserToBandMessageRepository = $addUserToBandMessageRepository;
    }

    public function decideAddUserToBand($addUserToBandMessageId, $decision)
    {
        $message = $this->addUserToBandMessageRepository->findOneBy(['addUserToBandMessageId' => $addUserToBandMessageId]);

        if ($decision === 'accept') {
            $band = $message->getBand();
            $band->addUser($message->getUser());
            $message->setOptions(AddUserToBandMessage::OPTION_ACCEPTED);
        } else {
            $message->setOptions(AddUserToBandMessage::OPTION_DECLINED);
        }

        $this->entityManager->flush();
    }
}