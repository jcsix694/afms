<?php

namespace App\Api\Core\Helpers;

class PathHelper
{
    public function getApiPath()
    {
        return app_path('Api');
    }

    public function getRoutesPath()
    {
        return $this->getApiPath() . '/Routes';
    }

    public function getMigrationsPath()
    {
        return $this->getApiPath() . '/Migrations';
    }
}
