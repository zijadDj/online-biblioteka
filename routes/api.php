<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserContoller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/user/{user}', [UserContoller::class, 'update']);
    Route::post('/update-avatar/{user}', [UserContoller::class, 'updateAvatar']);
}

);
