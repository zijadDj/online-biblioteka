<?php

    use App\Http\Controllers\AuthController;
    use App\Http\Controllers\AuthorController;
    use App\Http\Controllers\GenreController;
    use App\Http\Controllers\UserController;
    use Illuminate\Support\Facades\Route;

    Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum', 'librarian'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users', [UserController::class, 'index']);
    Route::put('/user/{user}', [UserController::class, 'update']);
    Route::delete('/users/{user}', [UserController::class, 'destroy']);
    Route::post('/update-avatar/{user}', [UserController::class, 'updateAvatar']);
    Route::apiResource('/genres', GenreController::class);
    Route::apiResource('/authors', AuthorController::class);
    Route::post('/authors/update-image/{author}', [AuthorController::class, 'updateAvatar']);
}
);
