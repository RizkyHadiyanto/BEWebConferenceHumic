<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk membuat Super Admin, Admin ICODSA, dan Admin ICICYTA.
     */
    public function run()
    {
        // Pastikan Role sudah ada
        $superadminRole = DB::table('roles')->where('name', 'superadmin')->value('id');
        $icodsaRole = DB::table('roles')->where('name', 'admin_icodsa')->value('id');
        $icicytaRole = DB::table('roles')->where('name', 'admin_icicyta')->value('id');

        // Tambahkan Super Admin ke `users`
        DB::table('users')->updateOrInsert(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'username' => 'superadmin',
                'password' => Hash::make('password123'),
                'role_id' => $superadminRole,
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Tambahkan Admin ICODSA ke `admin_icodsa`
        DB::table('admin_icodsa')->updateOrInsert(
            ['email' => 'admin_icodsa@example.com'],
            [
                'name' => 'Admin ICODSA',
                'password' => Hash::make('password123'),
                'role_id' => $icodsaRole, // 🚀 Tambahkan role_id
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Tambahkan Admin ICICYTA ke `admin_icicyta`
        DB::table('admin_icicyta')->updateOrInsert(
            ['email' => 'admin_icicyta@example.com'],
            [
                'name' => 'Admin ICICYTA',
                'password' => Hash::make('password123'),
                'role_id' => $icicytaRole, // 🚀 Tambahkan role_id
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
