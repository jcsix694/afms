<?php

namespace App\Api\Repositories;

use App\Api\Core\Helpers\StatusCodeHelper;
use App\Api\Models\CheckoutModel;
use App\Api\Requests\CreateCheckoutRequest;
use Carbon\Carbon;

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
            $oppwaCheckout = $this->oppwaRepository->createCheckout($request);
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

    public function getById($userId, $checkoutId){
        $checkout = $this->getByCheckoutId($userId, $checkoutId);

        if(!$checkout) throw new \Exception('Checkout does not exist', StatusCodeHelper::STATUS_UNPROCESSABLE);

        if($checkout->status === CheckoutModel::STATUS_PENDING){

            try{
                $oppwaCheckout = $this->oppwaRepository->getCheckout($checkoutId);
            } catch (\Exception $e) {
                throw new \Exception('Could not return information on the checkout', $e->getCode());
            }

            $processedCode = '?'; // To update this once access to production env
            if(env('APP_ENV') !== 'production') $processedCode = OppwaRepository::CODE_REQUEST_PROCESSED_TEST;

            if($oppwaCheckout->result->code !== $processedCode){
                throw new \Exception('Checkout has not been processed!', StatusCodeHelper::STATUS_UNPROCESSABLE);
            }

            $checkout->status = CheckoutModel::STATUS_COMPLETED;

            $checkout->saveOrFail();

            $checkout->payment()->create([
                'payment_id' => $oppwaCheckout->id,
                'amount' => $oppwaCheckout->amount,
                'response' => json_encode($oppwaCheckout),
                'completed_at' => Carbon::now()
            ]);

            $checkout->refresh();
        }

        return $checkout;
    }

    public function getByCheckoutId($userId, $checkoutId){
        return $this->queryGetByUserId($userId)->where('checkout_id', $checkoutId)->first();
    }
}
