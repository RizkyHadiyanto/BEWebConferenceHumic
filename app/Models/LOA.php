<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Invoice;
use App\Models\User;
use App\Models\Signature;
use Illuminate\Support\Facades\Log;

class Loa extends Model
{
    use HasFactory;

    protected $table = 'loas'; // Menetapkan nama tabel secara eksplisit

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

    // Relasi ke Invoice
    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    protected $appends = ['picture_url'];
    public function getPictureUrlAttribute()
    {
        return $this->picture ? asset('storage/' . $this->picture) : null;
    }
    

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
                // Cegah duplikat
                if (!Invoice::where('loa_id', $loa->id)->exists()) {
                    static::generateInvoice($loa);
                }
            }
        });
    }

    protected static function generateInvoice($loa)
    {
        try {
            $user = User::find($loa->created_by);
            $role_id = $user?->role_id ?? null;

            // Tentukan kode konferensi berdasarkan role_id
            $conferenceCode = match ($role_id) {
                2 => 'ICODSA',
                3 => 'ICICYTA',
                default => 'CONF',
            };

            $invoiceNumber = Invoice::count() + 1;
            $invoiceCode = str_pad($invoiceNumber, 3, '0', STR_PAD_LEFT) . "/INV/{$conferenceCode}/" . date('Y');

            Invoice::create([
                'invoice_no' => $invoiceCode,
                'loa_id' => $loa->id,
                'institution' => null,
                'email' => null,
                'presentation_type' => null,
                'member_type' => null,
                'author_names'     => $loa->author_names,
                'author_type' => null,
                'amount' => null,
                'date_of_issue' => now(),
                'virtual_account_id' => null,
                'bank_transfer_id' => null,
                'picture'          => null,
                'nama_penandatangan'=> null,
                'jabatan_penandatangan' => null,
                'created_by' => $loa->created_by,
                'signature_id' => $loa->signature_id,
                'status' => 'Pending',
            ]);

            Log::info(" Invoice generated for LOA ID {$loa->id} by {$conferenceCode}");
        } catch (\Exception $e) {
            Log::error(' Error generating invoice for LOA', ['error' => $e->getMessage()]);
        }
    }

    // protected static function generateInvoice($loa)
    // {
    //     $invoiceNumber = Invoice::count() + 1;
    //     $invoiceCode = str_pad($invoiceNumber, 3, '0', STR_PAD_LEFT) . "/INV/ICODSA/" . date('Y');

    //     Invoice::create([
    //         'invoice_no' => $invoiceCode,
    //         'loa_id' => $loa->id,
    //         'institution' => 'Some Institution',
    //         'email' => 'example@domain.com',
    //         'tempat_tanggal' => now(),
    //         'presentation_type' => 'Onsite', // default
    //         'member_type' => 'IEEE Member', // default
    //         'author_type' => 'Author', // default
    //         'amount' => 360.00, // default
    //         'date_of_issue' => now(),
    //         'virtual_account_id' => 1,
    //         'bank_transfer_id' => 1,
    //         'created_by' => $loa->created_by,
    //         'signature_id' => $loa->signature_id,
    //         'status' => 'Pending',
    //     ]);
    // }
    

    //TERBARU generateInvoice

    // protected static function generateInvoice($loa)
    // {
    //     $invoiceNumber = Invoice::count() + 1;
    //     $invoiceCode = str_pad($invoiceNumber, 3, '0', STR_PAD_LEFT) . "/INV/ICODSA/" . date('Y');

    //     Invoice::create([
    //         'invoice_no' => $invoiceCode,
    //         'loa_id' => $loa->id,
    //         'institution' => null,
    //         'email' => null,
    //         'tempat_tanggal' => now(),
    //         'presentation_type' => null, // default
    //         'member_type' => null, // default
    //         'author_type' => null, // default
    //         'amount' => null, // default
    //         'date_of_issue' => now(),
    //         'virtual_account_id' => 1,
    //         'bank_transfer_id' => 1,
    //         'created_by' => $loa->created_by,
    //         'signature_id' => $loa->signature_id, 
    //         'status' => 'Pending',
    //     ]);
    // }

    // Event saat LOA diterima, buat Invoice secara otomatis
    // protected static function boot()
    // {
    //     parent::boot();

    //     static::updated(function ($loa) {
    //         if ($loa->status == 'Accepted') {
    //             $existingInvoice = Invoice::where('loa_id', $loa->id)->first();
    //             if (!$existingInvoice) {
    //                 $invoiceNumber = Invoice::count() + 1;
    //                 $invoiceCode = str_pad($invoiceNumber, 3, '0', STR_PAD_LEFT) . "/INV/ICODSA/" . date('Y');

    //                 // Simulasi nilai default
    //                 $presentationType = 'Onsite'; // Atau bisa dari input LOA
    //                 $memberType = 'IEEE Non Member';
    //                 $authorType = 'Student Author';

    //                 // Pricing logic
    //                 $pricing = [
    //                     'Onsite' => [
    //                         'IEEE Member' => ['Author' => 500, 'Student Author' => 400],
    //                         'IEEE Non Member' => ['Author' => 450, 'Student Author' => 360]
    //                     ],
    //                     'Online' => [
    //                         'IEEE Member' => ['Author' => 300, 'Student Author' => 250],
    //                         'IEEE Non Member' => ['Author' => 270, 'Student Author' => 220]
    //                     ],
    //                 ];

    //                 $amount = $pricing[$presentationType][$memberType][$authorType];

    //                 Invoice::create([
    //                     'invoice_no' => $invoiceCode,
    //                     'loa_id' => $loa->id,
    //                     'institution' => 'Telkom University',
    //                     'email' => 'example@domain.com',
    //                     'date_of_issue' => now(),
    //                     'presentation_type' => $presentationType,
    //                     'member_type' => $memberType,
    //                     'author_type' => $authorType,
    //                     'amount' => $amount,
    //                     'signature_id' => $loa->signature_id,
    //                     'created_by' => $loa->created_by,
    //                     'status' => 'Pending',
    //                 ]);
    //             }
    //         }
    //     });
    // }

}

