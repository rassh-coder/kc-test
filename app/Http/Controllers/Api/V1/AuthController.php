<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;
use App\Models\User;
use App\Responses\AuthResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','registration']]);
    }

    public function registration(AuthRequest $request): JsonResponse
    {
        $request = $request->validated();
        $request['password'] = Hash::make($request['password']);

        $creds = User::create($request);

        $token = auth()->login($creds);

        return AuthResponse::success($token);
    }

    public function login(AuthRequest $request): JsonResponse
    {
        $request->validated();
        $user = User::where('username', $request->username)->first();
        if ($user && Hash::check($request->password, $user->password)) {
            $token = auth()->login($user);
            return AuthResponse::success($token);
        }

        return AuthResponse::error("Invalid credentials", 401);
    }

    public function logout(): JsonResponse
    {
        auth()->logout();
        return AuthResponse::successWithMessage("Logout successfully");
    }
}
