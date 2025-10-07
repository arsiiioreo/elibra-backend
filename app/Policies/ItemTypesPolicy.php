<?php

namespace App\Policies;

use App\Models\User;

class ItemTypesPolicy
{
    /**
     * Create a new policy instance.
     */
    public function __construct()
    {
        //
    }

    public function create(User $user): bool
    {
        return $user->getIsAdminAttribute();
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user): bool
    {
        return $user->getIsAdminAttribute();
    }
}
