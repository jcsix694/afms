<?php

namespace App\Api\Core\Helpers;

class FileHelper
{
    public function getFilesFromPath($path)
    {
        return \File::allFiles($path);
    }
}
