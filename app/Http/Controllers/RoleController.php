<?php

namespace App\Http\Controllers;

use App\Http\Resources\RoleResource;
use App\Models\Role;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class RoleController extends Controller
{
    /**
     * List all available roles.
     */
    public function index(): AnonymousResourceCollection
    {
        return RoleResource::collection(Role::all());
    }
}