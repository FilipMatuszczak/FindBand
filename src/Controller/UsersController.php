<?php


namespace App\Controller;


use App\Entity\User;
use App\Services\DataProvider\UserPageDataProvider;
use App\Services\Handler\PaginationHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class UsersController extends AbstractController
{
    /** @var UserPageDataProvider */
    private $userPageDataProvider;

    public function __construct(UserPageDataProvider $userPageDataProvider)
    {
        $this->userPageDataProvider = $userPageDataProvider;
    }

    public function indexAction(Request $request)
    {
        $page = $request->get('page');
        $nextPage = null;

        if (empty($page)) {
            $page = 1;
        }

        $firstName = $request->get('firstName');
        $lastName = $request->get('lastName');
        $sorting = $request->get('sorting');

        if (!isset($sorting)) {
            $sorting = 'A-Z';
        }

        $musicGenre = !empty($request->get('musicGenre')) ? $request->get('musicGenre') : '';
        $instrument = !empty($request->get('instrument')) ? $request->get('instrument') : '';
        $city = !empty($request->get('city')) ? $request->get('city') : '';

        $users = $this->userPageDataProvider->getUserRecordsByFilters((int)$page, $firstName, $lastName, $sorting, $instrument, $musicGenre, $city);
        $nextPageUrl = '';
        $previousPageUrl = '';

        if ($this->isNextPageNeeded($users))
        {
            $nextPage = $page + 1;
            $nextPageUrl = PaginationHandler::getPageQueryUrlForUsers($firstName, $lastName, $page+1, $instrument, $musicGenre, $city, $sorting);
            $previousPageUrl = PaginationHandler::getPageQueryUrlForUsers($firstName, $lastName, $page-1, $instrument, $musicGenre, $city, $sorting);
        }

        return $this->render('search_users.html.twig', [
            'users' => $users,
            'page' => $page,
            'nextPage' => $nextPage,
            'nextPageUrl' => $nextPageUrl,
            'previousPageUrl' => $previousPageUrl
            ]);
    }

    private function isNextPageNeeded(&$users)
    {
        if (sizeof($users) == UserPageDataProvider::USERS_PER_PAGE + 1)
        {
            array_pop($users);

            return true;
        }

        return false;
    }
}