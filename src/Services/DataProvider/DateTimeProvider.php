<?php

namespace App\Services\DataProvider;

class DateTimeProvider
{
    public function getDateTimeAsString(?\DateTime $dateTime)
    {
        if ($dateTime) {
        return $dateTime->format('Y-m-d H:i:s'); }

        return null;
    }
}