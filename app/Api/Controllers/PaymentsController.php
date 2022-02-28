<?php

namespace App\Api\Controllers;

use App\Api\Core\Helpers\StatusCodeHelper;
use App\Api\Core\Traits\ResponseTrait;
use App\Api\Repositories\RefundRepository;
use App\Api\Requests\CreateRefundRequest;
use App\Api\Resources\CheckoutResource;
use App\Api\Resources\PaymentResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class PaymentsController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ResponseTrait;

    protected $refundRepository;

    function __construct() {
        $this->refundRepository = new RefundRepository();
    }

    public function refund(CreateRefundRequest $request)
    {
        try {
            return $this->success('Created refund', new CheckoutResource($this->refundRepository->create($request, $request->user()->id)), StatusCodeHelper::STATUS_CREATED);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
