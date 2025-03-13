<?php

namespace App\Http\Controllers;

// use App\Models\User;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Http\Controllers\Controller; 
use App\Models\AdminICICYTA;
use App\Models\AdminICODSA;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class AdminController extends Controller
{
    // Method untuk menambahkan Admin ICODSA
    // Tambah Admin ICODSA

    public function createAdminICODSA(Request $request)
    {
        Log::info('Request data:', $request->all()); // Logging request input

        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:admin_icodsa,email',
                'password' => 'required|min:6',
            ]);

            if ($validator->fails()) {
                Log::warning('Validation failed', $validator->errors()->toArray());
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $roleICODSA = Role::where('name', 'admin_icodsa')->first();

            if (!$roleICODSA) {
                Log::error('Role admin_icodsa not found');
                return response()->json(['message' => 'Role not found'], 500);
            }

            $admin = AdminICODSA::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role_id' => $roleICODSA->id,
            ]);

            Log::info('Admin ICODSA created successfully', $admin->toArray());

            return response()->json([
                'message' => 'Admin ICODSA berhasil dibuat',
                'admin' => $admin
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating Admin ICODSA', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
        }
    }


    // Tambah Admin ICICYTA
    public function createAdminICICYTA(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admin_icicyta,email',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $role = Role::where('name', 'admin_icicyta')->first();
        if (!$role) {
            return response()->json(['error' => 'Role admin_icicyta tidak ditemukan'], 404);
        }

        $admin = AdminICICYTA::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $role->id,
        ]);

        return response()->json(['message' => 'Admin ICICYTA created successfully', 'admin' => $admin], 201);
    }



    public function listAllAdmins()
    {
        $adminsIcodsa = AdminICODSA::all();
        $adminsIcicyta = AdminICICYTA::all();

        return response()->json([
            'success' => true,
            'message' => 'Daftar Semua Admin',
            'admin_icodsa' => $adminsIcodsa,
            'admin_icicyta' => $adminsIcicyta
        ], 200);
    }


    /**
     * Menampilkan daftar Admin ICICYTA
     */
    // public function listICICYTA()
    // {
    //     $admins = AdminICICYTA::all();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Daftar Admin ICICYTA',
    //         'data' => $admins
    //     ], 200);
    // }

    // Hanya Super Admin yang bisa melihat semua admin
    // public function listAdmins()
    // {
    //     // Ambil data Admin ICODSA dan ICICYTA dari tabel berbeda
    //     $adminsIcodsa = DB::table('admin_icodsa')->get();
    //     $adminsIcicyta = DB::table('admin_icicyta')->get();

    //     return response()->json([
    //         'success' => true,
    //         'message' => 'Daftar Admin',
    //         'admin_icodsa' => $adminsIcodsa,
    //         'admin_icicyta' => $adminsIcicyta
    //     ]);
    // }

    // Dashboard Admin ICODSA
    public function icodsaDashboard()
    {
        if (Auth::user()->role !== 'admin_icodsa') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json(['message' => 'Welcome to ICODSA Dashboard']);
    }

    // Dashboard Admin ICICYTA
    public function icicytaDashboard()
    {
        if (Auth::user()->role !== 'admin_icicyta') {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        return response()->json(['message' => 'Welcome to ICICYTA Dashboard']);
    }


}

