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
// use App\Http\Controllers\Controller; 
use Illuminate\Routing\Controller;

use App\Models\Role;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use App\Http\Middleware\SuperAdminMiddleware;

// use App\Models\AdminICICYTA;
// use App\Models\AdminICODSA;
// use App\Models\LOA;
// use App\Models\Signature;
// use App\Models\BankTransfer;
// use App\Models\VirtualAccount;
// use App\Models\Invoice;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        // $this->middleware(SuperAdminMiddleware::class);
        $this->middleware(SuperAdminMiddleware::class)->only(['createAdminICODSA', 'createAdminICICYTA']);
    }
    
    public function createAdminICODSA(Request $request)
    {
        Log::info('Request data:', $request->all()); // Logging request input

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
            
            // Ambil Role Admin ICODSA
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


    // Tambah Admin ICICYTA
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

            // Pastikan admin yang di-update adalah Admin ICODSA atau Admin ICICYTA
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



    // LIST Admin ICODSA dan ICICYTA dengan SUPER ADMIN
    public function listAllAdmins()
    {
        $adminsIcodsa = User::all();
        $adminsIcicyta = User::all();

        return response()->json([
            'success' => true,
            'message' => 'Daftar Semua Admin',
            'admin_icodsa' => $adminsIcodsa,
            'admin_icicyta' => $adminsIcicyta
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

    // Tambah Signature
    // public function createSignature(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'picture' => 'required|image|mimes:jpg,png,jpeg|max:2048',
    //         'nama_penandatangan' => 'required|string',
    //         'jabatan_penandatangan' => 'required|string',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 400);
    //     }

    //     $path = $request->file('picture')->store('signatures', 'public');

    //     $signature = Signature::create([
    //         'picture' => $path,
    //         'nama_penandatangan' => $request->nama_penandatangan,
    //         'jabatan_penandatangan' => $request->jabatan_penandatangan,
    //     ]);

    //     return response()->json(['message' => 'Signature created successfully', 'signature' => $signature], 201);
    // }

    // // Tambah Bank Transfer
    // public function createBankTransfer(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'nama_bank' => 'required|string',
    //         'swift_code' => 'nullable|string',
    //         'recipient_name' => 'required|string',
    //         'beneficiary_bank_account_no' => 'required|string',
    //         'bank_branch' => 'required|string',
    //         'bank_address' => 'nullable|string',
    //         'city' => 'nullable|string',
    //         'country' => 'required|string',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 400);
    //     }

    //     $bankTransfer = BankTransfer::create($request->all());

    //     return response()->json(['message' => 'Bank Transfer created successfully', 'bank_transfer' => $bankTransfer], 201);
    // }

    // // Tambah Virtual Account
    // public function createVirtualAccount(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'nomor_virtual_akun' => 'required|string|unique:virtual_accounts',
    //         'account_holder_name' => 'required|string',
    //         'bank_name' => 'required|string',
    //         'bank_branch' => 'required|string',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 400);
    //     }

    //     $virtualAccount = VirtualAccount::create($request->all());

    //     return response()->json(['message' => 'Virtual Account created successfully', 'virtual_account' => $virtualAccount], 201);
    // }

    // Tambah LOA
    // public function createLOA(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'paper_id' => 'required|string|unique:loas',
    //         'paper_title' => 'required|string',
    //         'author_names' => 'required|array|min:1|max:5',
    //         'status' => 'required|in:Accepted,Rejected',
    //         'tempat_tanggal' => 'required|string',
    //         'signature_id' => 'required|exists:signatures,id',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 400);
    //     }

    //     $loa = LOA::create($request->all());

    //     return response()->json(['message' => 'LOA created successfully', 'loa' => $loa], 201);
    // }

    // Tambah Invoice
    // public function createInvoice(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'loa_id' => 'required|exists:loas,id',
    //         'institution' => 'required|string',
    //         'email' => 'required|email',
    //         'tempat_tanggal' => 'required|string',
    //         'virtual_account_id' => 'required|exists:virtual_accounts,id',
    //         'bank_transfer_id' => 'required|exists:bank_transfers,id',
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 400);
    //     }

    //     $invoice = Invoice::create($request->all());

    //     return response()->json(['message' => 'Invoice created successfully', 'invoice' => $invoice], 201);
    // }

    


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
    // public function icodsaDashboard()
    // {
    //     if (Auth::user()->role !== 'admin_icodsa') {
    //         return response()->json(['message' => 'Unauthorized'], 403);
    //     }

    //     return response()->json(['message' => 'Welcome to ICODSA Dashboard']);
    // }

    // // Dashboard Admin ICICYTA
    // public function icicytaDashboard()
    // {
    //     if (Auth::user()->role !== 'admin_icicyta') {
    //         return response()->json(['message' => 'Unauthorized'], 403);
    //     }

    //     return response()->json(['message' => 'Welcome to ICICYTA Dashboard']);
    // }


}

