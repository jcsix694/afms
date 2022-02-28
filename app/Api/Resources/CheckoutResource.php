<?php

namespace App\Api\Resources;

use App\Api\Repositories\RefundRepository;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class CheckoutResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->checkout_id,
            'uuid' => $this->uuid,
            'amount' => $this->amount,
            'reference' => $this->reference,
            'status' => $this->status,
            'payment' => new PaymentResource($this->payment),
            'refunds' => RefundResource::collection($this->payment->refund),
        ];
    }
}
