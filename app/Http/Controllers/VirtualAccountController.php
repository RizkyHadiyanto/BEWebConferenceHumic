<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models\VirtualAccount;
// use Illuminate\Support\Str;

// class VirtualAccountController extends Controller
// {
//     public function store(Request $request)
//     {
//         $request->validate([
//             'nomor_virtual_akun' => 'required|unique:virtual_accounts',
//             'account_holder_name' => 'required|string',
//             'bank_name' => 'required|string',
//             'bank_branch' => 'required|string',
//         ]);

//         $virtualAccount = VirtualAccount::create([
//             'nomor_virtual_akun' => $request->nomor_virtual_akun,
//             'account_holder_name' => $request->account_holder_name,
//             'bank_name' => $request->bank_name,
//             'bank_branch' => $request->bank_branch,
//             'token' => Str::uuid(), // Generate Token
//         ]);

//         // Generate API Token
//         $token = $virtualAccount->createToken('VirtualAccountToken')->plainTextToken;

//         return response()->json([
//             'success' => true,
//             'message' => 'Virtual Account berhasil dibuat!',
//             'data' => $virtualAccount,
//             'access_token' => $token
//         ], 201);
//     }
// }

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VirtualAccount;
use Illuminate\Support\Facades\Log;
use App\Http\Middleware\SuperAdminMiddleware;
use Illuminate\Routing\Controller;


class VirtualAccountController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('superadmin');
    }

    // List semua virtual account
    public function index()
    {
        return response()->json(VirtualAccount::all(), 200);
    }

    // Buat virtual account baru
    public function store(Request $request)
    {
        $request->validate([
            'nomor_virtual_akun' => 'required|string|unique:virtual_accounts,nomor_virtual_akun',
            'account_holder_name' => 'required|string',
            'bank_name' => 'required|string',
            'bank_branch' => 'required|string'
        ]);

        $virtualAccount = VirtualAccount::create($request->all());

        return response()->json(['message' => 'Virtual account created', 'virtual_account' => $virtualAccount], 201);
    }

    // Lihat virtual account berdasarkan ID
    public function show($id)
    {
        $virtualAccount = VirtualAccount::find($id);

        if (!$virtualAccount) {
            return response()->json(['message' => 'Virtual account not found'], 404);
        }

        return response()->json($virtualAccount, 200);
    }

    // Update virtual account
    public function update(Request $request, $id)
    {
        $virtualAccount = VirtualAccount::find($id);

        if (!$virtualAccount) {
            return response()->json(['message' => 'Virtual account not found'], 404);
        }

        $virtualAccount->update($request->all());

        return response()->json(['message' => 'Virtual account updated', 'virtual_account' => $virtualAccount], 200);
    }

    // Hapus virtual account
    public function destroy($id)
    {
        $virtualAccount = VirtualAccount::find($id);

        if (!$virtualAccount) {
            return response()->json(['message' => 'Virtual account not found'], 404);
        }

        $virtualAccount->delete();

        return response()->json(['message' => 'Virtual account deleted'], 200);
    }
}


