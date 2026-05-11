<?php

namespace App\Policies;

use App\Models\Studio;
use App\Models\User;

class StudioPolicy
{
    /**
     * Anyone can view studios.
     */
    public function view(?User $user, Studio $studio): bool
    {
        return true;
    }

    /**
     * Any authenticated user can create a studio.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Only the studio owner can update it.
     */
    public function update(User $user, Studio $studio): bool
    {
        return $user->id === $studio->user_id;
    }

    /**
     * Only the studio owner can delete it.
     */
    public function delete(User $user, Studio $studio): bool
    {
        return $user->id === $studio->user_id;
    }
}
