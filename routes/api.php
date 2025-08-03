<?php

use App\Http\Controllers\AuthController;
use App\Models\User;
use App\Events\LibrarianCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/login', [AuthController::class,'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout',[AuthController::class, 'logout']);
} 
);

Route::post('/create-librarian', function (Request $request) {
    $user = User::create([
        'name' => $request->input('name'),
        'surname'=> $request->input('surname')?? '',
        'email' => $request->input('email'),
        'password' => bcrypt($request->input('password')) ?? '',
        'photo_path' => $request->input('photo_path') ?? '',
        'jmbg' => $request->input('jmbg') ?? random_int(1, 999),
    ]);
    event(new LibrarianCreated($user));
    return response()->json(['message' => 'Librarian created and event fired.']);
    //Send a POST request to /api/create-librarian with JSON body:
    // "name": "New Librarian",
    // "email": "librarian@example.com",
});
