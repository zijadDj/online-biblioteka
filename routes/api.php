<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\PolicyController;
use App\Http\Controllers\UserController;
use App\Http\Middleware\EnsureUserIsLibrarian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RentalController;
use App\Models\Rental;

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
    Route::get('/policies',[PolicyController::class,'index']);
    Route::get('/policies/{id}',[PolicyController::class,'show']);
    Route::post('/policies',[PolicyController::class,'store']);
    Route::put('/policies/{id}',[PolicyController::class,'update']);
    Route::delete('/policies/{id}',[PolicyController::class,'destroy']);

}

);
