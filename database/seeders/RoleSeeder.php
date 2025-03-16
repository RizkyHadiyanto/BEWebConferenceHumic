<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    public function run()
    {
        // Hapus semua data sebelumnya untuk menghindari duplikasi
        DB::table('roles')->delete();
        DB::statement('ALTER TABLE roles AUTO_INCREMENT = 1');

        // DB::table('roles')->updateOrinsert([
        //     ['id' => 1, 'name' => 'superadmin', 'created_at' => now(), 'updated_at' => now()],
        //     ['id' => 2, 'name' => 'admin_icodsa', 'created_at' => now(), 'updated_at' => now()],
        //     ['id' => 3, 'name' => 'admin_icicyta', 'created_at' => now(), 'updated_at' => now()],
        // ]);
        DB::table('roles')->updateOrInsert(
            ['id' => 1], 
            ['name' => 'superadmin', 'created_at' => now(), 'updated_at' => now()]
        );
        DB::table('roles')->updateOrInsert(
            ['id' => 2], 
            ['name' => 'admin_icodsa', 'created_at' => now(), 'updated_at' => now()]
        );
        DB::table('roles')->updateOrInsert(
            ['id' => 3], 
            ['name' => 'admin_icicyta', 'created_at' => now(), 'updated_at' => now()]
        );
        
    }
}
