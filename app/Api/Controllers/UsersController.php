<?php

namespace App\Api\Controllers;

use App\Api\Core\Traits\ResponseTrait;
use App\Api\Repositories\UsersRepository;
use App\Api\Requests\CreateUserRequest;
use App\Api\Resources\UsersResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class UsersController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ResponseTrait;

    protected $usersRepository;

    function __construct() {
        $this->usersRepository = new UsersRepository();
    }

    public function createCustomer(CreateUserRequest $request)
    {
        try{
            return $this->success('Created customer', new UsersResource($this->usersRepository->createCustomer($request)));
        }catch (\Exception $e) {
           return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
