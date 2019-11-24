<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\BandRepository;
use App\Services\Factory\BandFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class BandController extends AbstractController
{
    /** @var BandFactory */
    private $bandFactory;

    /** @var BandRepository */
    private $bandRepository;

    public function __construct(BandFactory $bandFactory, BandRepository $bandRepository)
    {
        $this->bandFactory = $bandFactory;
        $this->bandRepository = $bandRepository;
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

        $band = $this->bandFactory->createBand($title, $description, $photo, $musicGenres, $user);

        return $this->redirectToRoute('bandProfileIndexAction', ['bandId' => $band->getBandId()]);
    }

    public function bandProfileIndexAction($bandId)
    {
        $band = $this->bandRepository->findOneBy(['bandId' => $bandId]);

        if (!$band) {
            throw $this->createNotFoundException();
        }

        return $this->render('band.html.twig', ['Band' => $band]);
    }
}