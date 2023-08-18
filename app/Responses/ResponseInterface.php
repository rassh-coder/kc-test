<?php

namespace App\Responses;

interface ResponseInterface
{
    public static function success($data): \Illuminate\Http\JsonResponse;
    public static function error($message, $code): \Illuminate\Http\JsonResponse;
}
