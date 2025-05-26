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

        // Buat Tabel Signatures
        Schema::create('signatures', function (Blueprint $table) {
            $table->id();
            $table->string('picture'); // Path gambar atau base64
            $table->string('nama_penandatangan');
            $table->string('jabatan_penandatangan');
            $table->date('tanggal_dibuat');
            $table->timestamps();
        });

        // Buat Tabel virtual_accounts
        Schema::create('virtual_accounts', function (Blueprint $table) {
            $table->id();
            $table->string('nomor_virtual_akun')->unique();
            $table->string('account_holder_name');
            $table->string('bank_name');
            $table->string('bank_branch');
            // $table->uuid('token')->unique(); // Unique identifier untuk keamanan
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
        });

        // Buat Tabel bank_transfer
        Schema::create('bank_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('nama_bank');
            $table->string('swift_code')->nullable();
            $table->string('recipient_name');
            $table->string('beneficiary_bank_account_no')->unique();
            $table->string('bank_branch');
            $table->string('bank_address')->nullable();
            $table->string('city')->nullable();
            $table->string('country');
            // $table->uuid('token')->unique(); 
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); 
            $table->timestamps();
        });

        // Buat Tabel loas
        Schema::create('loas_icodsa', function (Blueprint $table) {
            $table->id();
            $table->string('paper_id')->unique();
            $table->string('paper_title');
            $table->json('author_names'); // Simpan sebagai JSON (penulis 1-5)
            $table->enum('status', ['Accepted', 'Rejected']);
            $table->string('tempat_tanggal');
            $table->string('picture')->nullable(); // Path gambar atau base64
            $table->string('nama_penandatangan')->nullable();
            $table->string('jabatan_penandatangan')->nullable();
            $table->unsignedBigInteger('signature_id');
            $table->string('theme_conference')->nullable();
            $table->string('place_date_conference')->nullable();
            $table->unsignedBigInteger('created_by'); // Siapa yang membuat LOA
            $table->timestamps();

            // Foreign keys
            $table->foreign('signature_id')->references('id')->on('signatures')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });

        // Buat Tabel invoices
        Schema::create('invoices_icodsa', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique();
            $table->foreignId('loa_id')->constrained('loas_icodsa')->onDelete('cascade');
            $table->string('paper_id')->nullable();
            $table->string('paper_title')->nullable();
            $table->string('institution')->nullable();
            $table->string('email')->nullable();
            $table->string('presentation_type')->nullable();
            $table->string('member_type')->nullable();
            $table->json('author_names')->nullable(); 
            $table->string('author_type')->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->date('date_of_issue')->nullable();
            $table->foreignId('signature_id')->constrained('signatures')->onDelete('cascade');
            $table->foreignId('virtual_account_id')->nullable();
            $table->string('nomor_virtual_akun')->nullable();
            $table->foreignId('bank_transfer_id')->nullable();
            $table->string('beneficiary_bank_account_no')->nullable();
            $table->string('picture')->nullable(); // Path gambar atau base64
            $table->string('nama_penandatangan')->nullable();
            $table->string('jabatan_penandatangan')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['Pending', 'Paid'])->default('Pending');
            $table->timestamps();
        });

        // Buat Tabel payments
        Schema::create('payments_icodsa', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no');
            $table->string('received_from')->nullable();
            $table->decimal('amount', 15, 2);
            $table->string('in_payment_of');
            $table->date('payment_date');
            $table->string('paper_id')->nullable();
            $table->string('paper_title')->nullable();
            $table->string('picture')->nullable(); // Path gambar atau base64
            $table->string('nama_penandatangan')->nullable();
            $table->string('jabatan_penandatangan')->nullable();
            $table->foreignId('signature_id')->constrained('signatures')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });

        // Buat Tabel loas_icicyta
        Schema::create('loas_icicyta', function (Blueprint $table) {
            $table->id();
            $table->string('paper_id')->unique();
            $table->string('paper_title');
            $table->json('author_names'); // Simpan sebagai JSON (penulis 1-5)
            $table->enum('status', ['Accepted', 'Rejected']);
            $table->string('tempat_tanggal');
            $table->string('picture')->nullable(); // Path gambar atau base64
            $table->string('nama_penandatangan')->nullable();
            $table->string('jabatan_penandatangan')->nullable();
            $table->unsignedBigInteger('signature_id');
            $table->unsignedBigInteger('created_by'); // Siapa yang membuat LOA
            $table->string('theme_conference')->nullable();
            $table->string('place_date_conference')->nullable();
            $table->timestamps();
            // Foreign keys
            $table->foreign('signature_id')->references('id')->on('signatures')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
        });

        // Buat Tabel invoices_icicyta
        Schema::create('invoices_icicyta', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no')->unique();
            $table->foreignId('loa_id')->constrained('loas_icicyta')->onDelete('cascade');
            $table->string('paper_id')->nullable();
            $table->string('paper_title')->nullable();
            $table->string('institution')->nullable();
            $table->string('email')->nullable();
            $table->string('presentation_type')->nullable();
            $table->string('member_type')->nullable();
            $table->json('author_names')->nullable(); 
            $table->string('author_type')->nullable();
            $table->decimal('amount', 15, 2)->nullable();
            $table->date('date_of_issue')->nullable();
            $table->foreignId('signature_id')->constrained('signatures')->onDelete('cascade');
            $table->foreignId('virtual_account_id')->nullable();
            $table->string('nomor_virtual_akun')->nullable();
            $table->foreignId('bank_transfer_id')->nullable();
            $table->string('beneficiary_bank_account_no')->nullable();
            $table->string('picture')->nullable(); // Path gambar atau base64
            $table->string('nama_penandatangan')->nullable();
            $table->string('jabatan_penandatangan')->nullable();
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->enum('status', ['Pending', 'Paid'])->default('Pending');
            $table->timestamps();
        });

        // Buat Tabel payments_icicyta
        Schema::create('payments_icicyta', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_no');
            $table->string('received_from')->nullable();
            $table->decimal('amount', 15, 2);
            $table->string('in_payment_of');
            $table->date('payment_date');
            $table->string('paper_id')->nullable();
            $table->string('paper_title')->nullable();
            $table->string('picture')->nullable(); // Path gambar atau base64
            $table->string('nama_penandatangan')->nullable();
            $table->string('jabatan_penandatangan')->nullable();
            $table->foreignId('signature_id')->constrained('signatures')->onDelete('cascade');
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        
        Schema::dropIfExists('payments_icicyta');
        Schema::dropIfExists('invoices_icicyta');
        Schema::dropIfExists('loas_icicyta');
        Schema::dropIfExists('payments_icodsa');
        Schema::dropIfExists('invoices_icodsa');
        Schema::dropIfExists('loas_icodsa');
        Schema::dropIfExists('bank_transfers');
        Schema::dropIfExists('virtual_accounts');
        Schema::dropIfExists('signatures');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('admin_icicyta');   
        Schema::dropIfExists('admin_icodsa');    
        // Schema::dropIfExists('users');        
        Schema::dropIfExists('roles');
    }
};
