<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LibrarianPasswordResetController;

Route::get('/', function () {
    return view('welcome');
});

// Web rute za reset lozinke
Route::post('/librarian/request-password-reset', [LibrarianPasswordResetController::class, 'request']);
Route::post('/librarian/reset-password', [LibrarianPasswordResetController::class, 'reset']);
Route::get('/librarian/reset-password-form', [LibrarianPasswordResetController::class, 'showForm']);

// Dashboard placeholder za librarian-a
Route::get('/librarian/dashboard', function () {
    return 'Librarian dashboard placeholder';
})->middleware('auth:librarian');
