<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class AdminICODSA extends Authenticatable
{
    use HasApiTokens, HasFactory;

    protected $table = 'admin_icodsa';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];  

    protected $hidden = ['password'];

    // Set Default Role ICODSA
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($admin) {
            $admin->role_id = 2; // Role ID untuk Admin ICODSA
        });
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}

