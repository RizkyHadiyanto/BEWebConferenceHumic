<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Payment;
use App\Models\Loa;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_no',
        'loa_id',
        'institution',
        'email',
        'date_of_issue',
        'presentation_type',
        'member_type',
        'author_type',
        'amount',
        'status',
        'signature_id',
        'virtual_account_id', 
        'nomor_virtual_akun',
        'bank_transfer_id',
        'beneficiary_bank_account_no',   
        'created_by',
        'picture',
        'nama_penandatangan',
        'jabatan_penandatangan'
    ];

    protected $casts = [
        'author_names' => 'array',
    ];

    // protected $casts = [
    //     'date_of_issue' => 'date'
    // ];

    // App\Models\Invoice
    public function loa()
    {
        return $this->belongsTo(LOA::class, 'loa_id');
    }

    public function signature()
    {
        return $this->belongsTo(Signature::class);
    }

    public function virtualAccount()
    {
        return $this->belongsTo(VirtualAccount::class);
    }

    public function bankTransfer()
    {
        return $this->belongsTo(BankTransfer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected $appends = ['picture_url'];
    public function getPictureUrlAttribute()
    {
        return $this->picture ? asset('storage/' . $this->picture) : null;
    }
}


