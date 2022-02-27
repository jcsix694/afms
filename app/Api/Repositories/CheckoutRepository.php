<?php

namespace App\Api\Repositories;

use App\Api\Core\Helpers\StatusCodeHelper;
use App\Api\Models\CheckoutModel;
use App\Api\Requests\CreateCheckoutRequest;

class CheckoutRepository
{
    protected $oppwaRepository;

    function __construct()
    {
        $this->oppwaRepository = new OppwaRepository();
    }

    public function create(CreateCheckoutRequest $request, int $userId)
    {
        try {
            $oppwaCheckout = $this->oppwaRepository->checkout($request);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }

        return $this->saveCheckout($request, $oppwaCheckout, $userId);
    }

    public function saveCheckout(CreateCheckoutRequest $request, $oppwaCheckout, int $userId)
    {
        try {
            $checkout = new CheckoutModel([
                'user_id' => $userId,
                'reference' => $request->reference,
                'amount' => $request->amount,
                'checkout_id' => $oppwaCheckout->id,
                'response' => json_encode($oppwaCheckout)
            ]);

            $checkout->saveOrFail();
            return $checkout->refresh();
        } catch (\Exception $e) {
            throw new \Exception('Something went wrong whilst trying to create this checkout, please try again.', StatusCodeHelper::INTERNAL_SERVER_ERROR);
        }
    }

    public function queryGetByUserId(int $userId){
        return CheckoutModel::where('user_id', $userId);
    }

    public function getByUserIdAll(int $userId){
        return $this->queryGetByUserId($userId)->get();
    }

    public function getByUserIdPaginated(int $userId){
        return $this->queryGetByUserId($userId)->paginate(5);
    }
}
