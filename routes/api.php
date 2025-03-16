<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// use App\Http\Controllers\SuperAdminMiddleware;
use App\Http\Middleware\SuperAdminMiddleware;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\RoleMiddleware;
use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VirtualAccountController;
use App\Http\Controllers\BankTransferController;
use App\Http\Controllers\LOAController;
use App\Http\Controllers\InvoiceController;


use App\Http\Controllers\SignatureController;


// ✅ Login & Logout
Route::post('/login', [AuthController::class, 'login']);
//Route::post('/logout', [AuthController::class, 'logout']);//middleware('auth:sanctum')->

// Semua user bisa logout
Route::middleware(['auth:sanctum'])->post('/logout', function(Request $request) {
    return response()->json(['user' => $request->user()]);
});

// hanya superadmin yang bisa logout ?
Route::middleware(['auth:sanctum', SuperAdminMiddleware::class])->group(function () {
    Route::post('/logout/superadmin', [AuthController::class, 'logoutsuperadmin']); //logout superadmin
    Route::post('/admin/icodsa/create', [AdminController::class, 'createAdminICODSA']);
    Route::post('/admin/icicyta/create', [AdminController::class, 'createAdminICICYTA']);

    // Update dan delete admin icodsadan icicyta oleh superadmin 
    Route::put('/admin/update/{id}', [AdminController::class, 'updateAdmin']);
    Route::delete('/admin/delete/{id}', [AdminController::class, 'deleteAdmin']);

    Route::get('/admin/list', [AdminController::class, 'listAllAdmins']);
    Route::get('/admin/list/icodsa', [AdminController::class, 'listAdminsICODSA']);
    Route::get('/admin/list/icicyta', [AdminController::class, 'listAdminsICICYTA']);


});

Route::middleware(['auth:sanctum', RoleMiddleware::class . ':2,3'])->group(function () {
    Route::get('/loa', [LOAController::class, 'index']);
    Route::get('/invoice', [InvoiceController::class, 'index']);
});


// ✅ Super Admin Routes (Role ID = 1)

// Route::middleware(['auth:sanctum', SuperAdminMiddleware::class])->group(function () {
//     //Route::post('/login', [AuthController::class, 'login']); //done->middleware('auth:sanctum');
//     //Route::post('/logout', [AuthController::class, 'logout']); //done->middleware('auth:sanctum');

//     //Route::post('/admin/icodsa/create', [AdminController::class, 'createAdminICODSA']);
//     // Route::post('/admin/icicyta/create', [AdminController::class, 'createAdminICICYTA']);
    
//     // Route::get('/admin/list', [AdminController::class, 'listAllAdmins']);
//     // Route::get('/admin/list/icodsa', [AdminController::class, 'listAdminsICODSA']);
//     // Route::get('/admin/list/icicyta', [AdminController::class, 'listAdminsICICYTA']);

//     Route::post('/bank-transfer', [BankTransferController::class, 'store']);
//     Route::post('/virtual-account', [VirtualAccountController::class, 'store']);
//     Route::post('/loa', [LOAController::class, 'store']);
//     Route::post('/invoice', [InvoiceController::class, 'store']);
// });

// ✅ Admin ICODSA & ICICYTA (Role ID = 2 & 3) Bisa Lihat LOA & Invoice
// Route::middleware(['auth:sanctum', RoleMiddleware::class . ':2,3'])->group(function () {
//     Route::get('/loa', [LOAController::class, 'index']);
//     Route::get('/invoice', [InvoiceController::class, 'index']);
// });

// Route untuk login

// Route::post('/login', [AuthController::class, 'login']); //done
//Route logout dengan middleware Sanctum
// Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum'); //done

// // Group API untuk Super Admin
// Route::middleware(['auth:sanctum', SuperAdminMiddleware::class])->group(function () {

//     Route::post('/login', [AuthController::class, 'login'])->middleware('auth:sanctum');; //done
//     Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');; //done
    
//     Route::post('/admin/icodsa/create', [AdminController::class, 'createAdminICODSA']); //done ->middleware('role:superadmin')

//     Route::post('/admin/icicyta/create', [AdminController::class, 'createAdminICICYTA']); //done ->middleware('role:superadmin')
    
//     //List Super admin
//     Route::get('/admin/list', [AdminController::class, 'listAllAdmins']);
//     Route::get('/admin/list/icodsa', [AdminController::class, 'listAdminsICODSA']);
//     Route::get('/admin/list/icicyta', [AdminController::class, 'listAdminsICICYTA']);

//     // Route::post('/signature', [AdminController::class, 'createSignature']);
//     // Route::post('/bank-transfer', [AdminController::class, 'createBankTransfer']);
//     // Route::post('/virtual-account', [AdminController::class, 'createVirtualAccount']);
//     // Route::post('/loa', [AdminController::class, 'createLOA']);
//     // Route::post('/invoice', [AdminController::class, 'createInvoice']);
// });

// Route::middleware(['auth:sanctum', 'superadmin'])->group(function () {
//     Route::apiResource('invoices', InvoiceController::class);
//     Route::apiResource('bank-transfers', BankTransferController::class);
//     Route::apiResource('virtual-accounts', VirtualAccountController::class);
// });

