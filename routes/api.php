<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Models\User;
use App\Events\LibrarianCreated;
use App\Http\Controllers\LibrarianPasswordResetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'librarian'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users', [UserController::class, 'index']);
    Route::put('/user/{user}', [UserController::class, 'update']);
    Route::post('/update-avatar/{user}', [UserController::class, 'updateAvatar']);
    Route::post('/create-librarian', [UserController::class, 'store']);
}
);


