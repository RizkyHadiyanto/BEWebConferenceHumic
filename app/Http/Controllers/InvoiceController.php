<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Signature;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller;
use Barryvdh\DomPDF\Facade\Pdf;

use App\Models\InvoiceICODSA;
use App\Models\InvoiceICICYTA;

use App\Models\PaymentICODSA;
use App\Models\PaymentICICYTA;

class InvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    private function getInvoiceModel()
    {
        $user = Auth::user();
        switch ($user->role_id) {
            case 1:
                return Invoice::class;
            case 2:
                return InvoiceICODSA::class;
            case 3:
                return InvoiceICICYTA::class;
            default:
                return Invoice::class;
        }
    }
    
    private function getPaymentModel()
    {
        $user = Auth::user();
        switch ($user->role_id) {
            case 1:
                return Payment::class;       
            case 2:
                return PaymentICODSA::class; 
            case 3:
                return PaymentICICYTA::class; 
            default:
                return Payment::class;
        }
    }

    // public function index()
    // {
    //     try {
    //         $invoiceModel = $this->getInvoiceModel();
    //         $invoices = $invoiceModel::all();
    //         return response()->json($invoices, 200);
    //     } catch (\Exception $e) {
    //         Log::error('Error fetching invoice', ['error' => $e->getMessage()]);
    //         return response()->json(['message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
    //     }
    // }

    public function index()
    {
        try {
            $invoiceModel = $this->getInvoiceModel();
            if (!$invoiceModel) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            // Jika ingin membatasi "hanya data yang dibuat oleh user ini":
            $data = $invoiceModel::where('created_by', Auth::id())->get();

            // Jika superadmin boleh lihat semua data, admin pun boleh lihat data lain:
            //$data = $invoiceModel::all();

            return response()->json($data, 200);
        } catch (\Exception $e) {
            Log::error('Error fetching Loa', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
        }
    }
    
    public function show($id)
    {
        try {
            $invoiceModel = $this->getInvoiceModel();
            $invoice = $invoiceModel::find($id);
            if (!$invoice) {
                return response()->json(['message' => 'Invoice not found'], 404);
            }
            return response()->json($invoice, 200);
        } catch (\Exception $e) {
            Log::error('Error retrieving Invoice', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
        }
    }
    
    

    
    /**
     * Update Invoice
     */
    public function update(Request $request, $id)
    {
        try {
            // Gunakan getInvoiceModel() agar admin role=2/3 memakai tabel khusus
            $invoiceModel = $this->getInvoiceModel();
            $invoice = $invoiceModel::find($id);

            if (!$invoice) {
                return response()->json(['message' => 'Invoice not found'], 404);
            }

            // Validasi input
            $validator = Validator::make($request->all(), [
                'status' => 'in:Pending,Paid,Unpaid',
                'institution' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:255',
                'presentation_type' => 'nullable|in:Onsite,Online',
                'member_type' => 'nullable|in:IEEE Member,IEEE Non Member',
                'author_names' =>  'required|array|min:1|max:5',
                'author_type' => 'nullable|in:Author,Student Author',
                'amount' => 'nullable|numeric|min:0',
                'date_of_issue' => 'nullable|date',
                'signature_id'          => 'required|exists:signatures,id',
                'virtual_account_id' => 'nullable|exists:virtual_accounts,id',
                'bank_transfer_id' => 'nullable|exists:bank_transfers,id',
                // 'picture' => 'required|image|mimes:jpg,png,jpeg|max:2048',
                // 'nama_penandatangan' => 'required|string',
                // 'jabatan_penandatangan' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // if ($validator->fails()) {
            //     return response()->json(['errors' => $validator->errors()], 422);
            // }
            // // Simpan gambar ke storage
            // $path = $request->file('picture')->store('loa_pictures', 'public');
            $signature = Signature::find($request->signature_id);
            if (!$signature) {
                return response()->json(['message' => 'Signature not found'], 404);
            }

            // Update kolom yang diizinkan
            $invoice->fill($request->only([
                'institution',
                'email',
                'presentation_type',
                'member_type',
                'author_names',
                'author_type',
                'amount',
                'date_of_issue',
                'signature_id',
                'virtual_account_id',
                'bank_transfer_id',
                'status'
            ]));

            // Auto-fill dari Signature
            $invoice->picture = $signature->picture;
            $invoice->nama_penandatangan = $signature->nama_penandatangan;
            $invoice->jabatan_penandatangan = $signature->jabatan_penandatangan;

            $invoice->save();

            // Jika status = Paid -> buat payment otomatis
            if ($invoice->status === 'Paid') {
                $this->createPayment($invoice);
            }

            return response()->json([
                'message' => 'Invoice updated successfully',
                // load('loa') jika Anda punya relasi invoice->loa
                'invoice' => $invoice->load('loa')
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error updating Invoice', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Menghapus Invoice
     */
    public function destroy($id)
    {
        try {
            $invoiceModel = $this->getInvoiceModel();
            if (!$invoiceModel) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
    
            $invoice = $invoiceModel::find($id);
            if (!$invoice) {
                return response()->json(['message' => 'Invoice not found'], 404);
            }
    
            $invoice->delete();
            return response()->json(['message' => 'invoice deleted'], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting invoice', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
        }
    }

    private function createPayment($invoice)
    {
        try {
            // Pilih model payment sesuai role
            $paymentModel = $this->getPaymentModel();

            // Ambil data LOA (pastikan relasi "public function loa()" di model invoice)
            $loa = $invoice->loa;

            $paymentModel::create([
                'invoice_no'      => $invoice->invoice_no,
                // 'received_from'   => $invoice->institution,
                'amount'          => $invoice->amount,
                'in_payment_of'   => 'Conference Registration for ' . ($loa->paper_title ?? 'Unknown'),
                'payment_date'    => now(),
                'paper_id'        => $loa->paper_id ?? '-',
                'paper_title'     => $loa->paper_title ?? '-',
                'signature_id'    => $invoice->signature_id,
                'created_by'      => $invoice->created_by,
                // created_at / updated_at diisi otomatis oleh Eloquent, 
                // jadi baris berikut opsional kecuali Anda ingin override
                'created_at'      => now(),
                'updated_at'      => now(),
            ]);

            Log::info("Payment auto-generated for invoice: " . $invoice->invoice_no);
        } catch (\Exception $e) {
            Log::error('Error creating Payment', ['error' => $e->getMessage()]);
        }
    }

    public function downloadInvoice($id)
    {
        try {
            $invoiceModel = $this->getInvoiceModel();
            $invoice = $invoiceModel::with('loa')->find($id);
    
            if (!$invoice) {
                return response()->json(['message' => 'Invoice not found'], 404);
            }
    
            if (Auth::user()->role_id != 1 && $invoice->created_by != Auth::id()) {
                return response()->json(['message' => 'Forbidden'], 403);
            }
    
            // Pilih view berdasarkan role_id
            switch (Auth::user()->role_id) {
                case 2:
                    $view = 'pdf.icodsainvoice';
                    break;
                case 3:
                    $view = 'pdf.icicytainvoice';
                    break;
                default:
                    $view = 'pdf.invoice'; // Superadmin atau fallback
            }
    
            $filename = 'Invoice_' . str_replace(['/', '\\'], '-', $invoice->invoice_no) . '.pdf';
            $pdf = Pdf::loadView($view, compact('invoice'));
    
            return $pdf->download($filename);
    
        } catch (\Exception $e) {
            Log::error('Error generating Invoice PDF', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
        }
    }
}
