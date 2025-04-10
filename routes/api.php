<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Middleware\SuperAdminMiddleware;
use App\Http\Middleware\RoleMiddleware;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BankTransferController;
use App\Http\Controllers\VirtualAccountController;
use App\Http\Controllers\SignatureController;
use App\Http\Controllers\LOAController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\AdminController;
use App\Models\Invoice;


// AUTH
Route::post('/login', [AuthController::class, 'login']);


// SUPERADMIN
Route::middleware(['auth:sanctum', RoleMiddleware::class.':1'])->group(function () {
    
    // Logout superadmin
    Route::post('/logout/superadmin', [AuthController::class, 'logoutsuperadmin']);
    
    // CRUD Admin (ICODSA & ICICYTA)
    Route::post('/admin/icodsa/create', [AdminController::class, 'createAdminICODSA']);
    Route::post('/admin/icicyta/create', [AdminController::class, 'createAdminICICYTA']);
    Route::put('/admin/update/{id}', [AdminController::class, 'updateAdmin']);
    Route::delete('/admin/delete/{id}', [AdminController::class, 'deleteAdmin']);
    Route::get('/admin/list', [AdminController::class, 'listAllAdmins']);
    Route::get('/admin/list/icodsa', [AdminController::class, 'listAdminsICODSA']);
    Route::get('/admin/list/icicyta', [AdminController::class, 'listAdminsICICYTA']);

    // Bank Transfer (CRUD)
    Route::post('/bank-transfer/create', [BankTransferController::class, 'createBankTransfer']);
    Route::put('/bank-transfer/update/{id}', [BankTransferController::class, 'update']);
    Route::delete('/bank-transfer/delete/{id}', [BankTransferController::class, 'destroy']);
    
    // Virtual Account (CRUD)
    Route::post('/virtual-accounts/create', [VirtualAccountController::class, 'createVirtualAccount']);
    Route::put('/virtual-accounts/update/{id}', [VirtualAccountController::class, 'update']);
    Route::delete('/virtual-accounts/delete/{id}', [VirtualAccountController::class, 'destroy']);

    // Signature (CRUD)
    Route::post('/signatures/create', [SignatureController::class, 'store']);
    Route::put('/signatures/update/{id}', [SignatureController::class, 'update']);
    Route::delete('/signatures/delete/{id}', [SignatureController::class, 'destroy']);

    // LOA (CRUD) - pakai LoaController, tapi model = Loa
    // Route::get('/loas', [LoaController::class, 'index']);
    // Route::post('/loas/create', [LoaController::class, 'store']);
    // Route::get('/loas/{id}', [LoaController::class, 'show']);
    // Route::put('/loas/update/{id}', [LoaController::class, 'update']);
    // Route::delete('/loas/delete/{id}', [LoaController::class, 'destroy']);

    // Invoices (CRUD) - pakai InvoiceController, model = Invoice
    // Route::get('/invoices', [InvoiceController::class, 'index']);
    // Route::get('/invoices/{id}', [InvoiceController::class, 'show']);
    // Route::put('/invoices/{id}', [InvoiceController::class, 'update']);
    // (Jika perlu: store, destroy, dll. - sesuaikan)

    // Payments (CRUD) - pakai PaymentController, model = Payment
    // Route::get('/payments', [PaymentController::class, 'index']);
    // Route::get('/payments/{id}', [PaymentController::class, 'show']);
    // (Jika perlu: store, update, destroy, dsb.)
});


// ADMIN ICODSA 


Route::middleware(['auth:sanctum',RoleMiddleware::class.':1,2,3'])->group(function(){
    // Bank Transfer (read only)
    Route::get('/bank-transfer/list', [BankTransferController::class, 'index']);
    Route::get('/bank-transfer/{id}', [BankTransferController::class, 'show']);

    // Virtual Account (read only)
    Route::get('/virtual-accounts/list', [VirtualAccountController::class, 'index']);
    Route::get('/virtual-accounts/{id}', [VirtualAccountController::class, 'show']);

    // Signature (read only)
    Route::get('/signatures', [SignatureController::class, 'index']);
    Route::get('/signatures/{id}', [SignatureController::class, 'show']);
});

