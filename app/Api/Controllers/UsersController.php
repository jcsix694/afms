<?php

namespace App\Api\Controllers;

use App\Api\Core\Helpers\StatusCodeHelper;
use App\Api\Core\Traits\ResponseTrait;
use App\Api\Repositories\UsersRepository;
use App\Api\Requests\CreateUserRequest;
use App\Api\Resources\UserResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class UsersController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ResponseTrait;

    protected $usersRepository;

    function __construct() {
        $this->usersRepository = new UsersRepository();
    }

    /**
     * Creates a customer
     *
     * @param CreateUserRequest $request
     *
     * @return JsonResponse
     * @throws \Exception
     *
     */
    public function createCustomer(CreateUserRequest $request)
    {
        try {
            return $this->success('Created customer', new UserResource($this->usersRepository->createCustomer($request)), StatusCodeHelper::STATUS_CREATED);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Returns a user
     *
     * @param CreateUserRequest $request
     *
     * @return JsonResponse
     * @throws \Exception
     *
     */
    public function get(Request $request){
        return $this->success('Returned user', new UserResource($request->user()));
    }
}
