<?php

use App\Http\Controllers\PublisherController;
use App\Http\Controllers\CategoryController;
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

Route::post('/rentals', [RentalController::class, 'store']);
Route::get('/rentals/{id}', function ($id) {
    return Rental::findOrFail($id);
});

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
    Route::get('/books/rented', [RentalController::class, 'rented']);
    Route::get('/books/returned', [RentalController::class, 'returned']);
    Route::get('/books/overdue', [RentalController::class, 'overdue']);
    Route::apiResource('books', BookController::class);
    Route::post('/books/{book}/cover', [BookController::class, 'store']);
    Route::apiResource('genres', GenreController::class);
    Route::get('/books/{book}/cover', [BookController::class, 'showCover']);
    Route::post('update-cover/{book}', [BookController::class, 'updateCover']);
    Route::delete('/books/{id}', [BookController::class, 'destroy']);
    Route::apiResource('authors', AuthorController::class);
    Route::post('author-avatar/{author}', [AuthorController::class, 'updateAvatar']);
    Route::get('/categories',[CategoryController::class, 'index']);
    Route::post('/categories',[CategoryController::class, 'store']);
    Route::get('/categories/{category}', [CategoryController::class, 'show']);
    Route::get('/categories/{category}/icon', [CategoryController::class, 'icon']);
    Route::put('/categories/{category}', [CategoryController::class, 'update']);
    Route::put('/categories/{category}/icon', [CategoryController::class, 'updateIcon']);
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy']);
    Route::get('/publishers', [PublisherController::class, 'index']);
    Route::post('/publishers', [PublisherController::class, 'store']);
    Route::get('/publishers/{publisher}', [PublisherController::class, 'show']);
    Route::put('/publishers/{publisher}', [PublisherController::class, 'update']);
    Route::delete('/publishers/{publisher}', [PublisherController::class, 'destroy']);
    Route::get('/policies',[PolicyController::class,'index']);
    Route::get('/policies/{id}',[PolicyController::class,'show']);
    Route::post('/policies',[PolicyController::class,'store']);
    Route::put('/policies/{id}',[PolicyController::class,'update']);
    Route::delete('/policies/{id}',[PolicyController::class,'destroy']);
});
