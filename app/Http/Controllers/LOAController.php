<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Loa;
use App\Models\Invoice;
use App\Models\Signature;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;


// Model utama (superadmin)
// use App\Models\Loa;

// Model khusus ICODSA
use App\Models\LoaICODSA;
// Model khusus ICICYTA
use App\Models\LoaICICYTA;

// Model Invoice ICODSA
use App\Models\InvoiceICODSA;
// Model Invoice ICICYTA
use App\Models\InvoiceICICYTA;



class LoaController extends Controller
{
    public function __construct()
    {
        // Di contoh ini kita pakai sanctum & role middleware dari Anda
        $this->middleware('auth:sanctum');
    }

    /**
     * Method bantu untuk memilih model LOA berdasarkan role user.
     */
    private function getLoaModel()
    {
        $user = Auth::user();
        if (!$user) {
            return null;
        }

        switch ($user->role_id) {
            case 1:
                return Loa::class; // Tabel utama
            case 2:
                return LoaICODSA::class; // Tabel khusus ICODSA
            case 3:
                return LoaICICYTA::class; // Tabel khusus ICICYTA
            default:
                return Loa::class; // fallback
        }
    }

    
    public function index()
    {
        try {
            $loaModel = $this->getLoaModel();
            if (!$loaModel) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            // Jika ingin membatasi "hanya data yang dibuat oleh user ini":
            $data = $loaModel::where('created_by', Auth::id())->get();

            // Jika superadmin boleh lihat semua data, admin pun boleh lihat data lain:
            //$data = $loaModel::all();

            return response()->json($data, 200);
        } catch (\Exception $e) {
            Log::error('Error fetching Loa', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Menyimpan LOA ke tabel yang sesuai dengan role
     */
    public function store(Request $request)
    {
        try {
            $user = Auth::user();
            if (!$user) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $loaModel = $this->getLoaModel();
            if (!$loaModel) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            // Validasi
            $validator = Validator::make($request->all(), [
                'paper_id'              => 'required|string|unique:' . (new $loaModel)->getTable(),
                'paper_title'           => 'required|string',
                'author_names'          => 'required|array|min:1|max:5',
                'status'                => 'required|in:Accepted,Rejected',
                'tempat_tanggal'        => 'required|string',
                'signature_id'          => 'required|exists:signatures,id',
                'theme_conference'      => 'required|string',
                'place_date_conference' => 'required|string',
                // 'picture'               => 'required|image|mimes:jpg,png,jpeg|max:2048',
                // 'nama_penandatangan'    => 'required|string',
                // 'jabatan_penandatangan' => 'required|string',

            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            // // Simpan gambar ke storage
            // $path = $request->file('picture')->store('loa_pictures', 'public');

            // Menggunakan ini adanya Fallback
            $signature = Signature::find($request->signature_id);

            // $signature = Signature::find($request->signature_id ?? $invoice->signature_id);
            if (!$signature) {
                return response()->json(['message' => 'Signature not found'], 404);
            }

            // Simpan LOA
            $loa = $loaModel::create([
                'paper_id'       => $request->paper_id,
                'paper_title'    => $request->paper_title,
                'author_names'   => $request->author_names, // JSON jika di-cast di model
                'status'         => $request->status,
                'tempat_tanggal' => $request->tempat_tanggal,
                'signature_id'   => $request->signature_id,
                'created_by'     => $user->id,
                'theme_conference' => $request->theme_conference,
                'place_date_conference' => $request->place_date_conference,
                'picture' => $signature->picture,
                'nama_penandatangan' => $signature->nama_penandatangan,
                'jabatan_penandatangan' => $signature->jabatan_penandatangan,
            ]);
            $loa->save();

            // Jika LOA berstatus 'Accepted', generate Invoice
            if ($loa->status === 'Accepted') {
                $this->createInvoice($loa); 
            }

            return response()->json([
                'message' => 'LOA created successfully',
                'loa'     => $loa
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error creating LOA', ['error' => $e->getMessage()]);
            return response()->json([
                'message' => 'Terjadi kesalahan',
                'error'   => $e->getMessage()
            ], 500);
        }
    }


    /**
     * Menampilkan detail LOA
     */
    public function show($id)
    {
        try {
            $loaModel = $this->getLoaModel();
            if (!$loaModel) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $loa = $loaModel::find($id);
            if (!$loa) {
                return response()->json(['message' => 'LOA not found'], 404);
            }

            return response()->json($loa, 200);
        } catch (\Exception $e) {
            Log::error('Error retrieving LOA', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update LOA
     */
    public function update(Request $request, $id)
    {
        try {
            $loaModel = $this->getLoaModel();
            if (!$loaModel) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $loa = $loaModel::find($id);
            if (!$loa) {
                return response()->json(['message' => 'LOA not found'], 404);
            }

            // Pastikan admin hanya update LOA miliknya (jika diinginkan):
            if (Auth::user()->role_id != 1 && $loa->created_by != Auth::id()) {
                return response()->json(['message' => 'Not your LOA'], 403);
            }

            // $loa->update($request->all());
            $loa->update($request->only([
                'paper_title',
                'author_names',
                'status',
                'tempat_tanggal',
                'theme_conference',
                'place_date_conference',
            ]));

            return response()->json(['message' => 'LOA updated successfully', 'loa' => $loa], 200);
        } catch (\Exception $e) {
            Log::error('Error updating LOA', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Menghapus LOA
     */
    public function destroy($id)
    {
        try {
            $loaModel = $this->getLoaModel();
            if (!$loaModel) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $loa = $loaModel::find($id);
            if (!$loa) {
                return response()->json(['message' => 'LOA not found'], 404);
            }

            $loa->delete();
            return response()->json(['message' => 'LOA deleted'], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting LOA', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
        }
    }


    private function createInvoice($loa)
    {
        try {
            Log::info("Creating Invoice for LOA ID: " . $loa->id);

            // Cari si pembuat LOA
            $creator = User::find($loa->created_by);

            // DEFAULT (kalau tidak ketemu user / superadmin / role lain)
            $roleBasedCode = 'GENERAL';
            $invoiceModel  = Invoice::class;

            // Jika role_id = 2 -> ICODSA
            if ($creator && $creator->role_id == 2) {
                $roleBasedCode = 'ICODSA';
                $invoiceModel  = InvoiceICODSA::class; 
            }
            // Jika role_id = 3 -> ICICYTA
            elseif ($creator && $creator->role_id == 3) {
                $roleBasedCode = 'ICICYTA';
                $invoiceModel  = InvoiceICICYTA::class; 
            }else {
                // Fallback -> Superadmin atau role lain
                $roleBasedCode = 'GENERAL';
                $invoiceModel  = Invoice::class; 
            }

            // Buat nomor invoice sesuai role
            $invoiceNumber = sprintf(
                '%03d/INV/%s/%s',
                $invoiceModel::count() + 1,
                $roleBasedCode,
                date('Y')
            );
            $signature = Signature::find($loa->signature_id);

            // Cek apakah sudah ada invoice untuk LOA ini
            $invoice = $invoiceModel::where('loa_id', $loa->id)->first();
            
            // Simpan invoice di tabel yang sesuai
            $data=[
                'invoice_no'   => $invoiceNumber,
                'loa_id'       => $loa->id,
                'paper_id'     => $loa->paper_id,
                'paper_title'  => $loa->paper_title,
                'created_by'   => $loa->created_by,
                'signature_id' => $loa->signature_id,
                'author_names' => $loa->author_names,
                
                // Boleh tambahkan field lain seperti
                // 'institution' => null,
                // 'email' => null,
                // dsb.

                // Status invoice default 'Unpaid' misalnya
                'status'       => 'Pending',
                'picture'          => $signature->picture,
                'nama_penandatangan'=> $signature->nama_penandatangan,
                'jabatan_penandatangan' => $signature->jabatan_penandatangan,
                'created_by'       => $loa->created_by,
                'signature_id'     => $loa->signature_id,
            ];
            if ($invoice) {
                $invoice->update($data);
                Log::info("Invoice updated for LOA ID: " . $loa->id);
            } else {
                // Buat nomor invoice baru
                $data['invoice_no'] = sprintf(
                    '%03d/INV/%s/%s',
                    $invoiceModel::count() + 1,
                    $roleBasedCode,
                    date('Y')
                );

                $invoice = $invoiceModel::create($data);
                Log::info("Invoice created for LOA ID: " . $loa->id);
            }
        } catch (\Exception $e) {
            Log::error('Error creating Invoice', ['error' => $e->getMessage()]);
        }
    }
    
    public function downloadLOA($id)
    {
        try {
            $loaModel = $this->getLoaModel();
            if (!$loaModel) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }
    
            $loa = $loaModel::find($id);
            if (!$loa) {
                return response()->json(['message' => 'LOA not found'], 404);
            }
    
            if (Auth::user()->role_id != 1 && $loa->created_by != Auth::id()) {
                return response()->json(['message' => 'Forbidden'], 403);
            }
    
            // Pilih view berdasarkan role
            switch (Auth::user()->role_id) {
                case 2:
                    $view = 'pdf.icodsaloa';
                    break;
                case 3:
                    $view = 'pdf.icicytaloa';
                    break;
                default:
                    $view = 'pdf.loa'; // Superadmin atau fallback
            }
    
            $filename = 'LOA_' . str_replace(['/', '\\'], '-', $loa->paper_id) . '.pdf';
            $pdf = Pdf::loadView($view, compact('loa'));
    
            return $pdf->download($filename);
    
        } catch (\Exception $e) {
            Log::error('Error generating LOA PDF', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
        }
    }
}