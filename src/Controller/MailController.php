<?php

namespace App\Controller;

use App\Security\UserProvider;
use App\Services\Handler\UserHandler;
use Swift_Mailer;
use Swift_Message;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MailController extends AbstractController
{
    /** @var Swift_Mailer */
    private $mailer;

    /** @var UserHandler */
    private $userHandler;

    /** @var UserProvider */
    private $userProvider;

    public function __construct(Swift_Mailer $mailer, UserHandler $userHandler, UserProvider $userProvider)
    {
        $this->mailer = $mailer;
        $this->userHandler = $userHandler;
        $this->userProvider = $userProvider;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function sendRegisterMailAction(Request $request)
    {
        $authenticationLink = $this->getRandomString();

        /** @var Request $previousRequest */
        $username = $request->get('username');
        $email = $request->get('email');

        $this->userHandler->updateAuthenticationLinkHash($username, $authenticationLink);

        $verificationUrl = $this->generateUrl
        (
            'authenticateAccount',
            [
                'username' => $username,
                'authenticationLink' => $authenticationLink,
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $message = (new Swift_Message('FindBand - Confirmation email'))
            ->setFrom('findband.no.reply@gmail.com')
            ->setTo($email)
            ->setBody(
                $this->renderView(
                    'registration.html.twig',
                    [
                        'name' => $username,
                        'verificationUrl' => $verificationUrl,
                    ]
                ),
                'text/html'
            );

        $this->mailer->send($message);
        $this->container->get('session')->getFlashBag()->set('notice', 'Email potwierdzający został wysłany na twoją skrzynkę mailową');

        return $this->redirectToRoute('loginIndex', [], 301);
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function sendChangePasswordAction(Request $request)
    {
        $username = $request->get('username');
        $user = $this->userProvider->loadUserByUsername($username);

        if ($user) {
            $changePasswordLink = $this->getRandomString();

            $this->userHandler->generateChangePasswordLinkForUser($user, $changePasswordLink);

            $changePasswordUrl = $this->generateUrl
            (
                'changePasswordIndex',
                [
                    'userId' => $user->getUserId(),
                    'changePasswordLink' => $changePasswordLink,
                ],
                UrlGeneratorInterface::ABSOLUTE_URL
            );

            $message = (new Swift_Message('FindBand - Change password'))
                ->setFrom('findband.no.reply@gmail.com')
                ->setTo($user->getEmail())
                ->setBody(
                    $this->renderView(
                        'change_password_email.html.twig',
                        [
                            'name' => $username,
                            'changePasswordUrl' => $changePasswordUrl,
                        ]
                    ),
                    'text/html'
                );

            $this->mailer->send($message);
        }
        $this->get('session')->getFlashBag()->set('notice', 'Na twoją skrzynkę pocztową został wysłany email umożliwiający zmianę hasła');

        return $this->redirectToRoute('loginIndex');
    }

    private function getRandomString()
    {
        return substr(str_shuffle(str_repeat($x = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(128 / strlen($x)))), 1, 128);

    }
}