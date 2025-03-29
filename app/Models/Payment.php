<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    // protected $fillable = [
    //     'received_from',
    //     'amount',
    //     'in_payment_of',
    //     'payment_date',
    //     'invoice_no',
    //     'paper_id',
    //     'paper_title',
    //     'signature_id',
    //     'created_by',
    // ];
    protected $fillable = [
        'invoice_id',
        'invoice_no',
        'received_from',
        'amount',
        'in_payment_of',
        'payment_date',
        'paper_id',
        'paper_title',
        'signature_id',
        'created_by',
    ];

    // Relasi ke Invoice (Payment berasal dari Invoice)
    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_no', 'invoice_no');
    }

    // Relasi ke Signature (Tanda tangan yang dipakai di Payment)
    public function signature()
    {
        return $this->belongsTo(Signature::class);
    }

    // Relasi ke User (Siapa yang membuat Payment)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
}
