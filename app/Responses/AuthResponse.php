<?php

namespace App\Responses;

use Illuminate\Http\JsonResponse;

class AuthResponse extends Response implements ResponseInterface
{
    public static function success($data): JsonResponse
    {
        return response()->json([
           "status" => "success",
            "token" => $data
        ]);
    }

    public static function error($message, $code): JsonResponse
    {
        return response()->json([
            "status" => "error",
            "message" => $message
        ], $code);
    }

    public static function successWithMessage($message): JsonResponse
    {
        return response()->json([
            "status" => "success",
            "message" => $message
        ]);
    }
}
