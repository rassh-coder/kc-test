<?php

namespace App\Responses;

use App\Responses\Contracts\ResponseInterface;
use Illuminate\Http\JsonResponse;

class Response implements ResponseInterface
{
    public static function success($data): JsonResponse
    {
        return response()->json([
            "status" => "success",
            "data" => $data
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
