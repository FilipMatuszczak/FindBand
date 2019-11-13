<?php

namespace App\Services\Handler;

use App\Entity\User;
use App\Repository\InstrumentRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class InstrumentHandler
{
    /** @var EntityManager */
    private $entityManager;

    /** @var InstrumentRepository */
    private $instrumentRepository;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager, InstrumentRepository $instrumentRepository)
    {
        $this->entityManager = $entityManager;
        $this->instrumentRepository = $instrumentRepository;
    }

    public function addInstrumentsToUser(User $user, $instrumentNames)
    {
        $instruments = $this->instrumentRepository->findBy(['name' => $instrumentNames]);

        foreach ($instruments as $instrument) {
            $user->addInstrument($instrument);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }
}