<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\UserProvider;
use App\Services\Handler\UserHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends AbstractController
{
    /** @var UserProvider */
    private $userProvider;

    /** @var UserHandler */
    private $userHandler;

    public function __construct(UserProvider $userProvider, UserHandler $userHandler)
    {
        $this->userProvider = $userProvider;
        $this->userHandler = $userHandler;
    }

    public function indexAction($username)
    {
        $user = $this->userProvider->loadUserByUsername($username);

        return $this->render('profile.html.twig', ['user' => $user]);
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

        $this->userHandler->editCommonUserData($user, $firstname, $lastname, $dateOfBirth, $info, $newsletter, null);

        return $this->redirectToRoute('profileEditAction', ['username' => $user->getUsername()]);
    }
}