<?php

namespace App\Controller;

use App\Entity\User;

use App\Services\DataProvider\MainPageItemsDataProvider;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /** @var MainPageItemsDataProvider */
    private $mainPageItemsDataProvider;

    public function __construct(MainPageItemsDataProvider $mainPageItemsDataProvider)
    {
        $this->mainPageItemsDataProvider = $mainPageItemsDataProvider;
    }

    public function mainIndexAction()
    {
        /** @var User $user */
        $user = $this->get('security.token_storage')->getToken()->getUser();

        if ($user->hasOption(User::USER_ADMIN)) {
            return $this->redirectToRoute('adminReportsIndexAction');
        }

        $bands = $this->mainPageItemsDataProvider->getBandsForUser($user);
        $notices = $this->mainPageItemsDataProvider->getNoticesForUser($user);

        return $this->render('main.html.twig', ['bands' => $bands, 'notices' => $notices]);
    }
}