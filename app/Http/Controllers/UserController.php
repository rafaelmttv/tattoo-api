<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function roles($id)
    {
        $user = User::findOrFail($id);
        return $user->roles;
    }
}