<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use App\Models\Librarian;
use App\Mail\ResetPasswordMail;
use App\Http\Controllers\LibrarianPasswordResetController;

Route::post('/librarian/request-password-reset', [LibrarianPasswordResetController::class, 'request']);
Route::post('/librarian/reset-password', [LibrarianPasswordResetController::class, 'reset']);

Route::get('/', function () {
    return view('welcome');
});
