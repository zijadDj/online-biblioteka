<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    // app/Policies/UserPolicy.php

    public function create(User $user): bool
    {
        return $user->is_librarian === '1';
    }

    public function viewAny(User $user): bool
    {
        return $user->is_librarian === '1';
    }



}
