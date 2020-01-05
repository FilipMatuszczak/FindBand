<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\BandRepository;
use App\Repository\UserBandRepository;
use App\Security\UserProvider;
use App\Services\DataProvider\BandsPageDataProvider;
use App\Services\DataProvider\PostsDataProvider;
use App\Services\Factory\BandFactory;
use App\Services\Handler\BandHandler;
use App\Services\Handler\PaginationHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class BandController extends AbstractController
{
    /** @var BandFactory */
    private $bandFactory;

    /** @var BandRepository */
    private $bandRepository;

    /** @var BandsPageDataProvider */
    private $bandsPageDataProvider;

    /** @var PostsDataProvider */
    private $postsDataProvider;

    /** @var UserBandRepository */
    private $userBandRepository;

    /** @var BandHandler */
    private $bandHandler;

    /** @var UserProvider */
    private $userProvider;

    public function __construct(
        BandFactory $bandFactory,
        BandRepository $bandRepository,
        BandsPageDataProvider $bandsPageDataProvider,
        PostsDataProvider $postsDataProvider,
        UserBandRepository $userBandRepository,
        BandHandler $bandHandler,
        UserProvider $userProvider
    )
    {
        $this->bandFactory = $bandFactory;
        $this->bandRepository = $bandRepository;
        $this->bandsPageDataProvider = $bandsPageDataProvider;
        $this->postsDataProvider = $postsDataProvider;
        $this->userBandRepository = $userBandRepository;
        $this->bandHandler = $bandHandler;
        $this->userProvider = $userProvider;
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

        $posts = $this->postsDataProvider->getBandPosts($band);
        $authorId = $this->userBandRepository->getAuthorId($bandId);

        return $this->render('band.html.twig', ['Band' => $band, 'posts' => $posts, 'author' => $this->userProvider->loadUserById($authorId)]);
    }

    public function bandsIndexAction(Request $request)
    {
        $page = $request->get('page');
        $nextPage = null;

        if (empty($page)) {
            $page = 1;
        }

        $member = $request->get('member');
        $sorting = $request->get('sorting');
        $title = $request->get('title');

        if (!isset($sorting)) {
            $sorting = 'A-Z';
        }

        $musicGenre = !empty($request->get('musicGenre')) ? $request->get('musicGenre') : '';

        $bands = $this->bandsPageDataProvider->getBandRecordsByFilters((int)$page, $member, $sorting, $musicGenre, $title);
        $nextPageUrl = '';
        $previousPageUrl = '';

        if ($this->isNextPageNeeded($bands))
        {
            $nextPage = $page + 1;
            $nextPageUrl = PaginationHandler::getPageQueryUrlForBands($title, $member, $page+1, $musicGenre, $sorting);
        }
        $previousPageUrl =  PaginationHandler::getPageQueryUrlForBands($title, $member, $page-1, $musicGenre, $sorting);

        return $this->render('search_bands.html.twig', [
            'bands' => $bands,
            'page' => $page,
            'nextPage' => $nextPage,
            'nextPageUrl' => $nextPageUrl,
            'previousPageUrl' => $previousPageUrl
        ]);
    }

    public function editBandAction(Request $request)
    {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $bandId = $request->get('bandId');

        if ((int) ($this->userBandRepository->getAuthorId($bandId)) !== $user->getUserId()) {
            throw new BadRequestHttpException('User is not author');
        }

        $this->bandHandler->editBand(
            $bandId,
            $request->get('title'),
            $request->get('text'),
            $request->files->get('photo'),
            $request->get('musicGenres')
        );

        return $this->redirectToRoute('bandProfileIndexAction', ['bandId' => $bandId]);
    }

    public function getCurrentBandMusicGenres($bandId)
    {
        $musicGenres = [];

        foreach ($this->bandRepository->findOneBy(['bandId' => $bandId])->getMusicGenres() as $musicGenre) {
            $musicGenres[] = $musicGenre->getName();
        }

        return new JsonResponse($musicGenres);
    }

    private function isNextPageNeeded(&$bands)
    {
        if (sizeof($bands) == BandsPageDataProvider::BANDS_PER_PAGE + 1)
        {
            array_pop($bands);

            return true;
        }

        return false;
    }
}