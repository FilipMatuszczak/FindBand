<?php

namespace App\Controller;

use App\Entity\Ban;
use App\Entity\User;
use App\Repository\BanRepository;
use App\Security\UserProvider;
use App\Services\DataProvider\PostsDataProvider;
use App\Services\Handler\BlockedUsersHandler;
use App\Services\Handler\InstrumentHandler;
use App\Services\Handler\MusicGenreHandler;
use App\Services\Handler\UserHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProfileController extends AbstractController
{
    /** @var UserProvider */
    private $userProvider;

    /** @var UserHandler */
    private $userHandler;

    /** @var InstrumentHandler */
    private $instrumentHandler;

    /** @var MusicGenreHandler */
    private $musicGenreHandler;

    /** @var PostsDataProvider */
    private $postsDataProvider;

    /** @var BlockedUsersHandler */
    private $blockedUsersHandler;

    /** @var BanRepository */
    private $banRepository;

    public function __construct(
        UserProvider $userProvider,
        UserHandler $userHandler,
        InstrumentHandler $instrumentHandler,
        MusicGenreHandler $musicGenreHandler,
        PostsDataProvider $postsDataProvider,
        BlockedUsersHandler $blockedUsersHandler,
        BanRepository $banRepository
    )
    {
        $this->userProvider = $userProvider;
        $this->instrumentHandler = $instrumentHandler;
        $this->userHandler = $userHandler;
        $this->musicGenreHandler = $musicGenreHandler;
        $this->postsDataProvider = $postsDataProvider;
        $this->blockedUsersHandler = $blockedUsersHandler;
        $this->banRepository = $banRepository;
    }

    public function indexAction($username)
    {
        $user = $this->userProvider->loadUserByUsername($username);
        $posts = $this->postsDataProvider->getUserPosts($user);

        return $this->render('profile.html.twig', ['user' => $user, 'posts' => $posts]);
    }

    public function editIndexAction($username)
    {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();

        if ($user->getUsername() === $username) {
            return $this->render('edit-user.html.twig');
        }

        throw $this->createNotFoundException('This page does not exist or you have no permission to view it');
    }

    public function editProfileAction(Request $request) {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $firstname = $request->get('firstname');
        $lastname = $request->get('lastname');
        $dateOfBirth = $request->get('dateOfBirth');
        $info = $request->get('info');
        $newsletter = $request->get('newsletter');
        $photo = $request->files->get('fileToUpload');
        $cityName = $request->get('city');

        $this->userHandler->editCommonUserData($user, $firstname, $lastname, $dateOfBirth, $info, $newsletter, $photo, $cityName);

        return $this->redirectToRoute('profileEditAction', ['username' => $user->getUsername()]);
    }

    public function getCurrentUserBlockedUsersAction()
    {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $blockedUsers = $this->banRepository->findBy(['user' => $user]);
        $usernames = [];

        foreach ($blockedUsers as $blockedUser) {
            $usernames[] = $blockedUser->getSubject()->getUsername();
        }

        return new JsonResponse($usernames);
    }

    public function getCurrentUserInstrumentsAction()
    {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $instruments = [];

        foreach ($user->getInstrument() as $instrument) {
            $instruments[] = $instrument->getName();
        }

        return new JsonResponse($instruments);
    }

    public function getCurrentUserMusicGenresAction()
    {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $musicGenres = [];

        foreach ($user->getMusicGenres() as $musicGenre) {
            $musicGenres[] = $musicGenre->getName();
        }

        return new JsonResponse($musicGenres);
    }

    public function updateCurrentUserInstrumentsAction(Request $request)
    {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $instrumentsUpdated = $this->instrumentHandler->addInstrumentsToUser($user, $request->get('instruments'));

        $instrumentNames = [];

        foreach ($instrumentsUpdated as $instrument)
        {
            $instrumentNames[] = $instrument->getName();
        }

        return new Response('Instruments updated for ' . $user->getUsername() . ': ' . implode(', ', $instrumentNames), 200);
    }

    public function updateCurrentUserMusicGenresAction(Request $request)
    {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();

        $musicGenresData = $this->musicGenreHandler->addMusicGenresToUser($user, $request->get('musicGenres'));

        $musicGenresNames = [];

        foreach ($musicGenresData as $musicGenresDatum)
        {
            $musicGenresNames[] = $musicGenresDatum->getName();
        }

        return new Response('Music genres updated for ' . $user->getUsername() . ': ' . implode(', ', $musicGenresNames), 200);
    }

    public function updateCurrentUserBlockedUsers(Request $request)
    {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();

        /** @var User [] $usernamesBlocked */
        $blockedUsersData = $this->blockedUsersHandler->addBlockedUsersForUser($user, $request->get('usernames'));

        $usernamesBlocked = [];

        foreach ($blockedUsersData as $blockedUsersDatum)
        {
            $usernamesBlocked[] = $blockedUsersDatum->getUsername();
        }

        return new Response('Ids of blocked users for user ' . $user->getUsername() . ': ' . implode(', ', $usernamesBlocked), 200);
    }
}