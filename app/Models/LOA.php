<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LOA extends Model
{
    use HasFactory;
    protected $fillable = [
        'paper_id',
        'paper_title',
        'author_names',
        'status',
        'tempat_tanggal',
        'signature_id',
        'created_by', // Tambahkan kolom ini
    ];

    protected $casts = ['author_names' => 'array'];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function signature()
    {
        return $this->belongsTo(Signature::class);
    }

    public function invoices()
    {
        return $this->hasOne(Invoice::class);
    }
}

