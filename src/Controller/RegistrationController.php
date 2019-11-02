<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\UserProvider;
use App\Services\Factory\UserFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegistrationController extends AbstractController
{
    /** @var UserProvider */
    private $userProvider;

    /** @var UserFactory */
    private $userFactory;

    /**
     * @param UserProvider $userProvider
     * @param UserFactory $userFactory
     */
    public function __construct(UserProvider $userProvider, UserFactory $userFactory)
    {
        $this->userProvider = $userProvider;
        $this->userFactory = $userFactory;
    }

    /**
     * @param Request $request
     * @return RedirectResponse | Response
     */
    public function registerUserAction(Request $request)
    {
        $username = $request->get('username');
        $firstname = $request->get('firstname');
        $lastname =  $request->get('lastname');
        $email = $request->get('email');
        $password = $request->get('password');

        if ($this->userProvider->loadUserByUsername($username)) {
            $this->container->get('session')->getFlashBag()->set('notice', 'Użytkownik o takiej nazwie już istnieje');

            return $this->redirectToRoute('loginIndex');
        }

        $this->userFactory->createUser($username, $firstname, $lastname, $email, $password);

        return $this->redirectToRoute('sendAuthenticationLink', [
            'request' => $request
        ], 307);
    }
}