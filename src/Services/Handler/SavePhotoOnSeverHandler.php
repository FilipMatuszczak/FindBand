<?php

namespace App\Services\Handler;

use Symfony\Component\HttpFoundation\File\File;

class SavePhotoOnSeverHandler
{
    const USER_PROFILE_DIR = '/user_profile_photo/';
    const BAND_PROFILE_DIR = '/band_profile_photo/';
    const UPLOAD_DIRECTORY = 'uploads';

    public static function savePhotoOnServer(File $file, $directoryDir)
    {
        if ($file) {
            $destinationDirectory = getcwd() . '/' . self::UPLOAD_DIRECTORY . $directoryDir;
            $fileName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);

            $newFilename = $fileName . '-' . uniqid() . '.' . $file->guessExtension();

            $file->move(
                $destinationDirectory,
                $newFilename
            );

            return $newFilename;
        }

        return null;
    }
}