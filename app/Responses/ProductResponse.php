<?php

namespace App\Responses;

use App\Http\Resources\ProductCollection;
use App\Http\Resources\ProductResource;
use Illuminate\Http\JsonResponse;

class ProductResponse extends Response
{
    public static function success($data): JsonResponse
    {
        return response()->json([
            "status" => "success",
            "data" =>  new ProductCollection($data)
        ]);
    }

    public static function successSingle($data): JsonResponse
    {
        return response()->json([
            "status" => "success",
            "data" => new ProductResource($data)
        ]);
    }
}
