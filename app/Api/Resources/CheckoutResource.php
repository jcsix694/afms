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
        if(!$this->payment){
            $refund = null;
        } else {
            if(sizeof($this->payment->refund) > 0){
                $refund = RefundResource::collection($this->payment->refund);
            } else {
                $refund = null;
            }
        }

        return [
            'id' => $this->checkout_id,
            'uuid' => $this->uuid,
            'amount' => $this->amount,
            'reference' => $this->reference,
            'status' => $this->status,
            'payment' => new PaymentResource($this->payment),
            'refunds' => $refund,
        ];
    }
}
