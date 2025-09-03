<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LibrarianCreated
{
    use Dispatchable, SerializesModels;

    public $user;

    public function __construct(public User $librarian)
    {
        $this->user = $librarian;
    }
}
