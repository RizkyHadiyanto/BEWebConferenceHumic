<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        // Ambil semua invoice yang memiliki status "Paid"
        $paidInvoices = DB::table('invoices')->where('status', 'Paid')->get();

        foreach ($paidInvoices as $invoice) {
            // Ambil data LOA terkait
            $loa = DB::table('loas')->where('id', $invoice->loa_id)->first();
            if (!$loa) continue;

            // Ambil tanda tangan dari LOA
            $signature = DB::table('signatures')->where('id', $loa->signature_id)->first();
            $signature_id = $signature ? $signature->id : 1;

            // Simulasi jumlah pembayaran
            $amount = rand(500, 2000);

            // Insert ke tabel payments
            DB::table('payments')->insert([
                'received_from' => $invoice->institution,
                'amount' => $amount,
                'in_payment_of' => 'Conference Paper Registration',
                'payment_date' => Carbon::now()->format('Y-m-d'),
                'invoice_no' => $invoice->invoice_no,
                'paper_id' => $loa->paper_id,
                'paper_title' => $loa->paper_title,
                'signature_id' => $signature_id,
                'created_by' => $invoice->created_by,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
