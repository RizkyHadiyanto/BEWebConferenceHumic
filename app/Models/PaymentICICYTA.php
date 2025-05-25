<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Signature;
use App\Models\InvoiceICICYTA;

class PaymentICICYTA extends Model
{
    use HasFactory;

    protected $table = 'payments_icicyta'; // Tabel yang digunakan

    protected $fillable = [
        'invoice_no',
        'received_from',
        'amount',
        'in_payment_of',
        'payment_date',
        'paper_id',
        'paper_title',
        'signature_id',
        'created_by',
        'picture',
        'nama_penandatangan',
        'jabatan_penandatangan'
    ];

    // Relasi ke InvoiceICICYTA (berdasarkan invoice_no)
    public function invoice()
    {
        return $this->belongsTo(InvoiceICICYTA::class, 'invoice_no', 'invoice_no');
    }

    // Relasi ke Signature
    public function signature()
    {
        return $this->belongsTo(Signature::class);
    }

    // Relasi ke User (Siapa yang membuat Payment)
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    
    protected $appends = ['picture_url'];
    public function getPictureUrlAttribute()
    {
        return $this->picture ? asset('storage/' . $this->picture) : null;
    }
}