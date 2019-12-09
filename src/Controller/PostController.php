<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\BandRepository;
use App\Services\Factory\PostFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PostController extends AbstractController
{
    /** @var PostFactory */
    private $postFactory;

    /** @var BandRepository */
    private $bandRepository;

    public function __construct(PostFactory $postFactory, BandRepository $bandRepository)
    {
        $this->postFactory = $postFactory;
        $this->bandRepository = $bandRepository;
    }

    public function createPostOnBand(Request $request)
    {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $band = $this->bandRepository->findOneBy(['bandId' => $request->get('bandId')]);

        if (!$user->isPartOfBand($band)) {
            throw new BadRequestHttpException();
        }

        $photo = $request->files->get('photo');
        $text = $request->get('text');

        $this->postFactory->createPostForBandProfile($user, $band, $text, $photo);

        return $this->redirectToRoute('bandProfileIndexAction', ['bandId' => $band->getBandId()]);
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