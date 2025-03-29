<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class InvoiceSeeder extends Seeder
{
    public function run()
    {
        // Ambil LOA yang memiliki status "Accepted"
        $acceptedLoas = DB::table('loas')->where('status', 'Accepted')->get();

        foreach ($acceptedLoas as $index => $loa) {
            // Tentukan kategori dari LOA
            $category = ($loa->created_by == 2) ? "ICoDSA" : "ICICYTA"; // Admin ICODSA = 2, ICICYTA = 3

            // Buat nomor invoice berdasarkan urutan
            $invoiceNumber = sprintf("%03d", $index + 1) . "/INV/" . $category . "/" . date('Y');

            // Insert Invoice ke database
            DB::table('invoices')->insert([
                'invoice_no' => $invoiceNumber,
                'loa_id' => $loa->id,
                'institution' => 'Universitas XYZ',
                'email' => 'admin@univ-xyz.com',
                'tempat_tanggal' => 'Jakarta, ' . Carbon::now()->format('d M Y'),
                'virtual_account_id' => 1, // Asumsikan ada Virtual Account
                'bank_transfer_id' => 1, // Asumsikan ada Bank Transfer
                'status' => 'Unpaid', // Default Unpaid
                'created_by' => $loa->created_by, // Admin yang membuat LOA
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }
    }
}