// Virtual Account
// Middleware auth untuk memastikan hanya superadmin yang bisa mengakses

// Group Admin ICODSA
// Route::middleware(['auth:sanctum', RoleMiddleware::class . ':admin_icodsa'])->group(function () {
//     Route::get('/dashboard/icodsa', function () {
//         return response()->json(['message' => 'Welcome to ICODSA Dashboard']);
//     });
// });

// // Group Admin ICICYTA
// Route::middleware(['auth:sanctum', RoleMiddleware::class . ':admin_icicyta'])->group(function () {
//     Route::get('/dashboard/icicyta', function () {
//         return response()->json(['message' => 'Welcome to ICICYTA Dashboard']);
//     });
// });



// Route::middleware(['auth:sanctum', RoleMiddleware::class . ':1'])->group(function () {
//     Route::post('/signature', [SignatureController::class, 'store']);
//     Route::post('/bank-transfer', [BankTransferController::class, 'store']);
//     Route::post('/virtual-account', [VirtualAccountController::class, 'store']);
//     Route::post('/loa', [LOAController::class, 'store']);
//     Route::post('/invoice', [InvoiceController::class, 'store']);
// });

// Route::middleware(['auth:sanctum', RoleMiddleware::class . ':2,3'])->group(function () {
//     Route::get('/loa', [LOAController::class, 'index']);
//     Route::get('/invoice', [InvoiceController::class, 'index']);
// });

// ✨ Super Admin (role_id = 1) bisa mengakses semua API
// Route::middleware(['auth:sanctum', 'role:1'])->group(function () {
//     Route::apiResource('invoices', InvoiceController::class);
//     Route::apiResource('loas', LOAController::class);
//     Route::apiResource('bank-transfers', BankTransferController::class);
//     Route::apiResource('virtual-accounts', VirtualAccountController::class);
//     Route::apiResource('admins', AdminController::class);
// });

// // ✨ Admin ICODSA (role_id = 2) hanya bisa mengakses Invoices & LOAs
// Route::middleware(['auth:sanctum', 'role:1,2'])->group(function () {
//     Route::apiResource('invoices', InvoiceController::class);
//     Route::apiResource('loas', LOAController::class);
// });

// // ✨ Admin ICICYTA (role_id = 3) hanya bisa mengakses Invoices & LOAs
// Route::middleware(['auth:sanctum', 'role:1,3'])->group(function () {
//     Route::apiResource('invoices', InvoiceController::class);
//     Route::apiResource('loas', LOAController::class);
// });

// Create API
// Route::post('/admin/icodsa/create', [AdminController::class, 'createAdminICODSA']); //done
// Route::post('/admin/icicyta/create', [AdminController::class, 'createAdminICICYTA']); //done

// Bank Transfer
// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('/bank-transfer/create', [BankTransferController::class, 'store']);
//     Route::get('/bank-transfer/list', [BankTransferController::class, 'index']);
// });
// Route::middleware('auth:sanctum')->group(function () {
//     Route::post('/virtual-account/create', [VirtualAccountController::class, 'store']);
//     Route::get('/virtual-account/list', [VirtualAccountController::class, 'index']);
// });


// Super Admin dapat mengakses semua admin
// Route::middleware(['auth:sanctum', 'role:superadmin'])->group(function () {
//     Route::post('/admin/create', [AdminController::class, 'createAdmin']);
//     Route::get('/admin/list', [AdminController::class, 'listAdmins']);
// });

// // Login Route
// Route::get('/login', [AuthController::class, 'login'])->name('login');
// Route::post('/login', [AuthController::class, 'authenticate']);

// // Dashboard Redirect
// Route::middleware(['auth', 'redirect.auth'])->group(function () {
//     Route::get('/dashboard/icodsa', function () {
//         return view('dashboard.icodsa');
//     });

//     Route::get('/dashboard/icicyta', function () {
//         return view('dashboard.icicyta');
//     });

//     Route::get('/dashboard/superadmin', function () {
//         return view('dashboard.superadmin');
//     });
// });

// Route::middleware(['auth:sanctum', 'role:super_admin'])->group(function () {
//     Route::get('/admin/icodsa', [AdminController::class, 'listICODSA']); // List Admin ICODSA
//     Route::get('/admin/icicyta', [AdminController::class, 'listICICYTA']); // List Admin ICICYTA
// });

// Route::middleware(['auth:sanctum', 'role:superadmin,admin_icodsa'])->group(function () {
//     Route::get('/admin/list', [AdminController::class, 'listAllAdmins']);
// });

// // Admin ICODSA hanya bisa melihat ICODSA
// Route::middleware(['auth:sanctum', 'role:admin_icodsa'])->group(function () {
//     Route::get('/icodsa/dashboard', [AdminController::class, 'icodsaDashboard']);
// });

// // Admin ICICYTA hanya bisa melihat ICICYTA
// Route::middleware(['auth:sanctum', 'role:admin_icicyta'])->group(function () {
//     Route::get('/icicyta/dashboard', [AdminController::class, 'icicytaDashboard']);
// });




// Route default untuk mengecek apakah API berjalan
Route::get('/', function(){
    return response()->json(['message' => 'API is running'], 200);
});