Route::middleware(['auth:sanctum',RoleMiddleware::class.':1,2'])->group(function(){
    
    //Loa ICODSA (read only)
    Route::get('/icodsa/loas', [LoaController::class, 'index']);
    Route::get('/icodsa/loas/{id}', [LoaController::class, 'show']);

    //Invoice ICODSA (read only)
    Route::get('/icodsa/invoices', [InvoiceController::class, 'index']);
    Route::get('/icodsa/invoices/{id}', [InvoiceController::class, 'show']);

    //Payment (read only)
    Route::get('/icodsa/payments', [PaymentController::class, 'index']);
    Route::get('/icodsa/payments/{id}', [PaymentController::class, 'show']);
});
Route::middleware(['auth:sanctum', RoleMiddleware::class.':2'])->group(function() {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // // Bank Transfer (read only)
    // Route::get('/bank-transfer/list', [BankTransferController::class, 'index']);
    // Route::get('/bank-transfer/{id}', [BankTransferController::class, 'show']);

    // // Virtual Account (read only)
    // Route::get('/virtual-accounts/list', [VirtualAccountController::class, 'index']);
    // Route::get('/virtual-accounts/{id}', [VirtualAccountController::class, 'show']);

    // Signature (read only)
    // Route::get('/signatures', [SignatureController::class, 'index']);
    // Route::get('/signatures/{id}', [SignatureController::class, 'show']);

    // LOA (CRUD) - pakai LoaController, tapi model = LoaICODSA
    //Route::get('/icodsa/loas', [LoaController::class, 'index']);
    Route::post('/icodsa/loas/create', [LoaController::class, 'store']);
    //Route::get('/icodsa/loas/{id}', [LoaController::class, 'show']);
    Route::put('/icodsa/loas/update/{id}', [LoaController::class, 'update']);
    Route::delete('/icodsa/loas/delete/{id}', [LoaController::class, 'destroy']);

    // Invoice (CRUD) - pakai InvoiceController, model = InvoiceICODSA
    // Route::get('/icodsa/invoices', [InvoiceController::class, 'index']);
    // Route::get('/icodsa/invoices/{id}', [InvoiceController::class, 'show']);
    Route::put('/icodsa/invoices/update/{id}', [InvoiceController::class, 'update']);
    Route::delete('/icodsa/invoices/delete/{id}', [InvoiceController::class, 'destroy']);
    // (Jika perlu store, destroy, dsb.)

    // Payment (CRUD) - pakai PaymentController, model = PaymentICODSA
    // Route::get('/icodsa/payments', [PaymentController::class, 'index']);
    // Route::get('/icodsa/payments/{id}', [PaymentController::class, 'show']);
    Route::put('/icodsa/payments/update/{id}', [PaymentController::class, 'update']);
    Route::delete('/icodsa/payments/delete/{id}', [PaymentController::class, 'destroy']);
});


// ADMIN ICICYTA (role_id=3)

Route::middleware(['auth:sanctum',RoleMiddleware::class.':1,3'])->group(function(){
    //Loa ICICYTA (read only)
    Route::get('/icicyta/loas', [LoaController::class, 'index']);
    Route::get('/icicyta/loas/{id}', [LoaController::class, 'show']);

    //Invoices ICICYTA (read only)
    Route::get('/icicyta/invoices', [InvoiceController::class, 'index']);
    Route::get('/icicyta/invoices/{id}', [InvoiceController::class, 'show']);

    //Payments ICICYTA (read only)
    Route::get('/icicyta/payments', [PaymentController::class, 'index']);
    Route::get('/icicyta/payments/{id}', [PaymentController::class, 'show']);

});
Route::middleware(['auth:sanctum', RoleMiddleware::class.':3'])->group(function() {

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);


    // LOA (CRUD) - pakai LoaController, tapi model = LoaICICYTA
    // Route::get('/icicyta/loas', [LoaController::class, 'index']);
    Route::post('/icicyta/loas/create', [LoaController::class, 'store']);
    // Route::get('/icicyta/loas/{id}', [LoaController::class, 'show']);
    Route::put('/icicyta/loas/update/{id}', [LoaController::class, 'update']);
    Route::delete('/icicyta/loas/delete/{id}', [LoaController::class, 'destroy']);

    // Invoice (CRUD) - pakai InvoiceController, model = InvoiceICICYTA
    // Route::get('/icicyta/invoices', [InvoiceController::class, 'index']);
    // Route::get('/icicyta/invoices/{id}', [InvoiceController::class, 'show']);
    Route::put('/icicyta/invoices/update/{id}', [InvoiceController::class, 'update']);
    Route::delete('/icicyta/invoices/delete/{id}', [InvoiceController::class, 'destroy']);

    // Payment (CRUD) - pakai PaymentController, model = PaymentICICYTA
    // Route::get('/icicyta/payments', [PaymentController::class, 'index']);
    // Route::get('/icicyta/payments/{id}', [PaymentController::class, 'show']);
    Route::put('/icicyta/payments/update/{id}', [PaymentController::class, 'update']);
    Route::delete('/icicyta/payments/delete/{id}', [PaymentController::class, 'destroy']);
});


// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Route;


// use App\Http\Middleware\SuperAdminMiddleware;
// use App\Http\Middleware\RedirectIfAuthenticated;
// use App\Http\Middleware\RoleMiddleware;
// use Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful;

// use App\Http\Controllers\AdminController;
// use App\Http\Controllers\AuthController;
// use App\Http\Controllers\VirtualAccountController;
// use App\Http\Controllers\BankTransferController;
// use App\Http\Controllers\LOAController;
// use App\Http\Controllers\InvoiceController;
// use App\Http\Controllers\SignatureController;
// use App\Http\Controllers\PaymentController;


// // Login 
// Route::post('/login', [AuthController::class, 'login']);
// //Route::post('/logout', [AuthController::class, 'logout']);//middleware('auth:sanctum')->

// // Semua user bisa logout
// // Route::middleware(['auth:sanctum'])->post('/logout', function(Request $request) {
// //     return response()->json(['user' => $request->user()]);
// // });

// // hanya superadmin yang bisa logout 
// Route::middleware(['auth:sanctum', SuperAdminMiddleware::class])->group(function () {
//     Route::post('/logout/superadmin', [AuthController::class, 'logoutsuperadmin']); //logout superadmin
//     Route::post('/admin/icodsa/create', [AdminController::class, 'createAdminICODSA']);
//     Route::post('/admin/icicyta/create', [AdminController::class, 'createAdminICICYTA']);

//     // Update dan delete admin icodsa dan icicyta oleh superadmin 
//     Route::put('/admin/update/{id}', [AdminController::class, 'updateAdmin']);
//     Route::delete('/admin/delete/{id}', [AdminController::class, 'deleteAdmin']);

//     Route::get('/admin/list', [AdminController::class, 'listAllAdmins']);
//     Route::get('/admin/list/icodsa', [AdminController::class, 'listAdminsICODSA']);
//     Route::get('/admin/list/icicyta', [AdminController::class, 'listAdminsICICYTA']);
// });

// // Hanya Super Admin (role_id = 1) bisa menambah, mengedit, dan menghapus
// Route::middleware(['auth:sanctum', RoleMiddleware::class . ':1'])->group(function () {
    
//     Route::post('/bank-transfer/create', [BankTransferController::class, 'createBankTransfer']);
//     Route::put('/bank-transfer/update/{id}', [BankTransferController::class, 'update']);
//     Route::delete('/bank-transfer/delete/{id}', [BankTransferController::class, 'destroy']);
// });

// // Admin ICODSA (role_id = 2) dan Admin ICICYTA (role_id = 3) bisa melihat bank transfer
// Route::middleware(['auth:sanctum', RoleMiddleware::class . ':1,2,3'])->group(function () {
//     Route::post('/logout', [AuthController::class, 'logout']);
//     Route::get('/bank-transfer/list', [BankTransferController::class, 'index']); // Lihat semua bank transfer
//     Route::get('/bank-transfer/{id}', [BankTransferController::class, 'show']); // Lihat detail bank transfer
// });

