<?php

use \Illuminate\Support\Facades\Route;

Route::post('bot/{token}', \App\Http\Controllers\Api\Telegram\BotController::class);

Route::post('login', \App\Http\Controllers\Api\AuthController::class . '@login');
Route::post('logout', \App\Http\Controllers\Api\AuthController::class . '@logout')->middleware('auth:api');

Route::group(['middleware' => 'auth:api'], function () {
    Route::apiResource('users', \App\Http\Controllers\Api\Admin\UsersController::class);
    Route::apiResource('testimonials', \App\Http\Controllers\Api\Admin\TestimonialsController::class)->only(['index', 'destroy']);
    Route::get('telegram-nodes/reorder', \App\Http\Controllers\Api\Admin\Telegram\NodesController::class . '@getReorderedList');
    Route::post('telegram-nodes/reorder', \App\Http\Controllers\Api\Admin\Telegram\NodesController::class . '@storeReorder');
    Route::apiResource('telegram-nodes', \App\Http\Controllers\Api\Admin\Telegram\NodesController::class);
});