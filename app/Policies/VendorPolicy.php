<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;

class VendorPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function viewAny(User $user)
    {
        return $user->role === UserRole::ADMIN;
    }

    public function create(User $user)
    {
        return $user->role === UserRole::ADMIN;
    }

    public function update(User $user)
    {
        return $user->role === UserRole::ADMIN;
    }

    public function delete(User $user)
    {
        return $user->role === UserRole::ADMIN;
    }
}
