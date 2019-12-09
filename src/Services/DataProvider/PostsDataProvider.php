<?php

namespace App\Services\DataProvider;

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
        return $this->postRepository->findBy(['user' => $user, 'band' => null]);
    }
}