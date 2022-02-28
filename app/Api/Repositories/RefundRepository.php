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

    public function create(CreateRefundRequest $request, int $userId){
        $payment = PaymentModel::where('payment_id', $request->paymentId)->first();

        if(!$payment) throw new \Exception('Payment does not exist', StatusCodeHelper::STATUS_UNPROCESSABLE);

        if($payment->checkout->user_id !== $userId) throw new \Exception('Payment is not for this user', StatusCodeHelper::STATUS_UNPROCESSABLE);

        try {
            $status = $this->canRefundBeAccepted($payment, $request->amount);
            $oppwaRefund = $this->oppwaRepository->refund($payment->payment_id, $request->amount);
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }

        return $this->saveRefund($payment, $oppwaRefund, $status);
    }

    public function saveRefund($payment, $oppwaRefund, $status){
        $checkout = $payment->checkout;
        $checkout->status = $status;

        $payment->refund()->create([
            'refund_id' => $oppwaRefund->id,
            'amount' => $oppwaRefund->amount,
            'response' => json_encode($oppwaRefund),
            'completed_at' => Carbon::now()
        ]);

        $checkout->saveOrFail();
        return $checkout->refresh();
    }

    public function canRefundBeAccepted($payment, $amount){
        $amount = floatval($amount);
        $refunds = RefundModel::where('payment_id', $payment->id)->get();

        $refundsSum = $refunds->sum('amount');

        if($refundsSum >= $payment->amount) throw new \Exception('Payment has already been fully refunded', StatusCodeHelper::STATUS_UNPROCESSABLE);

        $maxRefundAllowed = $payment->amount - $refundsSum;

        if($amount > $maxRefundAllowed) throw new \Exception('The maximum refund amount can only be ' . $maxRefundAllowed, StatusCodeHelper::STATUS_UNPROCESSABLE);

        $refundsSum = $refundsSum + $amount;

        if($refundsSum === $payment->amount) return CheckoutModel::STATUS_REFUNDED;

        return CheckoutModel::STATUS_PARTIALLY_REFUNDED;
    }
}
