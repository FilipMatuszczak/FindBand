<?php

namespace App\Controller;

use App\Entity\User;
use App\Services\Factory\BandFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class BandController extends AbstractController
{
    /** @var BandFactory */
    private $bandFactory;

    public function __construct(BandFactory $bandFactory)
    {
        $this->bandFactory = $bandFactory;
    }

    public function createIndexAction()
    {
        return $this->render('new-band.html.twig');
    }

    public function createBandAction(Request $request)
    {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $title = $request->get('title');
        $description = $request->get('text');
        $photo = $request->files->get('image');
        $musicGenres = $request->get('musicGenres');

        $this->bandFactory->createBand($title, $description, $photo, $musicGenres, $user);

        return $this->redirectToRoute('mainIndex');
    }
}