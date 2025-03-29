<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Payment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller;


class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index()
    {
        try {
            $invoice = Invoice::all();
            return response()->json($invoice, 200);
        } catch (\Exception $e) {
            Log::error('Error fetching invoice', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $invoice = Invoice::find($id);
            if (!$invoice) {
                return response()->json(['message' => 'Invoice not found'], 404);
            }

            // Validasi input
            $validator = Validator::make($request->all(), [
                'status' => 'in:Pending,Paid',
                'institution' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255',
                'presentation_type' => 'nullable|in:Onsite,Online',
                'member_type' => 'nullable|in:IEEE Member,IEEE Non Member',
                'author_type' => 'nullable|in:Author,Student Author',
                'amount' => 'nullable|numeric|min:0',
                'date_of_issue' => 'nullable|date',
                'virtual_account_id' => 'nullable|exists:virtual_accounts,id',
                'bank_transfer_id' => 'nullable|exists:bank_transfers,id',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Update field yang boleh diubah
            $invoice->fill($request->only([
                'institution',
                'email',
                'presentation_type',
                'member_type',
                'author_type',
                'amount',
                'date_of_issue',
                'virtual_account_id',
                'bank_transfer_id',
                'status'
            ]));

            $invoice->save();

            // Buat payment jika sudah Paid
            if ($invoice->status === 'Paid') {
                $this->createPayment($invoice);
            }

            return response()->json([
                'message' => 'Invoice updated successfully',
                'invoice' => $invoice->load('loa') // tampilkan relasi jika perlu
            ], 200);
        } catch (\Exception $e) {
            Log::error('Error updating Invoice', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $invoice = Invoice::find($id);
            if (!$invoice) {
                return response()->json(['message' => 'Invoice not found'], 404);
            }

            return response()->json($invoice, 200);
        } catch (\Exception $e) {
            Log::error('Error retrieving Invoice', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
        }
    }

    
    private function createPayment($invoice)
    {
        try {
            $loa = $invoice->loa;

            Payment::create([
                'invoice_no' => $invoice->invoice_no,
                'received_from' => $invoice->institution,
                'amount' => $invoice->amount,
                'in_payment_of' => 'Conference Registration for ' . ($loa->paper_title ?? 'Unknown'),
                'payment_date' => now(),
                'paper_id' => $loa->paper_id ?? '-',
                'paper_title' => $loa->paper_title ?? '-',
                'signature_id' => $invoice->signature_id,
                'created_by' => $invoice->created_by,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            Log::info("Payment auto-generated for invoice: " . $invoice->invoice_no);
        } catch (\Exception $e) {
            Log::error('Error creating Payment', ['error' => $e->getMessage()]);
        }
    }

    

}
// private function createPayment($invoice)
    // {
    //     try {
    //         Payment::create([
    //             'invoice_id' => $invoice->id,
    //             'invoice_no' => $invoice->invoice_no,
    //             'received_from' => $invoice->institution ?? Auth::user()->name,
    //             'amount' => $invoice->amount ?? 0,
    //             'in_payment_of' => "Conference Registration for " . ($invoice->loa->paper_title ?? 'LOA'),
    //             'payment_date' => now(),
    //             'paper_id' => $invoice->loa->paper_id ?? null,
    //             'paper_title' => $invoice->loa->paper_title ?? null,
    //             'signature_id' => $invoice->signature_id,
    //             'created_by' => $invoice->created_by
    //         ]);
    //     } catch (\Exception $e) {
    //         Log::error('Error creating Payment', ['error' => $e->getMessage()]);
    //     }
    // }