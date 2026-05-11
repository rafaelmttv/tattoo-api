<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoleResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UserController extends Controller
{
    /**
     * List the roles for a given user.
     */
    public function roles(int $id): AnonymousResourceCollection
    {
        $user = User::findOrFail($id);

        return RoleResource::collection($user->roles);
    }
}