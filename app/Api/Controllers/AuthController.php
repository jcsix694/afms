<?php

namespace App\Api\Controllers;

use App\Api\Core\Traits\ResponseTrait;
use App\Api\Repositories\AuthRepository;
use App\Api\Requests\AuthenticateRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class AuthController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ResponseTrait;

    protected $authRepository;

    function __construct() {
        $this->authRepository = new AuthRepository();
    }

    /**
     * Returns an access token for a valid user
     *
     * @param AuthenticateRequest $request
     *
     * @return JsonResponse
     * @throws \Exception
     *
     */
    public function authenticate(AuthenticateRequest $request)
    {
        try {
            return $this->accessToken($this->authRepository->authenticate($request));
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
