<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use App\Models\User;
// use App\Models\AdminICODSA;
// use App\Models\AdminICICYTA;

class AuthController extends Controller
{

    // public function login(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required'
    //     ]);

    //     // Cek pengguna di tiga tabel
    //     $user = User::where('email', $request->email)->first();
    //     $adminICODSA = User::where('email', $request->email)->first();
    //     $adminICICYTA = User::where('email', $request->email)->first();

    //     // Tentukan pengguna yang valid
    //     if ($user && Hash::check($request->password, $user->password)) {
    //         $role =  $user->role_id;
    //         $token = $user->createToken('authToken')->plainTextToken;
    //     } elseif ($adminICODSA && Hash::check($request->password, $adminICODSA->password)) {
    //         $role = $user->role_id ;
    //         $token = $adminICODSA->createToken('authToken')->plainTextToken;
    //         $user = $adminICODSA;
    //     } elseif ($adminICICYTA && Hash::check($request->password, $adminICICYTA->password)) {
    //         $role =  $user->role_id;
    //         $token = $adminICICYTA->createToken('authToken')->plainTextToken;
    //         $user = $adminICICYTA;
    //     } else {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Email atau password salah'
    //         ], 401);
    //     }

    //     // Response berhasil dengan informasi pengguna
    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Login berhasil',
    //         'user' => [
    //             'id' => $user->id,
    //             'name' => $user->name,
    //             'email' => $user->email,
    //             'role' => $role
    //         ],
    //         'token' => $token,
    //         'token_type' => 'Bearer',
    //         // 'redirect_url' => $this->getRedirectUrl($role) // Redirect sesuai role
    //     ], 200);
    // }
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // Cek pengguna berdasarkan email
        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah'
            ], 401);
        }

        // Ambil role ID
        $role = $user->role_id;
        $token = $user->createToken('authToken')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login berhasil',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $role
            ],
            'token' => $token,
            'token_type' => 'Bearer',
        ], 200);
    }

    // private function getRedirectUrl($role)
    // {
    //     switch ($role) {
    //         case 'superadmin':
    //             return '1';
    //         case '2':
    //             return '/dashboard/icodsa';
    //         case '3':
    //             return '/dashboard/icicyta';
    //         default:
    //             return '/';
    //     }
    // }

/**
 * Handle user logout
 */
    // public function logout(Request $request)
    // {
        
    //     try {
    //         if (!Auth::check()) {
    //             return response()->json(['message' => 'User not authenticated'], 401);
    //         }

    //         // Ambil pengguna yang sedang login
    //         $user = Auth::user();

    //         // Hapus token jika menggunakan Laravel Sanctum
    //         // if (method_exists($user, 'tokens')) {
    //         //     $user->tokens()->delete(); // Hapus semua token pengguna
    //         // }

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Logout berhasil'
    //         ], 200);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Logout gagal',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }
    public function logoutsuperadmin(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $user = Auth::user();
        //$user->tokens()->delete(); // Hapus semua token pengguna

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ], 200);
    }
    public function logout(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }

        $user = Auth::user();
        //$user->tokens()->delete(); // Hapus semua token pengguna

        return response()->json([
            'success' => true,
            'message' => 'Logout berhasil'
        ], 200);
    }
}
// public function login(Request $request)
    // {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required'
    //     ]);

    //     // Cek pengguna dari tabel yang berbeda
    //     $user = User::where('email', $request->email)->first();
    //     $adminICODSA = AdminICODSA::where('email', $request->email)->first();
    //     $adminICICYTA = AdminICICYTA::where('email', $request->email)->first();

    //     // Tentukan pengguna yang valid
    //     if ($user && Hash::check($request->password, $user->password)) {
    //         $role = 'superadmin';
    //         $token = $user->createToken('authToken')->plainTextToken;
    //     } elseif ($adminICODSA && Hash::check($request->password, $adminICODSA->password)) {
    //         $role = 'admin_icodsa';
    //         $token = $adminICODSA->createToken('authToken')->plainTextToken;
    //         $user = $adminICODSA;
    //     } elseif ($adminICICYTA && Hash::check($request->password, $adminICICYTA->password)) {
    //         $role = 'admin_icicyta';
    //         $token = $adminICICYTA->createToken('authToken')->plainTextToken;
    //         $user = $adminICICYTA;
    //     } else {
    //         return response()->json([
    //             'success' => false,
    //             'message' => 'Email atau password salah'
    //         ], 401);
    //     }

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Login berhasil',
    //         'access_token' => $token,
    //         'token_type' => 'Bearer',
    //         'user' => [
    //             'id' => $user->id,
    //             'email' => $user->email,
    //             'name' => $user->name,
    //             'role' => $role
    //         ]
    //     ], 200);
    // }