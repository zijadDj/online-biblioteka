<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\LibrarianPasswordResetController;
use App\Models\User;
use App\Events\LibrarianCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);

// Ruta za kreiranje librarian-a (OL-94 pristup - kroz kontroler)
Route::post('/create-librarian', [UserController::class, 'store']);

// Rute za reset lozinke (OL-87)
Route::post('/librarian/request-password-reset', [LibrarianPasswordResetController::class, 'sendResetLinkEmail']);
Route::post('/librarian/reset-password', [LibrarianPasswordResetController::class, 'reset']);

Route::middleware(['auth:sanctum', 'librarian'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::put('/user/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
    Route::post('/update-avatar/{user}', [UserController::class, 'updateAvatar']);
});
