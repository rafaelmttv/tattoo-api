<?php

namespace App\Policies;

use App\Models\StudioService;
use App\Models\User;

class StudioServicePolicy
{
    /**
     * Anyone can view studio services.
     */
    public function view(?User $user, StudioService $service): bool
    {
        return true;
    }

    /**
     * Only users with a studio can create studio services.
     */
    public function create(User $user): bool
    {
        return $user->studio !== null;
    }

    /**
     * Only the studio owner can update their services.
     */
    public function update(User $user, StudioService $service): bool
    {
        return $user->studio
            && $user->studio->id === $service->provider_id;
    }

    /**
     * Only the studio owner can delete their services.
     */
    public function delete(User $user, StudioService $service): bool
    {
        return $user->studio
            && $user->studio->id === $service->provider_id;
    }
}
