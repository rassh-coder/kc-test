<?php

namespace App\Services;

use App\Responses\UserResponse;
use Illuminate\Http\JsonResponse;

class UserService{
    public function me(): JsonResponse
    {
        return UserResponse::success(auth()->user());
    }
}
