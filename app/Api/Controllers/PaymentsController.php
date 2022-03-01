<?php

namespace App\Api\Controllers;

use App\Api\Core\Helpers\StatusCodeHelper;
use App\Api\Core\Traits\ResponseTrait;
use App\Api\Repositories\RefundRepository;
use App\Api\Requests\CreateRefundRequest;
use App\Api\Resources\CheckoutResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class PaymentsController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ResponseTrait;

    protected $refundRepository;

    function __construct() {
        $this->refundRepository = new RefundRepository();
    }

    /**
     * Creates a refund for an existing payment
     *
     * @param CreateRefundRequest $request
     *
     * @return JsonResponse
     * @throws \Exception
     *
     */
    public function refund(CreateRefundRequest $request)
    {
        try {
            return $this->success('Created refund', new CheckoutResource($this->refundRepository->create($request, $request->user()->id)), StatusCodeHelper::STATUS_CREATED);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
