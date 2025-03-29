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
        'bank_transfer_id',   
        'created_by'
    ];

    protected $casts = [
        'date_of_issue' => 'date'
    ];

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
}


