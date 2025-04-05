<?php

namespace App\Http\Controllers;


use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

use Illuminate\Routing\Controller;

use App\Models\Role;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Middleware\SuperAdminMiddleware;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware(SuperAdminMiddleware::class)->only(['createAdminICODSA', 'createAdminICICYTA']);
    }
    
    public function createAdminICODSA(Request $request)
    {
        Log::info('Request data:', $request->all()); 

        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'username' => 'required|string|unique:users,username',
                'email' => 'required|email|unique:users,email',
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

            $admin = User::create([
                'name' => $request->name,
                'username' => $request->username,
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

    public function createAdminICICYTA(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'username' => 'required|string|unique:users,username',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $role = Role::where('name', 'admin_icicyta')->first();
        if (!$role) {
            return response()->json(['error' => 'Role admin_icicyta tidak ditemukan'], 404);
        }

        $admin = User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role_id' => $role->id,
        ]);

        return response()->json(['message' => 'Admin ICICYTA created successfully', 'admin' => $admin], 201);
    }
    public function updateAdmin(Request $request, $id)
    {
        Log::info("Update request received for Admin ID: $id", $request->all());

        try {
            $admin = User::find($id);

            if (!$admin) {
                return response()->json(['message' => 'Admin not found'], 404);
            }

            if (!in_array($admin->role->name, ['admin_icodsa', 'admin_icicyta'])) {
                return response()->json(['message' => 'Unauthorized - Only Admin ICODSA or ICICYTA can be updated'], 403);
            }

            $validator = Validator::make($request->all(), [
                'name' => 'sometimes|string|max:255',
                'username' => 'sometimes|string|unique:users,username,' . $id,
                'email' => 'sometimes|email|unique:users,email,' . $id,
                'password' => 'sometimes|min:6',
                'role_id' => 'sometimes|exists:roles,id',
            ]);

            if ($validator->fails()) {
                Log::warning("Validation failed", $validator->errors()->toArray());
                return response()->json(['errors' => $validator->errors()], 400);
            }

            if ($request->has('name')) {
                $admin->name = $request->name;
            }
            if ($request->has('username')) {
                $admin->username = $request->username;
            }
            if ($request->has('email')) {
                $admin->email = $request->email;
            }
            if ($request->has('password')) {
                $admin->password = Hash::make($request->password);
            }
            if ($request->has('role_id')) {
                $admin->role_id = $request->role_id;
            }

            $admin->save();

            Log::info("Admin updated successfully", $admin->toArray());

            return response()->json([
                'message' => 'Admin updated successfully',
                'admin' => $admin
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error updating admin', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
        }
    }
    
    public function deleteAdmin($id)
    {
        Log::info("Delete request received for Admin ID: $id");

        try {
            $admin = User::find($id);

            if (!$admin) {
                return response()->json(['message' => 'Admin not found'], 404);
            }

            // Pastikan hanya Admin ICODSA dan Admin ICICYTA yang bisa dihapus
            if (!in_array($admin->role->name, ['admin_icodsa', 'admin_icicyta'])) {
                return response()->json(['message' => 'Unauthorized - Only Admin ICODSA or ICICYTA can be deleted'], 403);
            }

            $admin->delete();

            Log::info("Admin deleted successfully", ['admin_id' => $id]);

            return response()->json([
                'message' => 'Admin deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting admin', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
        }
    }

    public function listAllAdmins()
    {
        // $adminsIcodsa = User::all();
        // $adminsIcicyta = User::all();
        $admin = User::all();

        return response()->json([
            'success' => true,
            'message' => 'Daftar Semua Admin',
            'admin' => $admin,
            // 'admin_icicyta' => $adminsIcicyta
        ], 200);
    }

    public function listAdminsICODSA()
    {
        $adminsIcodsa = User::whereHas('role', function ($query) {
            $query->where('name', 'admin_icodsa');
        })->get();
    
        return response()->json([
            'success' => true,
            'message' => 'Daftar Semua Admin ICODSA',
            'admin_icodsa' => $adminsIcodsa,
        ], 200);
    }

    public function listAdminsICICYTA()
    {
        $adminsIcicyta = User::whereHas('role', function ($query) {
            $query->where('name', 'admin_icicyta');
        })->get();

        return response()->json([
            'success' => true,
            'message' => 'Daftar Semua Admin ICICYTA',
            'admin_icicyta' => $adminsIcicyta,
        ], 200);
    }


}

