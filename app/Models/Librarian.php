<?php

namespace App\Models;

use App\Events\LibrarianCreated;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Notifications\LibrarianResetPasswordNotification;

class Librarian extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $dispatchesEvents = [
        'created' => LibrarianCreated::class,
    ];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new LibrarianResetPasswordNotification($token));
    }
}

