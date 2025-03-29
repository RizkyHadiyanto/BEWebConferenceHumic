<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BankTransfer;
use Illuminate\Support\Facades\Log;
use App\Http\Middleware\SuperAdminMiddleware;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class BankTransferController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        // $this->middleware(SuperAdminMiddleware::class);
        
    }
    
    public function index()
    {
        $user = Auth::user();
        if (!$user || !in_array($user->role_id, [1, 2, 3])) {
            return response()->json(['message' => 'Unauthorized - Access Denied'], 403);
        }

        return response()->json(BankTransfer::all(), 200);
    }

    
    public function createBankTransfer(Request $request)
    {
        
        try {
            $user = Auth::user();
            if (!$user || $user->role_id !== 1) {
                return response()->json(['message' => 'Unauthorized - Super Admin Only'], 403);
            }
    
            
            $validator = Validator::make($request->all(), [
                'nama_bank' => 'required|string',
                'swift_code' => 'nullable|string',
                'recipient_name' => 'required|string',
                'beneficiary_bank_account_no' => 'required|string|unique:bank_transfers,beneficiary_bank_account_no',
                'bank_branch' => 'required|string',
                'bank_address' => 'nullable|string',
                'city' => 'nullable|string',
                'country' => 'required|string'
            ]);
    
            if ($validator->fails()) {
                Log::warning('Validation failed', ['errors' => $validator->errors()]);
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $bankTransfer = BankTransfer::create([
                'nama_bank' => $request->nama_bank,
                'swift_code' => $request->swift_code,
                'recipient_name' => $request->recipient_name,
                'beneficiary_bank_account_no' => $request->beneficiary_bank_account_no,
                'bank_branch' => $request->bank_branch,
                'bank_address' => $request->bank_address,
                'city' => $request->city,
                'country' => $request->country,
                'created_by' => $user->id
            ]);
    
            Log::info('Bank Transfer created successfully', ['bank_transfer' => $bankTransfer]);
    
            return response()->json([
                'message' => 'Bank Transfer created successfully',
                'bank_transfer' => $bankTransfer
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating Bank Transfer', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Terjadi kesalahan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    
    public function show($id)
    {
        $user = Auth::user();
        if (!$user || !in_array($user->role_id, [1, 2, 3])) {
            return response()->json(['message' => 'Unauthorized - Access Denied'], 403);
        }

        $bankTransfer = BankTransfer::find($id);
        if (!$bankTransfer) {
            return response()->json(['message' => 'Bank transfer not found'], 404);
        }

        return response()->json($bankTransfer, 200);
    }

    // Update bank transfer
    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user || $user->role_id !== 1) {
            return response()->json(['message' => 'Unauthorized - Super Admin Only'], 403);
        }

        $bankTransfer = BankTransfer::find($id);

        if (!$bankTransfer) {
            return response()->json(['message' => 'Bank Transfer not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama_bank' => 'nullable|string',
            'swift_code' => 'nullable|string',
            'recipient_name' => 'nullable|string',
            'beneficiary_bank_account_no' => 'nullable|string|unique:bank_transfers,beneficiary_bank_account_no,'.$id,
            'bank_branch' => 'nullable|string',
            'bank_address' => 'nullable|string',
            'city' => 'nullable|string',
            'country' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $bankTransfer->update($request->all());

        return response()->json([
            'message' => 'Bank Transfer updated successfully',
            'bank_transfer' => $bankTransfer
        ], 200);
    }

    
    public function destroy($id)
    {
        $user = Auth::user();
        if (!$user || $user->role_id !== 1) {
            return response()->json(['message' => 'Unauthorized - Super Admin Only'], 403);
        }

        $bankTransfer = BankTransfer::find($id);

        if (!$bankTransfer) {
            return response()->json(['message' => 'Bank Transfer not found'], 404);
        }

        $bankTransfer->delete();

        return response()->json(['message' => 'Bank Transfer deleted successfully'], 200);
    }
}


