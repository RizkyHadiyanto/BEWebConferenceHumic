<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run()
    {
        DB::table('roles')->insert([
            ['id' => 1, 'name' => 'superadmin', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'name' => 'admin_icodsa', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 3, 'name' => 'admin_icicyta', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
