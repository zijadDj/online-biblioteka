<?php

namespace App\Events;

use App\Models\Librarian;
use App\Models\User;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class LibrarianCreated
{
    use Dispatchable, SerializesModels;
    public function __construct(public Librarian $librarian)
    {
    }
}
