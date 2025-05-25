<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Signature;
use App\Models\InvoiceICODSA;
use Illuminate\Support\Facades\Log;

class LoaICODSA extends Model
{
    use HasFactory;

    protected $table = 'loas_icodsa'; // Tabel yang digunakan

    protected $fillable = [
        'paper_id',
        'paper_title',
        'author_names',
        'status',
        'tempat_tanggal',
        'signature_id',
        'created_by',
        'theme_conference',
        'place_date_conference',
        'picture',
        'nama_penandatangan',
        'jabatan_penandatangan'
        
    ];

    protected $casts = [
        'author_names' => 'array',
    ];

    // Relasi ke User yang membuat LOA
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relasi ke Signature
    public function signature()
    {
        return $this->belongsTo(Signature::class);
    }

    // Relasi ke InvoiceICODSA
    public function invoice()
    {
        return $this->hasOne(InvoiceICODSA::class, 'loa_id');
    }

    protected $appends = ['picture_url'];
    public function getPictureUrlAttribute()
    {
        return $this->picture ? asset('storage/' . $this->picture) : null;
    }
    
    
    /**
     * Contoh jika Anda ingin menyalin logika generate invoice otomatis,
     * silakan sesuaikan sesuai kebutuhan.
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($loa) {
            if ($loa->status === 'Accepted') {
                static::generateInvoice($loa);
            }
        });

        static::updated(function ($loa) {
            if ($loa->status === 'Accepted') {
                if (!InvoiceICODSA::where('loa_id', $loa->id)->exists()) {
                    static::generateInvoice($loa);
                }
            }
        });
    }

   

    protected static function generateInvoice($loa)
    {
        try {
            // $user = User::find($loa->created_by);
            // $role_id = $user?->role_id ?? null;

            // // Tentukan kode konferensi berdasarkan role_id
            // $conferenceCode = match ($role_id) {
            //     2 => 'ICODSA',
            //     default => 'CONF',
            // }; 
            // Contoh penomoran invoice sederhana
            $invoiceNumber = InvoiceICODSA::count() + 1;
            $invoiceCode  = str_pad($invoiceNumber, 3, '0', STR_PAD_LEFT).'/INV/ICODSA/'.date('Y');
            $signature = Signature::find($loa->signature_id);
            //$invoiceCode = str_pad($invoiceNumber, 3, '0', STR_PAD_LEFT) . "/INV/{$conferenceCode}/" . date('Y');

            InvoiceICODSA::create([
                'invoice_no'       => $invoiceCode,
                'loa_id'           => $loa->id,
                'institution'      => null,
                'email'            => null,
                'presentation_type'=> null,
                'member_type'      => null,
                'author_names'     => $loa->author_names,
                'author_type'      => null,
                'amount'           => null,
                'date_of_issue'    => now(),
                'virtual_account_id' => null,
                'bank_transfer_id'   => null,
                'created_by'       => $loa->created_by,
                'signature_id'     => $loa->signature_id,
                'status'           => 'Pending',
                'picture'          => $signature?->picture,
                'nama_penandatangan'=> $signature?->nama_penandatangan,
                'jabatan_penandatangan' => $signature?->jabatan_penandatangan,
            ]);
        } catch (\Exception $e) {
            Log::error('Error generating invoice for LOA ICODSA', ['error' => $e->getMessage()]);
        }
    }
}
