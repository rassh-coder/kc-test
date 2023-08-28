<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserTransactionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            "id" => $this->id,
            "user_id" => $this->user_id,
            "product" => new ProductCollection($this->product()->get()),
            "cost" => $this->cost,
            "type" => $this->type,
            "rent_time" => $this->when($this->type == 'rent', function () {
                return $this->rent_time;
            }),
            "created_at" => $this->created_at,
            "expired_at" => $this->expired_at
        ];
    }
}
