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
        'tanggal_dibuat',
        'created_by', // Tambahkan kolom ini
        
    ];

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