// //  Super Admin (role_id = 1) bisa CRUD Virtual Account
// Route::middleware(['auth:sanctum', RoleMiddleware::class . ':1'])->group(function () {
//     Route::post('/virtual-accounts/create', [VirtualAccountController::class, 'createVirtualAccount']);
//     Route::put('/virtual-accounts/update/{id}', [VirtualAccountController::class, 'update']);
//     Route::delete('/virtual-accounts/delete/{id}', [VirtualAccountController::class, 'destroy']);
// });

// //  Admin ICODSA (role_id = 2) dan Admin ICICYTA (role_id = 3) hanya bisa melihat daftar Virtual Account
// Route::middleware(['auth:sanctum', RoleMiddleware::class . ':1,2,3'])->group(function () {
//     Route::get('/virtual-accounts/list', [VirtualAccountController::class, 'index']);
//     Route::get('/virtual-accounts/{id}', [VirtualAccountController::class, 'show']);
// });

// //  Super Admin (role_id = 1) bisa CRUD Signature
// Route::middleware(['auth:sanctum',RoleMiddleware::class . ':1'])->group(function () {
//     Route::post('/signatures/create', [SignatureController::class, 'store']);
//     Route::put('/signatures/update/{id}', [SignatureController::class, 'update']);
//     Route::delete('/signatures/delete/{id}', [SignatureController::class, 'destroy']);
// });

// //  Admin ICODSA (role_id = 2) dan Admin ICICYTA (role_id = 3) hanya bisa melihat daftar Signature
// Route::middleware(['auth:sanctum',RoleMiddleware::class . ':1,2,3'])->group(function () {
//     Route::get('/signatures', [SignatureController::class, 'index']);
//     Route::get('/signatures/{id}', [SignatureController::class, 'show']);
// });


// //  Hanya Super Admin bisa CRUD semua LOA
// Route::middleware(['auth:sanctum', RoleMiddleware::class . ':1'])->group(function () {
//     Route::get('/loas', [LOAController::class, 'index']);
//     Route::post('/loas/create', [LOAController::class, 'store']);
//     Route::get('/loas/{id}', [LOAController::class, 'show']);
//     Route::put('/loas/update/{id}', [LOAController::class, 'update']);
//     Route::delete('/loas/delete/{id}', [LOAController::class, 'destroy']);
// });

// //  Admin ICODSA & ICICYTA hanya bisa CRUD LOA yang dibuat sendiri
// Route::middleware(['auth:sanctum', RoleMiddleware::class . ':2,3'])->group(function () {
//     Route::get('/loas/admin', [LOAController::class, 'index']);
//     Route::post('/loas/create', [LOAController::class, 'store']);
//     Route::get('/loas/{id}', [LOAController::class, 'show']);
//     Route::put('/loas/update/{id}', [LOAController::class, 'update']);
//     Route::delete('/loas/delete/{id}', [LOAController::class, 'destroy']);
// });

// // Superadmin bisa melihat semua Invoice & Payment
// Route::middleware(['auth:sanctum', RoleMiddleware::class . ':1'])->group(function () {
//     Route::get('/invoices', [InvoiceController::class, 'index']);
//     Route::get('/invoices/{id}', [InvoiceController::class, 'show']);
//     Route::get('/payments', [PaymentController::class, 'index']);
//     Route::get('/payments/{id}', [PaymentController::class, 'show']);
// });

// // Admin hanya bisa CRUD invoice milik mereka
// Route::middleware(['auth:sanctum', RoleMiddleware::class . ':1,2'])->group(function () {
//     Route::get('/invoices/icodsa/{id}', [InvoiceController::class, 'show']);
//     Route::put('/invoices/update/icodsa/{id}', [InvoiceController::class, 'update']);
// });
// Route::middleware(['auth:sanctum', RoleMiddleware::class . ':1,3'])->group(function () {
//     Route::get('/invoices/icicyta/{id}', [InvoiceController::class, 'show']);
//     Route::put('/invoices/update/icicyta/{id}', [InvoiceController::class, 'update']);
// });

// // Semua user bisa melihat payment milik mereka
// Route::middleware(['auth:sanctum', RoleMiddleware::class . ':2,3'])->group(function () {
//     Route::get('/payments/{id}', [PaymentController::class, 'show']);
// });