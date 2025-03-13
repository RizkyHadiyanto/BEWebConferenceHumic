<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     */
    public function up(): void
    {
        // Buat Tabel Roles Terlebih Dahulu
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Contoh: superadmin, admin_icodsa, admin_icicyta
            $table->timestamps();
        });

        //  Buat Tabel Users (Hanya untuk Super Admin)
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->unsignedBigInteger('role_id'); // Foreign key ke roles.id
            $table->rememberToken();
            $table->timestamps();

            // Foreign Key Constraint
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });

        //  Buat Tabel Admin ICODSA
        Schema::create('admin_icodsa', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->unsignedBigInteger('role_id'); // Foreign key ke roles.id
            $table->timestamps();

            // Foreign Key Constraint
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });

        // Buat Tabel Admin ICICYTA
        Schema::create('admin_icicyta', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->unsignedBigInteger('role_id'); // Foreign key ke roles.id
            $table->timestamps();

            // **Foreign Key Constraint**
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });

        // Buat Tabel Password Reset
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // Buat Tabel Sessions
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Rollback migrasi.
     */
    public function down(): void
    {
        // **Hapus tabel dari yang paling terakhir dibuat**
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('admin_icicyta');
        Schema::dropIfExists('admin_icodsa');
        Schema::dropIfExists('users');
        Schema::dropIfExists('roles');
    }
};
