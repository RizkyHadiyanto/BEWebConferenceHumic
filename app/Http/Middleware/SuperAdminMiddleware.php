<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log; 

class SuperAdminMiddleware
{
    
    public function handle(Request $request, Closure $next)
    {
        
        $user = Auth::user();

        // Debug: Cek apakah user terdeteksi
        Log::info('Middleware SuperAdmin: User logged in', ['user' => $user]);

        if (!$user) {
            return response()->json(['message' => 'Unauthorized - Not Logged In'], 401);
        }

        // // Debug: Cek role user
        if ($user->role_id !== 1) {
            Log::warning('Unauthorized access attempt', ['user_id' => $user->id, 'role_id' => $user->role_id]);
            return response()->json(['message' => 'Unauthorized - Super Admin Only'], 403);
        }

        return $next($request);

        
    }
    // public function handle(Request $request, Closure $next)
    // {
    //     if (Auth::check() && Auth::user()->role->name === 'superadmin') {
    //         return $next($request);
    //     }
    //     return response()->json(['message' => 'Unauthorized - Super Admin Only'], 403);
    // }
    // Pastikan semua user sudah login 
        // $user = Auth::user();
        // if (!Auth::check()) {
        //     return response()->json(['message' => 'Unauthorized - Not Logged In'], 401);
        // }

        // // $user = Auth::user();

        // if (!$user || !isset($user->role) || $user->role->name !== '1') {
        //     return response()->json(['message' => 'Unauthorized - Super Admin Only'], 403);
        // }

        //hanya superadmin yang bisa logout
        // $user = Auth::user(); // Ambil pengguna yang sedang login
        // dd(Auth::user());

        // if (!$user) {
        //     return response()->json(['message' => 'Unauthorized - Not Logged In'], 401);
        // }

        // if ($request->routeIs('logout')) { // Jangan cek role jika hanya logout
        //     //return $next($request);
        //     return response()->json(['user' => $request->user()]);
        // }

        // if ($user->role_id !== 1) { // Jika bukan superadmin
        //     return response()->json(['message' => 'Unauthorized - Super Admin Only'], 403);
        // }

        // if ($request->routeIs('createAdminICODSA') || $request->routeIs('createAdminICICYTA')) {
        //     return $next($request);
        // }
}
