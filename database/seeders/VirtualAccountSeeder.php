<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class VirtualAccountSeeder extends Seeder
{
    public function run()
    {
        DB::table('virtual_accounts')->insert([
            [
                'nomor_virtual_akun' => '8321066202400006 ',
                'account_holder_name' => 'Universitas Telkom',
                'bank_name' => 'Bank Negara Indonesia (BNI) ',
                'bank_branch' => 'Perintis Kemerdekaan',
                'token' => Str::uuid(),
                'created_by' => 1, // Super Admin
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nomor_virtual_akun' => '8321066202400016 ',
                'account_holder_name' => 'PT. Telkom Indonesia',
                'bank_name' => 'Bank BCA',
                'bank_branch' => 'Jakarta',
                'token' => Str::uuid(),
                'created_by' => 1, // Super Admin
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
