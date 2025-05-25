<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;
use App\Models\Signature;
use App\Models\VirtualAccount;
use App\Models\BankTransfer;
use App\Models\LOAICICYTA;

class InvoiceICICYTA extends Model
{
    use HasFactory;

    protected $table = 'invoices_icicyta'; // Tabel yang digunakan

    protected $fillable = [
        'invoice_no',
        'loa_id',
        'institution',
        'email',
        'presentation_type',
        'member_type',
        'author_names',
        'author_type',
        'amount',
        'date_of_issue',
        'signature_id',
        'virtual_account_id',
        'nomor_virtual_akun',
        'bank_transfer_id',
        'beneficiary_bank_account_no',
        'picture',
        'nama_penandatangan',
        'jabatan_penandatangan',
        'created_by',
        'status',
    ];

    protected $casts = [
        'author_names' => 'array',
    ];

    // Relasi ke LOAICICYTA
    public function loa()
    {
        return $this->belongsTo(LOAICICYTA::class, 'loa_id');
    }

    // Relasi ke Signature
    public function signature()
    {
        return $this->belongsTo(Signature::class);
    }

    // Relasi ke VirtualAccount
    public function virtualAccount()
    {
        return $this->belongsTo(VirtualAccount::class);
    }

    // Relasi ke BankTransfer
    public function bankTransfer()
    {
        return $this->belongsTo(BankTransfer::class);
    }

    // Relasi ke User
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi ke PaymentICICYTA (jika ingin menghubungkan invoice ke payment via invoice_no)
    public function payments()
    {
        return $this->hasMany(PaymentICICYTA::class, 'invoice_no', 'invoice_no');
    }

    protected $appends = ['picture_url'];
    public function getPictureUrlAttribute()
    {
        return $this->picture ? asset('storage/' . $this->picture) : null;
    }
}
