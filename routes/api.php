<?php

    use App\Http\Controllers\AuthController;
    use App\Http\Controllers\BookController;
    use App\Http\Controllers\GenreController;
    use App\Http\Controllers\UserController;
    use Illuminate\Support\Facades\Route;


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
    });
