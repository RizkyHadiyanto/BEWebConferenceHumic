<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;


class BankTransfer extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $fillable = [
        'nama_bank',
        'swift_code',
        'recipient_name',
        'beneficiary_bank_account_no',
        'bank_branch',
        'bank_address',
        'city',
        'country',
        'token',
        'created_by',
    ];

    // Generate Token saat membuat akun
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($bankTransfer) {
            $bankTransfer->token = Str::uuid();
        });
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}

