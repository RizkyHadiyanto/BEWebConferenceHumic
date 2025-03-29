<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class SignatureSeeder extends Seeder
{
    public function run()
    {
        DB::table('signatures')->insert([
            [
                'picture' => 'signatures/sample1.png',
                'nama_penandatangan' => 'Dr. John Doe',
                'jabatan_penandatangan' => 'Rektor Universitas',
                'tanggal_dibuat' => Carbon::createFromFormat('m-d-Y', '03-20-2024')->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'picture' => 'signatures/sample2.png',
                'nama_penandatangan' => 'Prof. Jane Smith',
                'jabatan_penandatangan' => 'Dekan Fakultas',
                'tanggal_dibuat' => Carbon::createFromFormat('m-d-Y', '05-21-2023')->format('Y-m-d'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
