<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BankTransferSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama_bank' => 'Bank Mandiri',
                'swift_code' => 'BMRIIDJA',
                'recipient_name' => 'Universitas Telkom',
                'beneficiary_bank_account_no' => '1310095019917',
                'bank_branch' => 'Bank Mandiri KCP Bandung Martadinata',
                'bank_address' => 'Jl. R.E. Martadinata No.103, Kota Bandung, Jawa Barat, Indonesia, 40115',
                'city' => 'Bandung',
                'country' => 'Indonesia',
                'token' => Str::uuid(),
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_bank' => 'Bank BRI',
                'swift_code' => 'BRINIDJA',
                'recipient_name' => 'Universitas Telkom',
                'beneficiary_bank_account_no' => '12345678901',
                'bank_branch' => 'Bank BRI KCP Bandung Martadinata',
                'bank_address' => 'Jl. R.E. Martadinata No.103, Kota Bandung, Jawa Barat, Indonesia, 40115',
                'city' => 'Bandung',
                'country' => 'Indonesia',
                'token' => Str::uuid(),
                'created_by' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        foreach ($data as $item) {
            DB::table('bank_transfers')->updateOrInsert(
                ['beneficiary_bank_account_no' => $item['beneficiary_bank_account_no']],
                $item
            );
        }
    }
}
