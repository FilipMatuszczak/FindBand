<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\UserProvider;
use App\Services\Handler\PasswordHandler;
use App\Services\Handler\UserHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /** @var UserProvider */
    private $userProvider;

    /** @var PasswordHandler */
    private $passwordHandler;

    /** @var UserHandler */
    private $userHandler;

    public function __construct(UserProvider $userProvider, PasswordHandler $passwordHandler, UserHandler $userHandler)
    {
        $this->userProvider = $userProvider;
        $this->passwordHandler = $passwordHandler;
        $this->userHandler = $userHandler;
    }

    /**
     * @Route("/login", name="app_login")
     *
     * @param AuthenticationUtils $authenticationUtils
     *
     * @return Response
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // if ($this->getUser()) {
        //    $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        // logout
    }

    public function authenticateAccountAction($username, $authenticationLink)
    {
        /** @var User $user */
        $user = $this->userProvider->loadUserByUsername($username);

        $authenticationLinkSalt =  $this->passwordHandler->getHashFromPlainTextAndSalt($authenticationLink, $user->getSalt());

        if ($user->getAuthenticationLink() !== $authenticationLinkSalt || $user->getOptions() & User::USER_VERIFIED) {
            throw $this->createNotFoundException('Wygląda na to, że strona której szukasz już nie istnieje lub nigdy nie istniała');
        }

        $this->userHandler->authenticateUser($user);

        $this->container->get('session')->getFlashBag()->set('notice', 'Twoje konto zostało pomyślnie zweryfikowane, możesz teraz bezpiecznie się zalogować');

        return $this->redirectToRoute('loginIndex');
    }
}
