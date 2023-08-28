<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    public function me(UserService $service): JsonResponse
    {
        return $service->me();
    }

    public function transaction($id, UserService $service): JsonResponse
    {
        $userId = auth()->user()->id;
        return $service->transaction($userId, $id);
    }
    public function transactions(UserService $service): JsonResponse
    {
        return $service->transactions();
    }
}
