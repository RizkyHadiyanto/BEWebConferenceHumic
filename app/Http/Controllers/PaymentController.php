<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Validator;

// class PaymentController extends Controller
// {
//     public function __construct()
//     {
//         $this->middleware('auth:sanctum');
//     }

//     public function index()
//     {
//         try {
//             return response()->json(Payment::all(), 200);
//         } catch (\Exception $e) {
//             Log::error('Error retrieving Payments', ['error' => $e->getMessage()]);
//             return response()->json(['message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
//         }
//     }

//     public function show($id)
//     {
//         try {
//             $payment = Payment::find($id);
//             if (!$payment) {
//                 return response()->json(['message' => 'Payment not found'], 404);
//             }

//             return response()->json($payment, 200);
//         } catch (\Exception $e) {
//             Log::error('Error retrieving Payment', ['error' => $e->getMessage()]);
//             return response()->json(['message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
//         }
//     }
// }

// Model khusus
use App\Models\PaymentICODSA;
use App\Models\PaymentICICYTA;

class PaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
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

    public function index()
    {
        try {
            $paymentModel = $this->getPaymentModel();
            $payments = $paymentModel::all();
            return response()->json($payments, 200);
        } catch (\Exception $e) {
            Log::error('Error retrieving Payments', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
        }
    }

    public function show($id)
    {
        try {
            $paymentModel = $this->getPaymentModel();
            $payment = $paymentModel::find($id);

            if (!$payment) {
                return response()->json(['message' => 'Payment not found'], 404);
            }
            return response()->json($payment, 200);
        } catch (\Exception $e) {
            Log::error('Error retrieving Payment', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $paymentModel = $this->getPaymentModel();
            if (!$paymentModel) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $payment = $paymentModel::find($id);
            if (!$payment) {
                return response()->json(['message' => 'payment not found'], 404);
            }

            // Pastikan admin hanya update payment miliknya (jika diinginkan):
            if (Auth::user()->role_id != 1 && $payment->created_by != Auth::id()) {
                return response()->json(['message' => 'Not your payment'], 403);
            }
            //bisa di update semuanya
            // $payment->update($request->all());
            // Validasi input
            $validator = Validator::make($request->all(), [
                'received_from' => 'nullable|string|max:255',
                'amount' => 'nullable|numeric|min:0',
                'in_payment_of' => 'nullable|string|max:255',
                'payment_date' => 'nullable|date',
                'paper_id' => 'nullable|string|max:255',
                'paper_title' => 'nullable|string|max:255',
                'nama_penandatangan' => 'nullable|string|max:255',
                'jabatan_penandatangan' => 'nullable|string|max:255',
                'picture' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Simpan file jika ada
            if ($request->hasFile('picture')) {
                $path = $request->file('picture')->store('payment_pictures', 'public');
                $payment->picture = $path;
            }

            // Update data
            $payment->fill($request->only([
                'received_from',
                'amount',
                'in_payment_of',
                'payment_date',
                'paper_id',
                'paper_title',
                'nama_penandatangan',
                'jabatan_penandatangan',
            ]));

            $payment->save();

            return response()->json(['message' => 'payment updated successfully', 'payment' => $payment], 200);
        } catch (\Exception $e) {
            Log::error('Error updating payment', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
        }
    }

    /**
     * Menghapus payment
     */
    public function destroy($id)
    {
        try {
            $paymentModel = $this->getPaymentModel();
            if (!$paymentModel) {
                return response()->json(['message' => 'Unauthorized'], 401);
            }

            $payment = $paymentModel::find($id);
            if (!$payment) {
                return response()->json(['message' => 'payment not found'], 404);
            }

            $payment->delete();
            return response()->json(['message' => 'payment deleted'], 200);
        } catch (\Exception $e) {
            Log::error('Error deleting payment', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
        }
    }

    public function downloadPayment($id)
    {
        try {
            $paymentModel = $this->getPaymentModel();
            $payment = $paymentModel::find($id);
    
            if (!$payment) {
                return response()->json(['message' => 'Payment not found'], 404);
            }
    
            if (Auth::user()->role_id != 1 && $payment->created_by != Auth::id()) {
                return response()->json(['message' => 'Forbidden'], 403);
            }
    
            // Pilih view berdasarkan role
            switch (Auth::user()->role_id) {
                case 2:
                    $view = 'pdf.icodsapayment';
                    break;
                case 3:
                    $view = 'pdf.icicytapayment';
                    break;
                default:
                    $view = 'pdf.payment'; // superadmin atau fallback
            }
    
            $filename = 'Payment_' . str_replace(['/', '\\'], '-', $payment->invoice_no) . '.pdf';
            $pdf = Pdf::loadView($view, compact('payment'));
    
            return $pdf->download($filename);
    
        } catch (\Exception $e) {
            Log::error('Error generating Payment PDF', ['error' => $e->getMessage()]);
            return response()->json(['message' => 'Terjadi kesalahan', 'error' => $e->getMessage()], 500);
        }
    }
}
