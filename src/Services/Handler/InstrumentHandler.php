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
     * @param InstrumentRepository $instrumentRepository
     */
    public function __construct(EntityManagerInterface $entityManager, InstrumentRepository $instrumentRepository)
    {
        $this->entityManager = $entityManager;
        $this->instrumentRepository = $instrumentRepository;
    }

    public function addInstrumentsToUser(User $user, $instrumentData)
    {
        $instruments = $this->instrumentRepository->findBy(['name' => $this->decorateRawData($instrumentData)]);

        foreach ($user->getInstrument() as $instrument) {
            $user->removeInstrument($instrument);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        foreach ($instruments as $instrument) {
            $user->addInstrument($instrument);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $instruments;
    }

    private function decorateRawData($instrumentData)
    {
        $instrumentNames = [];
        if (!empty($instrumentData)) {
            foreach ($instrumentData as $instrument) {
                $instrumentNames[] = $instrument['value'];
            }
        }

        return $instrumentNames;
    }
}