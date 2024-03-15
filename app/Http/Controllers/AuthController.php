<?php

namespace App\Http\Controllers;

use App\Enums\AuthenticableTypeEnum;
use App\Http\Requests\Auth\AuthenticateRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        $input = $request->validated();
        $user = User::create($input)->refresh();

        return response()->json(['user' => $user], Response::HTTP_CREATED);
    }

    public function login(AuthenticateRequest $request): JsonResponse
    {
        $credentials = $request->only('email', 'password');
        $type = AuthenticableTypeEnum::from(
            $request->input('type', AuthenticableTypeEnum::User->value)
        );
        $guard = auth()->guard($this->getGuard($type));

        if (!$guard->attempt($credentials)) {
            return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
        }

        $token = $guard->user()?->createToken('authToken')->plainTextToken;

        return response()->json(['access_token' => $token], Response::HTTP_OK);
    }

    public function logout(): JsonResponse
    {
        $authenticable = auth()->user() ?? auth('admin')->user();
        $authenticable?->tokens()->delete();

        return response()->json(['message' => 'Logged out'], 200);
    }

    private function getGuard(AuthenticableTypeEnum $type): string
    {
        return match ($type) {
            AuthenticableTypeEnum::Admin => 'admin',
            AuthenticableTypeEnum::User => 'web',
        };
    }
}
