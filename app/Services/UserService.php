<?php

namespace App\Services;

use App\Models\UserTransaction;
use App\Responses\UserResponse;
use App\Responses\UserTransactionResponse;
use Illuminate\Http\JsonResponse;

class UserService{
    public function me(): JsonResponse
    {
        return UserResponse::success(auth()->user());
    }

    public function transaction($id, $userId): JsonResponse
    {
        if (!$transaction = UserTransaction::where("user_id", $userId)->find($id)) {
            return UserTransactionResponse::error("Transaction is not found", 400);
        }
        return UserTransactionResponse::successSingle($transaction);
    }
    public function transactions(): JsonResponse
    {
        return UserTransactionResponse::success(auth()->user()->transactions()->get());
    }

}
