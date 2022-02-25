<?php

namespace App\Api\Repositories;

use App\Api\Models\AccountTypeModel;

class AccountTypesRepository
{
    function __construct()
    {

    }

    public function getByType($type){
        return AccountTypeModel::where('type', $type)->first();
    }
}
