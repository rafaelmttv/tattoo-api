<?php

namespace App\Http\Middleware;

use App\Enums\RoleEnum;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * Accepts one or more role names. The user must have at least one
     * of the specified roles to proceed.
     *
     * Usage in routes: ->middleware('role:TattooArtist') or ->middleware('role:Admin,Studio')
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'message' => 'Unauthenticated.',
            ], 401);
        }

        // Load roles if not already loaded
        $user->loadMissing('roles');

        $userRoles = $user->roles->pluck('name')->toArray();

        foreach ($roles as $role) {
            if (in_array($role, $userRoles, true)) {
                return $next($request);
            }
        }

        return response()->json([
            'message' => 'You do not have the required role to perform this action.',
        ], 403);
    }
}
