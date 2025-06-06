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
                'role_id' => $superadminRole, 
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Admin ICODSA masuk ke tabel `admin_icodsa`
        User::updateOrInsert(
            ['email' => 'admin_icodsa@example.com'],
            [
                'username' => 'admin_icodsa',
                'name' => 'Admin ICODSA',
                'password' => 'password123',
                'role_id' => $icodsaRole, 
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Admin ICICYTA masuk ke tabel `admin_icicyta`
        User::updateOrInsert(
            ['email' => 'admin_icicyta@example.com'],
            [
                'username' => 'admin_icicyta',
                'name' => 'Admin ICICYTA',
                'password' => 'password123',
                'role_id' => $icicytaRole, 
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
