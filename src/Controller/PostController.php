<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\Factory\PostFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class PostController extends AbstractController
{
    /** @var PostFactory */
    private $postFactory;

    public function __construct(PostFactory $postFactory)
    {
        $this->postFactory = $postFactory;
    }

    public function createPostOnProfile(Request $request)
    {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $photo = $request->files->get('photo');
        $text = $request->get('text');

        $this->postFactory->createPostForUserProfile($user, $text, $photo);

        return $this->redirectToRoute('profileIndexAction', ['username' => $user->getUsername()]);
    }
}