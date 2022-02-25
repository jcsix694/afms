<?php

namespace App\Api\Repositories;

use App\Api\Models\AccountTypeModel;
use App\Api\Models\UserModel;
use App\Api\Requests\CreateUserRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersRepository
{
    protected $accountTypesRepository;

    function __construct()
    {
        $this->accountTypesRepository = new AccountTypesRepository();
    }

    public function createCustomer(CreateUserRequest $data){
        try {
            $data->accountType = AccountTypeModel::CUSTOMER;
            return $this->create($data);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }

    public function create($data)
    {
        DB::beginTransaction();
        if($this->getUserByEmail($data->email)) throw new \Exception('The email has already been taken.', 422);

        if(!$data->accountType) throw new \Exception('Account type is Required', 422);

        $accountType = $this->accountTypesRepository->getByType($data->accountType);

        if(!$accountType) throw new \Exception('Account type does not exist', 422);

        $user = new UserModel([
            'email' => $data->email,
            'name' => $data->name,
            'surname' => $data->surname,
            'password' => Hash::make($data->password),
        ]);

        try {
            $user->saveOrFail();

            $user->userAccount()->create([
                'account_type_id' => $accountType->id,
            ]);

            DB::commit();
            return $user->refresh();
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception('Something went wrong whilst trying to create this user, please try again.', 500);
        }
    }

    public function getUserByEmail($email){
        return UserModel::where('email', $email)->first();
    }
}
