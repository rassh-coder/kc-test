<?php

namespace App\Responses;

use App\Http\Resources\UserProductCollection;
use App\Http\Resources\UserProductResource;
use Illuminate\Http\JsonResponse;

class UserProductResponse extends Response
{
    public static function success($data): JsonResponse
    {
        return response()->json([
            "status" => "success",
            "data" => new UserProductCollection($data)
        ]);
    }

    public static function successSingle($data): JsonResponse
    {
        return response()->json([
            "status" => "success",
            "data" => new UserProductResource($data)
        ]);
    }
}
