<?php

namespace App\Api\Repositories;

use App\Api\Requests\AuthenticateRequest;
use Illuminate\Support\Facades\Hash;

class AuthRepository
{
    protected $usersRepository;

    function __construct()
    {
        $this->usersRepository = new UsersRepository();
    }

    public function authenticate(AuthenticateRequest $data){
        $user = $this->usersRepository->getUserByEmail($data->email);

        if(!$user) throw new \Exception('This user does not exist', 400);

        if(!Hash::check($data->password, $user->password)) throw new \Exception('Invalid Credentials', 400);

        return $user->createToken('auth_token')->plainTextToken;
    }
}
