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
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;

class CheckoutsController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests, ResponseTrait;

    protected $checkoutRepository;

    function __construct() {
        $this->checkoutRepository = new CheckoutRepository();
    }

    /**
     * Creates a checkout for the user
     *
     * @param CreateCheckoutRequest $request
     *
     * @return JsonResponse
     * @throws \Exception
     *
     */
    public function create(CreateCheckoutRequest $request)
    {
        try {
            return $this->success('Created checkout', new CheckoutResource($this->checkoutRepository->create($request, $request->user()->id)), StatusCodeHelper::STATUS_CREATED);
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Returns all checkouts
     *
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \Exception
     *
     */
    public function getAll(Request $request)
    {
        try {
            $checkouts = $this->checkoutRepository->getByUserIdPaginated($request->user()->id);
            return $this->successPaginated('Returned checkouts', CheckoutResource::collection($checkouts)->response()->getData(true));
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Returns a single checkout by id
     *
     * @param Request $request
     * @param string $checkoutId
     *
     * @return JsonResponse
     * @throws \Exception
     *
     */
    public function getById(Request $request, string $checkoutId){
        try {
            return $this->success('Returned checkout', new CheckoutResource($this->checkoutRepository->getById($request->user()->id, $checkoutId)));
        } catch (\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}
