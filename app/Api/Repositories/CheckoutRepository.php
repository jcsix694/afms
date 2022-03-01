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

    /**
     * Returns a created checkout
     *
     * @param CreateCheckoutRequest $request
     *
     * @return CheckoutModel
     * @throws \Exception
     *
     */
    public function create(CreateCheckoutRequest $request, int $userId)
    {
        try {
            // Goes to creat the checkout with the oppwa integration
            $oppwaCheckout = $this->oppwaRepository->createCheckout($request);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }

        // if a checkout has been created with oppwa - now save the checkout in the database
        return $this->saveCheckout($request, $oppwaCheckout, $userId);
    }

    /**
     * Returns a created checkout
     *
     * @param CreateCheckoutRequest $request
     * @param int $userId
     * @param object $oppwaCheckout
     *
     * @return CheckoutModel
     * @throws \Exception
     *
     */
    public function saveCheckout(CreateCheckoutRequest $request, object $oppwaCheckout, int $userId)
    {
        try {
            // Create and save the checkout to the database
            $checkout = new CheckoutModel([
                'user_id' => $userId,
                'reference' => $request->reference,
                'amount' => floatval($request->amount),
                'checkout_id' => $oppwaCheckout->id,
                'response' => json_encode($oppwaCheckout)
            ]);

            $checkout->saveOrFail();
            return $checkout->refresh();
        } catch (\Exception $e) {
            throw new \Exception('Something went wrong whilst trying to create this checkout, please try again.', StatusCodeHelper::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Returns the base query forgetting a checkout by user_id
     */
    public function queryGetByUserId(int $userId)
    {
        return CheckoutModel::where('user_id', $userId);
    }

    /**
     * Returns checkouts filtered by user id
     *
     * @param int $userId
     *
     * @return CheckoutModel
     */
    public function getByUserIdAll(int $userId)
    {
        return $this->queryGetByUserId($userId)->get();
    }

    /**
     * Returns checkouts filtered by user id and paginated by 5
     *
     * @param int $userId
     *
     * @return CheckoutModel
     */
    public function getByUserIdPaginated(int $userId)
    {
        return $this->queryGetByUserId($userId)->paginate(5);
    }

    /**
     * Returns a checkout filtered by user id and checkout id
     *
     * @param int $userId
     * @param string $checkoutId
     *
     * @return CheckoutModel
     * @throws \Exception
     */
    public function getById(int $userId, string $checkoutId){
        // Checks if the checkout if is in the database
        $checkout = $this->getByCheckoutId($userId, $checkoutId);
        if(!$checkout) throw new \Exception('Checkout does not exist', StatusCodeHelper::STATUS_UNPROCESSABLE);

        // If the current status of the checkout is pending then we need to go to the oppwa api and return the most up to date status/information
        if($checkout->status === CheckoutModel::STATUS_PENDING){
            // Trying to get the checkout with oppwa integration
            try{
                $oppwaCheckout = $this->oppwaRepository->getCheckout($checkoutId);
            } catch (\Exception $e) {
                throw new \Exception('Could not return information on the checkout', $e->getCode());
            }

            // Sets the processCode to check with the integration
            $processedCode = '?'; // To update this once access to production env, currently no access to production sustem
            if(env('APP_ENV') !== 'production') $processedCode = OppwaRepository::CODE_REQUEST_PROCESSED_TEST;

            // Throw an exception if the checkout is not processed
            if($oppwaCheckout->result->code !== $processedCode){
                throw new \Exception('Checkout has not been processed!', StatusCodeHelper::STATUS_UNPROCESSABLE);
            }

            // Update the status of the checkout to completed in the database
            $checkout->status = CheckoutModel::STATUS_COMPLETED;
            $checkout->saveOrFail();

            // Create a payment in the database using the information from the oppwa integration response
            $checkout->payment()->create([
                'payment_id' => $oppwaCheckout->id,
                'amount' => floatval($oppwaCheckout->amount),
                'response' => json_encode($oppwaCheckout),
                'completed_at' => Carbon::now()
            ]);
            $checkout->refresh();
        }

        return $checkout;
    }

    /**
     * Returns a checkout by user id and checkout id
     */
    public function getByCheckoutId(int $userId, string $checkoutId)
    {
        return $this->queryGetByUserId($userId)->where('checkout_id', $checkoutId)->first();
    }
}
