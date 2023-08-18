<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{

    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'image' => $this->image,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'count' => $this->count,
            'rent_4' => $this->rent_4,
            'rent_8' => $this->rent_8,
            'rent_12' => $this->rent_12,
            'rent_24' => $this->rent_24
        ];
    }
}
