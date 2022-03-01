<?php

namespace App\Api\Repositories;

use App\Api\Core\Helpers\StatusCodeHelper;
use App\Api\Requests\AuthenticateRequest;
use Illuminate\Support\Facades\Hash;

class AuthRepository
{
    protected $usersRepository;

    function __construct()
    {
        $this->usersRepository = new UsersRepository();
    }


    /**
     * Returns an plain text auth token
     *
     * @param AuthenticateRequest $request
     *
     * @return string
     * @throws \Exception
     *
     */
    public function authenticate(AuthenticateRequest $data)
    {
        $user = $this->usersRepository->getUserByEmail($data->email);

        if(!$user) throw new \Exception('This user does not exist', StatusCodeHelper::STATUS_NOT_FOUND);

        if(!Hash::check($data->password, $user->password)) throw new \Exception('Invalid Credentials', StatusCodeHelper::STATUS_UNPROCESSABLE);

        return $user->createToken('auth_token')->plainTextToken;
    }
}
