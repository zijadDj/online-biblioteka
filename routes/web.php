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

Route::get('/librarian/reset-password', function () {
    return view('librarian.reset-password');
});

Route::get('/librarian/dashboard', function () {
    return 'Librarian dashboard placeholder';
})->middleware('auth:librarian');

// Privremena test ruta za reset lozinke
Route::get('/test-reset', function () {
    $email = 'kaprisvuk@gmail.com'; // pravi mail iz baze

    $librarian = Librarian::where('email', $email)->first();

    if (! $librarian) {
        return 'Librarian not found.';
    }

    $token = Password::createToken($librarian);

    $resetUrl = url("/librarian/reset-password?token={$token}&email={$email}");

    Mail::to($email)->send(new ResetPasswordMail($resetUrl));

    return 'Reset email sent.';
});
