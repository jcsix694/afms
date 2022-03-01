<?php

namespace App\Api\Repositories;

use App\Api\Models\AccountTypeModel;

class AccountTypesRepository
{
    function __construct()
    {

    }

    /**
     * Returns the account type model filtered by type
     *
     * @param string $type
     *
     * @return AccountTypeModel
     *
     */
    public function getByType(string $type)
    {
        return AccountTypeModel::where('type', $type)->first();
    }
}
