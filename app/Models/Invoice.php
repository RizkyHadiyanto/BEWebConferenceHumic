<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;
    protected $fillable = [
        'loa_id',
        'institution',
        'email',
        'tempat_tanggal',
        'virtual_account_id',
        'bank_transfer_id',
        'created_by', // Tambahkan kolom ini
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function loa()
    {
        return $this->belongsTo(LOA::class);
    }

    public function virtualAccount()
    {
        return $this->belongsTo(VirtualAccount::class);
    }

    public function bankTransfer()
    {
        return $this->belongsTo(BankTransfer::class);
    }
}

