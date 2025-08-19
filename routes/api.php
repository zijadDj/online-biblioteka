<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
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
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
    Route::post('/update-avatar/{user}', [UserController::class, 'updateAvatar']);
    Route::get('/books/{book}', [BookController::class, 'show']);
    Route::get('/books/{book}/cover', [BookController::class, 'cover']);
    Route::get('/categories',[CategoryController::class, 'index']);
    Route::post('/categories',[CategoryController::class, 'store']);
    Route::get('/categories/{category}', [CategoryController::class, 'show']);
    Route::get('/categories/{category}/icon', [CategoryController::class, 'icon']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::put('/categories/{category}/icon', [CategoryController::class, 'updateIcon']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);
}

);
