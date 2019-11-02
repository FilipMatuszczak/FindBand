<?php

namespace App\Services\Handler;

use App\Entity\User;

class PasswordHandler
{
    const hashingIterations = 476;

    /**
     * @param $password
     *
     * @return array
     */
    public function generateHashAndSalt($password)
    {
        $salt = $this->getRandomString(32);

        $hash = $this->getHashFromPlainTextAndSalt($password, $salt);

        return [
            User::COLUMN_PASSWORD => $hash,
            User::COLUMN_SALT => $salt,
        ];
    }

    /**
     * @param string $text
     * @param string $salt
     *
     * @return string
     */
    public function getHashFromPlainTextAndSalt($text, $salt)
    {
        $hash = hash('sha512', $text . $salt);

        for ($i = 0; $i < self::hashingIterations; $i++)
        {
            $hash = hash('sha512', $hash . $salt);
        }

        return $hash;
    }

    /**
     * @param int $size
     *
     * @return string
     */
    private function getRandomString($size)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $size; $i++) {
            $index = rand(0, strlen($characters) - 1);
            $randomString .= $characters[$index];
        }

        return $randomString;
    }
}