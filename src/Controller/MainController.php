<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    public function mainIndexAction()
    {
        return $this->render('main.html.twig');
    }
}