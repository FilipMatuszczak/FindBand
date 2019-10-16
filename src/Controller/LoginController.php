<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class LoginController extends AbstractController
{
    public function loginIndex()
    {
        return $this->render('index.html.twig');
    }
}