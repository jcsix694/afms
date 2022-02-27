<?php

namespace App\Api\Controllers;

use App\Api\Core\Helpers\StatusCodeHelper;
use App\Api\Core\Traits\ResponseTrait;
use App\Api\Repositories\CheckoutRepository;
use App\Api\Requests\CreateCheckoutRequest;
use App\Api\Resources\CheckoutResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class CheckoutsController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ResponseTrait;

    protected $checkoutRepository;

    function __construct() {
        $this->checkoutRepository = new CheckoutRepository();
    }

    public function create(CreateCheckoutRequest $request)
    {
        try {
            return $this->success('Created checkout', new CheckoutResource($this->checkoutRepository->create($request, $request->user()->id)), StatusCodeHelper::STATUS_CREATED);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function get(Request $request)
    {
        try {
            $checkouts = $this->checkoutRepository->getByUserIdPaginated($request->user()->id);
            return $this->successPaginated('Returned checkouts', CheckoutResource::collection($checkouts)->response()->getData(true));
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
