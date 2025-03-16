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

        // //  Buat Tabel Admin ICODSA
        // Schema::create('admin_icodsa', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->string('email')->unique();
        //     $table->string('password');
        //     $table->unsignedBigInteger('role_id'); // Foreign key ke roles.id
        //     $table->timestamps();

        //     // Foreign Key Constraint
        //     $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        // });

        // // Buat Tabel Admin ICICYTA
        // Schema::create('admin_icicyta', function (Blueprint $table) {
        //     $table->id();
        //     $table->string('name');
        //     $table->string('email')->unique();
        //     $table->string('password');
        //     $table->unsignedBigInteger('role_id'); // Foreign key ke roles.id
        //     $table->timestamps();

        //     // **Foreign Key Constraint**
        //     $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        // });

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
        // Buat Tabel Signatures
        Schema::create('signatures', function (Blueprint $table) {
            $table->id();
            $table->string('picture'); // Path gambar atau base64
            $table->string('nama_penandatangan');
            $table->string('jabatan_penandatangan');
            $table->timestamps();
        });

        // Buat Tabel virtual_accounts
        Schema::create('virtual_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_virtual_akun')->unique();
            $table->string('account_holder_name');
            $table->string('bank_name');
            $table->string('bank_branch');
            $table->timestamps();
        });
        // Buat Tabel bank_transfer
        Schema::create('bank_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bank');
            $table->string('swift_code')->nullable();
            $table->string('recipient_name');
            $table->string('beneficiary_bank_account_no');
            $table->string('bank_branch');
            $table->string('bank_address')->nullable();
            $table->string('city')->nullable();
            $table->string('country');
            $table->timestamps();
        });
        //Buat Tabel loas
        Schema::create('loas', function (Blueprint $table) {
            $table->id();
            $table->string('paper_id')->unique();
            $table->string('paper_title');
            $table->json('author_names'); // Simpan sebagai JSON (penulis 1-5)
            $table->enum('status', ['Accepted', 'Rejected']);
            $table->string('tempat_tanggal');
            $table->unsignedBigInteger('signature_id'); // Pakai unsignedBigInteger untuk foreign key
            $table->timestamps();

            // Foreign key
            $table->foreign('signature_id')->references('id')->on('signatures')->onDelete('cascade');
        });
        //Buat Tabel invoices
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loa_id')->constrained('loas')->onDelete('cascade');
            $table->string('institution');
            $table->string('email');
            $table->string('tempat_tanggal');
            $table->foreignId('virtual_account_id')->constrained('virtual_accounts')->onDelete('cascade');
            $table->foreignId('bank_transfer_id')->constrained('bank_transfers')->onDelete('cascade');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
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
        Schema::dropIfExists('signatures');
        Schema::dropIfExists('l_o_a_s');
        Schema::dropIfExists('invoices');
    }
};
