<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\AuthRequest;
use App\Http\Resources\User as UserResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class AuthController extends Controller
{
    public function login(AuthRequest $request): JsonResponse
    {
        $loginData = $request->only(['email', 'password']);

        if (! Auth::attempt($loginData, true)) {
            return Response::json([
                'errors' => true,
                'message' => 'Неправильный email или пароль',
            ], 403);
        }

        $user = Auth::user();
        $token = $user->createToken('auth')->accessToken;

        $resource = new UserResource($user);
        $resource = $resource->additional(['access_token' => $token]);
        return $resource->response();
    }

    public function logout(): JsonResponse
    {
        Auth::user()->token()->revoke();
        return Response::json(['errors' => false, 'data' => []]);
    }
}
