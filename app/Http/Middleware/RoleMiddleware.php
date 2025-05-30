<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'Unauthorized - Not Logged In'], 401);
        }

        $user = Auth::user();

        if (!$user->role_id) {
            return response()->json(['message' => 'Unauthorized - No Role Assigned'], 403);
        }

        if ($user->role_id == 1) {
            return $next($request); // Super Admin bisa akses semua
        }

        if (!in_array($user->role_id, $roles)) {
            return response()->json(['message' => 'Unauthorized - Access Denied'], 403);
        }

        return $next($request);
    }
}

