<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;


class VirtualAccount extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'nomor_virtual_akun',
        'account_holder_name',
        'bank_name',
        'bank_branch',
        'token',
        'created_by', // Tambahkan kolom ini
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
    // protected static function boot()
    // {
    //     parent::boot();
    //     static::creating(function ($bankTransfer) {
    //         $bankTransfer->token = Str::uuid();
    //     });
    // }
}

