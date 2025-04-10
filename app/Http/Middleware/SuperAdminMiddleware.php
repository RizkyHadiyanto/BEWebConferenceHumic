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

        // if ($user->role_id !== 1) {
        //     return redirect('/dashboard/icodsa');
        // }

        return $next($request);

        
    }

}
