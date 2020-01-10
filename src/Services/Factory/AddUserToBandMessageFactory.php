<?php

namespace App\Services\Factory;

use App\Entity\AddUserToBandMessage;
use App\Repository\AddUserToBandMessageRepository;
use App\Repository\BandRepository;
use App\Repository\UserBandRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class AddUserToBandMessageFactory
{
    /** @var EntityManager */
    private $entityManager;

    /** @var UserBandRepository */
    private $userBandRepository;

    /** @var BandRepository */
    private $bandRepository;

    /** @var AddUserToBandMessageRepository */
    private $addUserToBandMessageRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        UserBandRepository $userBandRepository,
        BandRepository $bandRepository,
        AddUserToBandMessageRepository $addUserToBandMessageRepository
    )
    {
        $this->entityManager = $entityManager;
        $this->userBandRepository = $userBandRepository;
        $this->bandRepository = $bandRepository;
        $this->addUserToBandMessageRepository = $addUserToBandMessageRepository;
    }

    public function createAddUserToBandMessage($bandId, $sender, $text)
    {
        $band = $this->bandRepository->findOneBy(['bandId' => $bandId]);
        $request = $this->addUserToBandMessageRepository->findOneBy(['band' => $band, 'user' => $sender, 'options' => AddUserToBandMessage::OPTION_NEW]);
        if (empty($request)) {
            $addUserToBandRequest = new AddUserToBandMessage();
            $addUserToBandRequest->setUser($sender);
            $addUserToBandRequest->setReason($text);
            $addUserToBandRequest->setBand($band);
            $addUserToBandRequest->setOptions(AddUserToBandMessage::OPTION_NEW);

            $this->entityManager->persist($addUserToBandRequest);
            $this->entityManager->flush($addUserToBandRequest);
        }
    }
}