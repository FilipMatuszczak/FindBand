<?php

namespace App\Services\DataProvider;

use App\Entity\Band;
use App\Entity\User;
use App\Repository\PostRepository;

class PostsDataProvider
{
    /** @var PostRepository */
    private $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    public function getUserPosts(User $user)
    {
        return $this->postRepository->findBy(['user' => $user, 'band' => null], ['timestamp' => 'DESC']);
    }

    public function getBandPosts(Band $band)
    {
        return $this->postRepository->findBy(['band' => $band], ['timestamp' => 'DESC']);
    }
}