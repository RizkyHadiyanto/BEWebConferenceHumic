<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loa;
use App\Models\Invoice;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller;
use App\Models\User;

class LoaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        // $this->middleware(SuperAdminMiddleware::class);
            
    }
    public function index()
    {
        try {
            $loa = Loa::all();
            return response()->json($loa, 200);
        } catch (\Exception $e) {
            Log::error('Error fetching loa', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $validator = Validator::make($request->all(), [
                'paper_id' => 'required|string|unique:loas',
                'paper_title' => 'required|string',
                'author_names' => 'required|array|min:1|max:5',
                'status' => 'required|in:Accepted,Rejected',
                'tempat_tanggal' => 'required|string',
                'signature_id' => 'required|exists:signatures,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 400);
            }

            $loa = Loa::create([
                'paper_id' => $request->paper_id,
                'paper_title' => $request->paper_title,
                'author_names' => json_encode($request->author_names),
                'status' => $request->status,
                'tempat_tanggal' => $request->tempat_tanggal,
                'signature_id' => $request->signature_id,
                'created_by' => $user->id
            ]);

            if ($request->status === 'Accepted') {
                $this->createInvoice($loa);
            }

            return response()->json(['message' => 'LOA created successfully', 'loa' => $loa], 201);
        } catch (\Exception $e) {
            Log::error('Error creating LOA', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $loa = Loa::find($id);
            if (!$loa) {
                return response()->json(['message' => 'LOA not found'], 404);
            }

            $loa->update($request->all());

            if ($request->status === 'Accepted') {
                $this->createInvoice($loa);
            }

            return response()->json(['message' => 'LOA updated successfully', 'loa' => $loa], 200);
        } catch (\Exception $e) {
            Log::error('Error updating LOA', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
        }
    }

    // private function createInvoice($loa)
    // {
    //     try {
    //         Log::info("Creating Invoice for LOA ID: " . $loa->id);

    //         $invoiceNumber = sprintf('%03d/INV/%s/%s',
    //             Invoice::count() + 1,
    //             ($loa->created_by == 2 ? 'ICODSA' : 'ICICYTA'),
    //             date('Y')
    //         );

    //         $invoice = Invoice::create([
    //             'invoice_no' => $invoiceNumber,
    //             'loa_id' => $loa->id,
    //             'created_by' => $loa->created_by,
    //             'signature_id' => $loa->signature_id,
    //             'status' => 'Unpaid'
    //         ]);

    //         Log::info("Invoice Created Successfully: " . $invoice->id);
    //     } catch (\Exception $e) {
    //         Log::error('Error creating Invoice', ['error' => $e->getMessage()]);
    //     }
    // }
    private function createInvoice($loa)
    {
        try {
            Log::info("Creating Invoice for LOA ID: " . $loa->id);

            $creator = User::find($loa->created_by);
            $roleBasedCode = 'GENERAL';

            if ($creator && $creator->role_id == 2) {
                $roleBasedCode = 'ICODSA';
            } elseif ($creator && $creator->role_id == 3) {
                $roleBasedCode = 'ICICYTA';
            }

            $invoiceNumber = sprintf('%03d/INV/%s/%s',
                Invoice::count() + 1,
                $roleBasedCode,
                date('Y')
            );

            $invoice = Invoice::create([
                'invoice_no' => $invoiceNumber,
                'loa_id' => $loa->id,
                'created_by' => $loa->created_by,
                'signature_id' => $loa->signature_id,
                'status' => 'Unpaid'
            ]);

            Log::info("Invoice Created Successfully: " . $invoice->id);
        } catch (\Exception $e) {
            Log::error('Error creating Invoice', ['error' => $e->getMessage()]);
        }
    }

}
