<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Signature extends Model
{
    use HasFactory;
    protected $fillable = [
        'picture',
        'nama_penandatangan',
        'jabatan_penandatangan',
        'created_by', // Tambahkan kolom ini
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
