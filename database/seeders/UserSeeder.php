<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Ambil ID role berdasarkan nama
        $superadminRole = DB::table('roles')->where('name', 'superadmin')->value('id');
        $icodsaRole = DB::table('roles')->where('name', 'admin_icodsa')->value('id');
        $icicytaRole = DB::table('roles')->where('name', 'admin_icicyta')->value('id');

        // Pastikan Super Admin masuk ke tabel `users`
        User::updateOrInsert(
            ['email' => 'superadmin@example.com'],
            [
                'username' => 'superadmin',
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
                'role_id' => $superadminRole, // ✅ Gunakan role_id
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Admin ICODSA masuk ke tabel `admin_icodsa`
        DB::table('admin_icodsa')->updateOrInsert(
            ['email' => 'admin_icodsa@example.com'],
            [
                'name' => 'Admin ICODSA',
                'password' => Hash::make('password123'),
                'role_id' => $icodsaRole, // ✅ Gunakan role_id
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Admin ICICYTA masuk ke tabel `admin_icicyta`
        DB::table('admin_icicyta')->updateOrInsert(
            ['email' => 'admin_icicyta@example.com'],
            [
                'name' => 'Admin ICICYTA',
                'password' => Hash::make('password123'),
                'role_id' => $icicytaRole, // ✅ Gunakan role_id
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
