<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class DatabaseSeeder extends Seeder
{
    /**
     * Jalankan seeder.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,        // ✅ Jalankan RoleSeeder lebih dulu
            UserSeeder::class,        // ✅ Tambahkan Super Admin ke `users`
            AdminSeeder::class,       // ✅ Tambahkan Admin ICODSA & Admin ICICYTA
        ]);
        // // Pastikan role tersedia
        // $superadminRole = DB::table('roles')->where('name', 'superadmin')->value('id') 
        //     ?? DB::table('roles')->insertGetId(['name' => 'superadmin', 'created_at' => now(), 'updated_at' => now()]);

        // // Tambahkan Super Admin ke `users`
        // DB::table('users')->updateOrInsert(
        //     ['email' => 'superadmin@example.com'],
        //     [
        //         'name' => 'Super Admin',
        //         'username' => 'superadmin',
        //         'password' => Hash::make('password123'),
        //         'role_id' => $superadminRole,
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]
        // );

        // // Tambahkan Admin ICODSA ke `admin_icodsa`
        // DB::table('admin_icodsa')->updateOrInsert(
        //     ['email' => 'admin_icodsa@example.com'],
        //     [
        //         'name' => 'Admin ICODSA',
        //         'password' => Hash::make('password123'),
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]
        // );

        // // Tambahkan Admin ICICYTA ke `admin_icicyta`
        // DB::table('admin_icicyta')->updateOrInsert(
        //     ['email' => 'admin_icicyta@example.com'],
        //     [
        //         'name' => 'Admin ICICYTA',
        //         'password' => Hash::make('password123'),
        //         'created_at' => now(),
        //         'updated_at' => now(),
        //     ]

        // );
        
    }
}
