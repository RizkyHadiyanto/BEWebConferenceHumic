<?php

// namespace App\Http\Controllers;

// use Illuminate\Http\Request;
// use App\Models\BankTransfer;

// class BankTransferController extends Controller
// {
//     public function store(Request $request)
//     {
//         $request->validate([
//             'nama_bank' => 'required|string',
//             'swift_code' => 'nullable|string',
//             'recipient_name' => 'required|string',
//             'beneficiary_bank_account_no' => 'required|string|unique:bank_transfers',
//             'bank_branch' => 'required|string',
//             'bank_address' => 'nullable|string',
//             'city' => 'nullable|string',
//             'country' => 'required|string',
//         ]);

//         $bankTransfer = BankTransfer::create($request->all());

//         return response()->json([
//             'success' => true,
//             'message' => 'Bank Transfer berhasil dibuat!',
//             'data' => $bankTransfer
//         ], 201);
//     }

//     public function index()
//     {
//         $transfers = BankTransfer::all();
//         return response()->json([
//             'success' => true,
//             'data' => $transfers
//         ], 200);
//     }
// }
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BankTransfer;
use Illuminate\Support\Facades\Log;
use App\Http\Middleware\SuperAdminMiddleware;
use Illuminate\Routing\Controller;

class BankTransferController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        $this->middleware('superadmin');
    }
    // public function __construct()
    // {
    //     // parent::__construct(); 
    //     $this->middleware(['auth:sanctum', SuperAdminMiddleware::class]);
    // }

    // List semua bank transfer
    public function index()
    {
        return response()->json(BankTransfer::all(), 200);
    }

    // Buat bank transfer baru
    public function store(Request $request)
    {
        $request->validate([
            'nama_bank' => 'required|string',
            'swift_code' => 'nullable|string',
            'recipient_name' => 'required|string',
            'beneficiary_bank_account_no' => 'required|string',
            'bank_branch' => 'required|string',
            'bank_address' => 'nullable|string',
            'city' => 'nullable|string',
            'country' => 'required|string'
        ]);

        $bankTransfer = BankTransfer::create($request->all());

        return response()->json(['message' => 'Bank transfer created', 'bank_transfer' => $bankTransfer], 201);
    }

    // Lihat bank transfer berdasarkan ID
    public function show($id)
    {
        $bankTransfer = BankTransfer::find($id);

        if (!$bankTransfer) {
            return response()->json(['message' => 'Bank transfer not found'], 404);
        }

        return response()->json($bankTransfer, 200);
    }

    // Update bank transfer
    public function update(Request $request, $id)
    {
        $bankTransfer = BankTransfer::find($id);

        if (!$bankTransfer) {
            return response()->json(['message' => 'Bank transfer not found'], 404);
        }

        $bankTransfer->update($request->all());

        return response()->json(['message' => 'Bank transfer updated', 'bank_transfer' => $bankTransfer], 200);
    }

    // Hapus bank transfer
    public function destroy($id)
    {
        $bankTransfer = BankTransfer::find($id);

        if (!$bankTransfer) {
            return response()->json(['message' => 'Bank transfer not found'], 404);
        }

        $bankTransfer->delete();

        return response()->json(['message' => 'Bank transfer deleted'], 200);
    }
}


