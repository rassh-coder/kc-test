<?php

namespace App\Responses;

use App\Http\Resources\UserTransactionCollection;
use App\Http\Resources\UserTransactionResource;
use Illuminate\Http\JsonResponse;

class UserTransactionResponse extends Response
{
    public static function success($data): JsonResponse
    {
        return response()->json([
            "status" => "success",
            "data" => new UserTransactionCollection($data)
        ]);
    }

    public static function successSingle($data): JsonResponse
    {
        return response()->json([
            "status" => "success",
            "data" => new UserTransactionResource($data)
        ]);
    }
}
