<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Product;
use App\Models\User;

class ProductPolicy
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
        return $user->role === UserRole::ADMIN->value || $user->role === UserRole::MANAGER->value;
    }

    public function create(User $user)
    {
        return $user->role === UserRole::ADMIN->value;
    }

    public function update(User $user, Product $product)
    {
        return $user->role === UserRole::ADMIN->value;
    }

    public function delete(User $user, Product $product)
    {
        return $user->role === UserRole::ADMIN->value;
    }
}
