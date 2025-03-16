<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\LOA;
use App\Models\VirtualAccount;
use App\Models\BankTransfer;
use Illuminate\Support\Facades\Validator;

class InvoiceController extends Controller
{
    // Create Invoice
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'loa_id' => 'required|exists:loas,id',
            'institution' => 'required|string',
            'email' => 'required|email',
            'tempat_tanggal' => 'required|string',
            'virtual_account_id' => 'required|exists:virtual_accounts,id',
            'bank_transfer_id' => 'required|exists:bank_transfers,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $invoice = Invoice::create([
            'loa_id' => $request->loa_id,
            'institution' => $request->institution,
            'email' => $request->email,
            'tempat_tanggal' => $request->tempat_tanggal,
            'virtual_account_id' => $request->virtual_account_id,
            'bank_transfer_id' => $request->bank_transfer_id
        ]);

        return response()->json(['message' => 'Invoice created successfully', 'invoice' => $invoice], 201);
    }

    // Get all Invoices
    public function index()
    {
        $invoices = Invoice::with(['loa', 'virtualAccount', 'bankTransfer'])->get();
        return response()->json(['success' => true, 'invoices' => $invoices], 200);
    }

    // Get single Invoice
    public function show($id)
    {
        $invoice = Invoice::with(['loa', 'virtualAccount', 'bankTransfer'])->find($id);

        if (!$invoice) {
            return response()->json(['message' => 'Invoice not found'], 404);
        }

        return response()->json(['success' => true, 'invoice' => $invoice], 200);
    }

    // Update Invoice
    public function update(Request $request, $id)
    {
        $invoice = Invoice::find($id);

        if (!$invoice) {
            return response()->json(['message' => 'Invoice not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'institution' => 'sometimes|string',
            'email' => 'sometimes|email',
            'tempat_tanggal' => 'sometimes|string',
            'virtual_account_id' => 'sometimes|exists:virtual_accounts,id',
            'bank_transfer_id' => 'sometimes|exists:bank_transfers,id'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $invoice->update($request->all());

        return response()->json(['message' => 'Invoice updated successfully', 'invoice' => $invoice], 200);
    }

    // Delete Invoice
    public function destroy($id)
    {
        $invoice = Invoice::find($id);

        if (!$invoice) {
            return response()->json(['message' => 'Invoice not found'], 404);
        }

        $invoice->delete();
        return response()->json(['message' => 'Invoice deleted successfully'], 200);
    }
}
