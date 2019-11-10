<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ProfileController extends AbstractController
{
    public function indexAction($username)
    {
        return $this->render('profile.html.twig');
    }

    public function editIndexAction($username)
    {
        return $this->render('edit-user.html.twig');
    }
}