// <?php

// namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
// use App\Models\Invoice;
// use App\Models\User;
// use App\Models\Signature;
// use Illuminate\Support\Facades\Log;

// class LOA extends Model
// {
//     use HasFactory;

//     protected $table = 'loas';

//     protected $fillable = [
//         'paper_id',
//         'paper_title',
//         'author_names',
//         'status',
//         'tempat_tanggal',
//         'signature_id',
//         'created_by',
//     ];

//     protected $casts = [
//         'author_names' => 'array',
//     ];

//     public function creator()
//     {
//         return $this->belongsTo(User::class, 'created_by');
//     }

//     public function signature()
//     {
//         return $this->belongsTo(Signature::class);
//     }

//     public function invoice()
//     {
//         return $this->hasOne(Invoice::class);
//     }

//     protected static function boot()
//     {
//         parent::boot();

//         static::created(function ($loa) {
//             if ($loa->status === 'Accepted') {
//                 static::generateInvoice($loa);
//             }
//         });

//         static::updated(function ($loa) {
//             if ($loa->status === 'Accepted' && !Invoice::where('loa_id', $loa->id)->exists()) {
//                 static::generateInvoice($loa);
//             }
//         });
//     }

//     protected static function generateInvoice($loa)
//     {
//         try {
//             $user = User::find($loa->created_by);
//             $role_id = $user?->role_id ?? null;

//             // Tentukan kode konferensi berdasarkan role_id
//             $conferenceCode = match ($role_id) {
//                 2 => 'ICODSA',
//                 3 => 'ICICYTA',
//                 default => 'CONF',
//             };

//             $invoiceNumber = Invoice::count() + 1;
//             $invoiceCode = str_pad($invoiceNumber, 3, '0', STR_PAD_LEFT) . "/INV/{$conferenceCode}/" . date('Y');

//             Invoice::create([
//                 'invoice_no' => $invoiceCode,
//                 'loa_id' => $loa->id,
//                 'institution' => null,
//                 'email' => null,
//                 'presentation_type' => null,
//                 'member_type' => null,
//                 'author_type' => null,
//                 'amount' => null,
//                 'date_of_issue' => now(),
//                 'virtual_account_id' => null,
//                 'bank_transfer_id' => null,
//                 'created_by' => $loa->created_by,
//                 'signature_id' => $loa->signature_id,
//                 'status' => 'Pending',
//             ]);

//             Log::info(" Invoice generated for LOA ID {$loa->id} by {$conferenceCode}");
//         } catch (\Exception $e) {
//             Log::error(' Error generating invoice for LOA', ['error' => $e->getMessage()]);
//         }
//     }
// }
