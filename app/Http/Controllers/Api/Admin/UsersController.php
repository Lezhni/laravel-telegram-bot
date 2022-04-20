<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\User\StoreRequest;
use App\Http\Requests\Api\User\UpdateRequest;
use App\Http\Resources\User as UserResource;
use App\Http\Resources\UserCollection as UserCollectionResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Throwable;

/**
 * Class UsersController
 * @package App\Http\Controllers\Api
 */
class UsersController extends Controller
{
    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $users = Cache::remember('users', 3600 * 30, function () {
            return User::all();
        });

        $resource = new UserCollectionResource($users);
        return $resource->response();
    }

    /**
     * @param \App\Http\Requests\Api\User\StoreRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $userData = $request->validated();

        try {
            $user = new User($userData);
            $user->saveOrFail();
        } catch (Throwable $e) {
            return Response::json([
                'errors' => true,
                'message' => 'Произошла непредвиденная ошибка, попробуйте еще раз',
            ], 500);
        }

        $resource = new UserResource($user);
        return $resource->response()->setStatusCode(201);
    }

    /**
     * @param int $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(int $user)
    {
        $user = Cache::remember("users-{$user}", 3600 * 30, function () use ($user) {
            return User::find($user);
        });
        if (! $user instanceof User) {
            return Response::json([
                'errors' => true,
                'message' => 'Пользователь не найден',
            ], 404);
        }

        $resource = new UserResource($user);
        return $resource->response();
    }

    /**
     * @param \App\Http\Requests\Api\User\UpdateRequest $request
     * @param int $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(UpdateRequest $request, int $user): JsonResponse
    {
        $user = Cache::remember("users-{$user}", 3600 * 30, function () use ($user) {
            return User::find($user);
        });
        if (! $user instanceof User) {
            return Response::json([
                'errors' => true,
                'message' => 'Пользователь не найден',
            ], 404);
        }

        $newUserData = $request->validated();
        if (\Arr::has($newUserData, 'password')) {
            $newUserData['password'] = Hash::make($newUserData['password']);
        }

        try {
            $user->fill($newUserData);
            $user->saveOrFail();
        } catch (Throwable $e) {
            return Response::json([
                'errors' => true,
                'message' => 'Произошла непредвиденная ошибка, попробуйте еще раз',
            ], 500);
        }

        $resource = new UserResource($user);
        return $resource->response()->setStatusCode(204);
    }

    /**
     * @param int $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(int $user): JsonResponse
    {
        $user = User::find($user);
        if (! $user instanceof User) {
            return Response::json([
                'errors' => true,
                'message' => 'Пользователь не найден',
            ], 404);
        }

        try {
            $user->delete();
        } catch (Throwable $e) {
            return Response::json([
                'errors' => true,
                'message' => 'Произошла непредвиденная ошибка, попробуйте еще раз',
            ], 500);
        }

        $resource = new UserResource($user);
        return $resource->response()->setStatusCode(202);
    }
}
