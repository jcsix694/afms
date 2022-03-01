<?php

namespace App\Api\Repositories;

use App\Api\Core\Helpers\StatusCodeHelper;
use App\Api\Models\CheckoutModel;
use App\Api\Models\PaymentModel;
use App\Api\Models\RefundModel;
use App\Api\Requests\CreateRefundRequest;
use Carbon\Carbon;

class RefundRepository
{
    protected $oppwaRepository;

    function __construct()
    {
        $this->oppwaRepository = new OppwaRepository();
    }

    /**
     * Creates a refund
     *
     * @param CreateRefundRequest $request
     *
     * @return CheckoutModel
     *
     * @throws \Exception
     */
    public function create(CreateRefundRequest $request, int $userId)
    {
        // Checks if the payment exists in the database and if the payment is for the requested user
        $payment = PaymentModel::where('payment_id', $request->paymentId)->first();
        if(!$payment) throw new \Exception('Payment does not exist', StatusCodeHelper::STATUS_UNPROCESSABLE);
        if($payment->checkout->user_id !== $userId) throw new \Exception('Payment is not for this user', StatusCodeHelper::STATUS_UNPROCESSABLE);

        try {
            // Now will check if the refund can be accepted and returns a status of either refunded or partially_refunded
            $status = $this->canRefundBeAccepted($payment, $request->amount);
            $oppwaRefund = $this->oppwaRepository->refund($payment->payment_id, $request->amount);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }

        // Goes to save the refund in the database and returns the whole checkout
        return $this->saveRefund($payment, $oppwaRefund, $status);
    }

    /**
     * Saves the reufund in the database
     */
    public function saveRefund(PaymentModel $payment, object $oppwaRefund, string $status){
        $checkout = $payment->checkout;

        // sets the status on the checkout to refunded or parially_refunded
        $checkout->status = $status;

        // creates a refund for the payment
        $payment->refund()->create([
            'refund_id' => $oppwaRefund->id,
            'amount' => floatval($oppwaRefund->amount),
            'response' => json_encode($oppwaRefund),
            'completed_at' => Carbon::now()
        ]);

        $checkout->saveOrFail();
        return $checkout->refresh();
    }

    /**
     * Checks if the refund can be processed and returns a status
     */
    public function canRefundBeAccepted(PaymentModel $payment, float $amount){
        $amount = floatval($amount);
        $paymentAmount = floatval($payment->amount);

        // Goes to the database to check if there are any refunds for this payment
        $refunds = RefundModel::where('payment_id', $payment->id)->get();
        $refundsSum = floatval($refunds->sum('amount'));

        // throws an error if the payment has already been fully refunded
        if($refundsSum >= $paymentAmount) throw new \Exception('Payment has already been fully refunded', StatusCodeHelper::STATUS_UNPROCESSABLE);

        // Checks if the amount wanting to be refunded can be refunded
        $maxRefundAllowed = $paymentAmount - $refundsSum;
        if($amount > $maxRefundAllowed) throw new \Exception('The maximum refund amount can only be ' . number_format($maxRefundAllowed, 2, '.', ',') . ' ' . json_decode($payment->response)->currency, StatusCodeHelper::STATUS_UNPROCESSABLE);

        $refundsSum = $refundsSum + $amount;

        // will set the status to refunded if the full refund amount matches payment amount
        if($refundsSum === $paymentAmount) return CheckoutModel::STATUS_REFUNDED;

        // will set the status to partially refunded if not
        return CheckoutModel::STATUS_PARTIALLY_REFUNDED;
    }
}
