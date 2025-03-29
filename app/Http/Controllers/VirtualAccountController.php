<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VirtualAccount;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VirtualAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    // ✅ List semua virtual account (hanya bisa dilihat oleh Super Admin, Admin ICODSA, Admin ICICYTA)
    public function index()
    {
        $user = Auth::user();
        if (!$user || !in_array($user->role_id, [1, 2, 3])) {
            return response()->json(['message' => 'Unauthorized - Access Denied'], 403);
        }

        return response()->json(VirtualAccount::all(), 200);
    }

    // ✅ Buat virtual account baru (Hanya bisa dilakukan oleh Super Admin)
    public function createVirtualAccount(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user || $user->role_id !== 1) {
                return response()->json(['message' => 'Unauthorized - Super Admin Only'], 403);
            }

            // ✅ Validasi input
            $validator = Validator::make($request->all(), [
                'nomor_virtual_akun' => 'required|string|unique:virtual_accounts,nomor_virtual_akun',
                'account_holder_name' => 'required|string',
                'bank_name' => 'required|string',
                'bank_branch' => 'required|string',
            ]);

            if ($validator->fails()) {
                Log::warning('Validation failed', ['errors' => $validator->errors()]);
                return response()->json(['errors' => $validator->errors()], 400);
            }

            // ✅ Buat Virtual Account
            $virtualAccount = VirtualAccount::create([
                'nomor_virtual_akun' => $request->nomor_virtual_akun,
                'account_holder_name' => $request->account_holder_name,
                'bank_name' => $request->bank_name,
                'bank_branch' => $request->bank_branch,
                'created_by' => $user->id, // Tambahkan pencatat Super Admin yang membuat
            ]);

            Log::info('Virtual Account created successfully', ['virtual_account' => $virtualAccount]);

            return response()->json([
                'message' => 'Virtual Account created successfully',
                'virtual_account' => $virtualAccount
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating Virtual Account', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ✅ Lihat virtual account berdasarkan ID
    public function show($id)
    {
        $virtualAccount = VirtualAccount::find($id);

        if (!$virtualAccount) {
            return response()->json(['message' => 'Virtual Account not found'], 404);
        }

        return response()->json($virtualAccount, 200);
    }

    // ✅ Update virtual account (Hanya bisa dilakukan oleh Super Admin)
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user || $user->role_id !== 1) {
            return response()->json(['message' => 'Unauthorized - Super Admin Only'], 403);
        }

        $virtualAccount = VirtualAccount::find($id);

        if (!$virtualAccount) {
            return response()->json(['message' => 'Virtual Account not found'], 404);
        }

        // ✅ Validasi input sebelum update
        $validator = Validator::make($request->all(), [
            'nomor_virtual_akun' => 'sometimes|string|unique:virtual_accounts,nomor_virtual_akun,'.$id,
            'account_holder_name' => 'sometimes|string',
            'bank_name' => 'sometimes|string',
            'bank_branch' => 'sometimes|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $virtualAccount->update($request->all());

        return response()->json(['message' => 'Virtual Account updated successfully', 'virtual_account' => $virtualAccount], 200);
    }

    // ✅ Hapus virtual account (Hanya bisa dilakukan oleh Super Admin)
    public function destroy($id)
    {
        $user = Auth::user();
        if (!$user || $user->role_id !== 1) {
            return response()->json(['message' => 'Unauthorized - Super Admin Only'], 403);
        }

        $virtualAccount = VirtualAccount::find($id);

        if (!$virtualAccount) {
            return response()->json(['message' => 'Virtual Account not found'], 404);
        }

        $virtualAccount->delete();

        return response()->json(['message' => 'Virtual Account deleted successfully'], 200);
    }
}
