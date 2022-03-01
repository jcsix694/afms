<?php

namespace App\Api\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class PaymentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  Request  $request
     * @return array|Arrayable|JsonSerializable
     */
    public function toArray($request)
    {
        $response = json_decode($this->response);

        return [
            'id' => $this->payment_id,
            'uuid' => $this->uuid,
            'amount' => number_format($response->amount, 2, '.', ','),
            'currency' => $response->currency,
            'code' => $response->result->code,
            'description' => $response->result->description,
            'completedAt' => $this->completed_at
        ];
    }
}
