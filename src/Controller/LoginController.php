<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\UserProvider;
use App\Services\Handler\PasswordHandler;
use App\Services\Handler\UserHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    /** @var AuthenticationUtils */
    private $authenticationUtils;

    /** @var UserProvider */
    private $userProvider;

    /** @var PasswordHandler */
    private $passwordHandler;

    /** @var UserHandler */
    private $userHandler;

    public function __construct(
        AuthenticationUtils $authenticationUtils,
        UserProvider $userProvider,
        PasswordHandler $passwordHandler,
        UserHandler $userHandler
    )
    {
        $this->authenticationUtils = $authenticationUtils;
        $this->userProvider = $userProvider;
        $this->passwordHandler = $passwordHandler;
        $this->userHandler = $userHandler;
    }

    public function changePasswordAction(Request $request)
    {
        $user = $this->userProvider->loadUserById($this->get('session')->get('userId'));

        if (empty($user)) {
            throw ($this->createNotFoundException());
        }

        $newPassword = $request->get('password');
        $this->userHandler->generateNewPassword($user, $newPassword);

        $this->get('session')->getFlashBag()->set('notice', 'Twoje hasło zostało pomyślnie zmienione');

        return $this->redirectToRoute('loginIndex');
    }

    public function changePasswordIndexAction($userId, $changePasswordLink)
    {
        $user = $this->userProvider->loadUserById($userId);

        if ($user) {
            $expirationDate = new \DateTime();
            $expirationDate->setTimestamp($user->getChangePasswordLinkExpirationDate()->getTimestamp());

            $changePasswordLink =
                $this->passwordHandler->getHashFromPlainTextAndSalt($changePasswordLink, $user->getSalt());

            if ($user->getChangePasswordLink() === $changePasswordLink
                && $this->isChangePasswordLinkExpired($expirationDate)
                && $user->hasOption(User::USER_CHANGING_PASSWORD)
            ) {
                $this->get('session')->set('userId', $user->getUserId());

                return $this->render('changepswd-web.html.twig');
            }
        }

        throw($this->createNotFoundException());
    }

    public function loginIndex($message = null)
    {
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return $this->redirectToRoute('mainIndex');
        }

        $error = $this->authenticationUtils->getLastAuthenticationError();
        $lastUsername = $this->authenticationUtils->getLastUsername();

        return $this->render('index.html.twig', ['last_username' => $lastUsername, 'error' => $error, 'message' => $message]);
    }

    private function isChangePasswordLinkExpired(\DateTime $expirationDate)
    {
        $currentDate = new \DateTime('now');

        return $currentDate < $expirationDate;
    }
}