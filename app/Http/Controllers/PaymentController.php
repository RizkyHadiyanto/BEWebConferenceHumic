<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use Illuminate\Support\Facades\Log;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

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

            $payment->update($request->all());

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
}