<?php

namespace App\Services\Factory;

use App\Entity\Notice;
use App\Entity\User;
use App\Repository\BandRepository;
use App\Repository\InstrumentRepository;
use App\Repository\MusicGenreRepository;
use App\Repository\UserBandRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class NoticeFactory
{
    const CURRENT_USER_AUTHOR = 'Ja';

    /** @var BandRepository */
    private $bandRepository;

    /** @var EntityManager */
    private $entityManager;

    /** @var UserBandRepository */
    private $userBandRepository;

    /** @var InstrumentRepository */
    private $instrumentRepository;

    public function __construct(
        BandRepository $bandRepository,
        EntityManagerInterface $entityManagerInterface,
        UserBandRepository $userBandRepository,
        InstrumentRepository $instrumentRepository
    )
    {
        $this->bandRepository = $bandRepository;
        $this->entityManager = $entityManagerInterface;
        $this->userBandRepository = $userBandRepository;
        $this->instrumentRepository = $instrumentRepository;
    }

    public function createNotice($title, $description, $instrument, User $author, $who)
    {
        $notice = new Notice();

        $notice->setTitle($title)->setDetails($description)->setTimestamp(new \DateTime('now'));

        if ($author) {
            $notice->setUser($author);
        }

        $this->handleWhoIsAuthor($who, $notice, $author);

        $userInstrument = $this->instrumentRepository->findOneBy(['name' => $instrument]);
        $notice->setInstrument($userInstrument);

        $this->entityManager->persist($notice);
        $this->entityManager->flush();

        return $notice;
    }

    public function handleWhoIsAuthor($who, Notice &$notice, User $author)
    {
        if ($who !== self::CURRENT_USER_AUTHOR) {
            $band = $this->bandRepository->findOneBy(['title' => $who]);
            if ($band->getUser()->contains($author)) {
                $notice->setBand($band);
            } else throw new NotFoundHttpException('No band for user');
        }
    }
}