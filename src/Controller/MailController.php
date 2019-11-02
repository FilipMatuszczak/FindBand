<?php

namespace App\Controller;

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

    public function __construct(Swift_Mailer $mailer, UserHandler $userHandler)
    {
        $this->mailer = $mailer;
        $this->userHandler = $userHandler;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function sendRegisterMailAction(Request $request)
    {
        $authenticationLink = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil(128/strlen($x)) )),1,128);

        /** @var Request $previousRequest */
       // $previousRequest = $request->get('request');
        $username =  $request->get('username');
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
     * @param string $email
     * @param string $username
     * @param string $changePasswordLink
     *
     * @return RedirectResponse
     */
    public function sendChangePasswordAction($email, $username, $changePasswordLink)
    {
        $changePasswordLink = $this->generateUrl
        (
            'change_password',
            [
                'username' => $username,
                'changePasswordLink' => $changePasswordLink,
            ],
            UrlGeneratorInterface::ABSOLUTE_URL
        );
        $message = (new Swift_Message('Scout - Change password'))
            ->setFrom('scoutregister1@gmail.com')
            ->setTo($email)
            ->setBody(
                $this->renderView(
                    'change_password_email.html.twig',
                    [
                        'name' => $username,
                        'changePasswordUrl' => $changePasswordLink,
                    ]
                ),
                'text/html'
            );
        $this->mailer->send($message);
        $this->get('session')->getFlashBag()->set('notice', 'Na twoją skrzynkę pocztową został wysłany email umożliwiający zmianę hasła');

        return $this->redirectToRoute('index');
    }
}