<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class LOASeeder extends Seeder
{
    public function run()
    {
        DB::table('loas')->insert([
            [
                'paper_id' => 'LOA001',
                'paper_title' => 'Exploring the Influence of Citizen Sentiment on the  utilization of Government Service Applications in Indonesia',
                'author_names' => json_encode(['John Doe', 'Jane Smith','david','raka','budi']),
                'status' => 'Accepted',
                'tempat_tanggal' => 'Jakarta, 15 Maret 2025',
                'signature_id' => 1,
                'created_by' => 1, // Super Admin
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'paper_id' => 'LOA002',
                'paper_title' => 'Cloud Computing Security',
                'author_names' => json_encode(['Alice Brown', 'Bob Martin','sadino','evan','martin']),
                'status' => 'Accepted',
                'tempat_tanggal' => 'Bandung, 10 Maret 2025',
                'signature_id' => 2,
                'created_by' => 2, // Admin ICODSA
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'paper_id' => 'LOA003',
                'paper_title' => 'Machine Learning terus terus',
                'author_names' => json_encode([' Brown', 'Bob','yahya','dimas','paes']),
                'status' => 'Accepted',
                'tempat_tanggal' => 'Bandung, 11 Februari 2023',
                'signature_id' => 1,
                'created_by' => 3, // Admin ICICYTA
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ]);
    }
}
