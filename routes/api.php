<?php

use App\Http\Controllers\GenreController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('/genre', GenreController::class);
Route::middleware('auth:sanctum')->group(function () {

}

);
