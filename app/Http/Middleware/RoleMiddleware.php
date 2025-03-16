<?php

// namespace App\Http\Middleware;

// use Closure;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;

// class RoleMiddleware
// {
//     public function handle(Request $request, Closure $next, ...$roles)
//     {
//         // Cek apakah pengguna sudah login
//         if (!Auth::check()) {
//             return response()->json(['message' => 'Unauthorized - Not Logged In'], 401);
//         }

//         // Ambil peran pengguna
//         $userRole = Auth::user()->role;

//         // Jika user adalah superadmin, biarkan akses semua
//         if ($userRole === 'superadmin') {
//             return $next($request);
//         }

//         // Jika bukan superadmin, cek apakah memiliki izin
//         if (!in_array($userRole, $roles)) {
//             return response()->json(['message' => 'Unauthorized - Access Denied'], 403);
//         }

//         return $next($request);

        

//         if (!$user || !in_array($user->role->name, $roles)) {
//             return response()->json(['message' => 'Unauthorized'], 403);
//         }

//         return $next($request);
//     }
// }


namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    // public function handle(Request $request, Closure $next, ...$roles)
    // {
        
    //     // Cek apakah pengguna sudah login
    //     if (!Auth::check()) {
    //         // return response()->json(['message' => 'Unauthorized - Not Logged In'], 401);
    //         dd($request->user());
    //     }

    //     // Ambil pengguna yang sedang login
    //     //$user = Auth::user()->role->name ?? null; // Pastikan nama role tersedia
    //     $user = Auth::user()->role_id;

    //     // Pastikan user memiliki peran (role)
    //     if (!$user->role) {
    //         return response()->json(['message' => 'Unauthorized - No Role Assigned'], 403);
    //     }

    //     // Jika user adalah superadmin, izinkan akses tanpa batas
    //     if ($user == 1) { // Super Admin bisa akses semua
    //         return $next($request);
    //     }

    //     // Jika bukan superadmin, periksa apakah role user termasuk dalam daftar yang diizinkan
    //     if (!in_array($user->role->name, $roles)) {
    //         return response()->json(['message' => 'Unauthorized - Access Denied'], 403);
    //     }

    //     return $next($request);
    // }
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

