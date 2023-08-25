<?php

namespace App\Responses;

use App\Http\Resources\UserResource;
use Illuminate\Http\JsonResponse;

class UserResponse extends Response
{
    public static function success($data): JsonResponse
    {
        return response()->json(
            [
                "status" => "success",
                "data" => new UserResource($data)
            ]
        );
    }
}
