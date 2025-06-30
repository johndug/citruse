<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\PurchaseOrder;
use App\Models\User;

class PurchaseOrderPolicy
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
        return $user->role === UserRole::MANAGER->value || $user->role === UserRole::ADMIN->value;
    }

    public function create(User $user)
    {
        return $user->role === UserRole::MANAGER->value || $user->role === UserRole::ADMIN->value;
    }

    public function update(User $user, PurchaseOrder $purchaseOrder)
    {
        return $user->role === UserRole::ADMIN->value;
    }

    public function delete(User $user, PurchaseOrder $purchaseOrder)
    {
        return $user->role === UserRole::ADMIN->value;
    }
}
