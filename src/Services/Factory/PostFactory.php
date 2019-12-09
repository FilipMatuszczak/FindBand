<?php

namespace App\Services\Factory;

use App\Entity\Post;
use App\Entity\User;
use App\Services\Handler\SavePhotoOnSeverHandler;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;

class PostFactory
{
    /** @var EntityManager */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createPostForUserProfile(User $user, $text, $photo)
    {
        $post = new Post();

        $post->setText($text)->setTimestamp(new \DateTime());

        if ($photo) {
            $fileName = SavePhotoOnSeverHandler::savePhotoOnServer($photo, SavePhotoOnSeverHandler::POST_DIR);
            $post->setPhoto($fileName);
        }

        $post->setUser($user);

        $this->entityManager->persist($post);
        $this->entityManager->flush();
    }
}