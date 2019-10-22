<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    /** @var AuthenticationUtils */
    private $authenticationUtils;


    public function __construct(AuthenticationUtils $authenticationUtils)
    {
        $this->authenticationUtils = $authenticationUtils;
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
}