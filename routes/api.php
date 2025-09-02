<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureUserIsLibrarian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RentalController;
use App\Models\Rental;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\GenreController;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'librarian'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{user}', [UserController::class, 'show']);
    Route::put('/user/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
    Route::post('/update-avatar/{user}', [UserController::class, 'updateAvatar']);
    Route::apiResource('books', BookController::class);
    Route::post('/books/{book}/cover', [BookController::class, 'store']);
    Route::apiResource('genres', GenreController::class);
    Route::get('/books/{book}/cover', [BookController::class, 'showCover']);
    Route::post('update-cover/{book}', [BookController::class, 'updateCover']);
    Route::apiResource('authors', AuthorController::class);
    Route::post('author-avatar/{author}', [AuthorController::class, 'updateAvatar']);
    Route::get('/rentals/{rental}', [RentalController::class, 'show']);
    Route::post('/rentals', [RentalController::class, 'store']);
    Route::post('/rentals/{rental}/return', [RentalController::class, 'returnBook']);
});

