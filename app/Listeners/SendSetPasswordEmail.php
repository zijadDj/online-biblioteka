<?php

namespace App\Listeners;

use App\Events\LibrarianCreated;
use Illuminate\Support\Facades\Password;

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
        $user = $event->librarian;

        if (!$user || !$user->is_librarian) {
            return;
        }
        Password::sendResetLink(['email' => $user->email]);
    }
}
