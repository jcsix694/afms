<?php

namespace App\Api\Resources;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use JsonSerializable;

class UserResource extends JsonResource
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
            'uuid' => $this->uuid,
            'email' => $this->email,
            'name' => $this->name,
            'surname' => $this->surname,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'accountType' => new AccountTypeResource($this->userAccount->type)
        ];
    }
}
