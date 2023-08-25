<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class UserProductResource extends JsonResource
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
            "product" => new ProductResource($this->product()->first(), true),
            "expired_at" => $this->expired_at,
            "is_rent" => $this->is_rent,
            "slug"=> $this->slug
        ];
    }
}
