<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RolePolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
    
    }

    public function checkRole(User $user, $routeName) {
        return $user->roles()->where('name', $routeName)->count() > 0;
    }
}