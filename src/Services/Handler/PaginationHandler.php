<?php

namespace App\Services\Handler;

class PaginationHandler
{
    public static function getPageQueryUrlForUsers($firstName, $lastName, $page, $instrument, $musicGenre, $city, $sorting)
    {
        return
            '?firstName=' . $firstName .'&' .
            'lastName=' . $lastName .'&' .
            'page=' . $page .'&' .
            'instrument=' . $instrument .'&' .
            'musicGenre=' . $musicGenre .'&' .
            'city=' . $city .'&' .
            'sorting=' . $sorting .'&';
    }
}