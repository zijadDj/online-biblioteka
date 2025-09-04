<?php
    use App\Http\Controllers\AuthController;
    use App\Http\Controllers\AuthorController;
    use App\Http\Controllers\BookController;
    use App\Http\Controllers\GenreController;
    use App\Http\Controllers\PublisherController;
    use App\Http\Controllers\UserController;
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\CategoryController;



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
    }
);
