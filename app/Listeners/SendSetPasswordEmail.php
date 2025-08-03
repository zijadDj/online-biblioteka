<?php

namespace App\Listeners;

use App\Events\LibrarianCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;

class SendSetPasswordEmail
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(LibrarianCreated $event): void
    {
        $user=$event->librarian;
        
        $token= Password::createToken($user);
        $url=url("set-password?token={$token}&email={$user->email}");
        Mail::to($user->email)->send(new \App\Mail\SetPasswordMail($user, $url));
    }
}
