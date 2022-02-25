<?php

namespace App\Api\Controllers;

use App\Api\Models\UsersModel;
use App\Api\Requests\AuthenticateRequest;
use App\Api\Resources\UsersResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class AuthController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function authenticate(AuthenticateRequest $request)
    {

    }
}
