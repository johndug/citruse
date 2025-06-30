<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user)
    {
        return $user->role === UserRole::ADMIN->value || $user->role === UserRole::MANAGER->value;
    }

    public function create(User $user)
    {
        return $user->role === UserRole::ADMIN->value;
    }

    public function update(User $user, User $model)
    {
        return $user->role === UserRole::ADMIN->value;
    }

    public function delete(User $user, User $model)
    {
        return $user->role === UserRole::ADMIN->value;
    }
}