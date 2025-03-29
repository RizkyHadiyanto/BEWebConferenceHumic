<?php


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


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
use App\Http\Controllers\PaymentController;


// Login 
Route::post('/login', [AuthController::class, 'login']);
//Route::post('/logout', [AuthController::class, 'logout']);//middleware('auth:sanctum')->

// Semua user bisa logout
// Route::middleware(['auth:sanctum'])->post('/logout', function(Request $request) {
//     return response()->json(['user' => $request->user()]);
// });

// hanya superadmin yang bisa logout 
Route::middleware(['auth:sanctum', SuperAdminMiddleware::class])->group(function () {
    Route::post('/logout/superadmin', [AuthController::class, 'logoutsuperadmin']); //logout superadmin
    Route::post('/admin/icodsa/create', [AdminController::class, 'createAdminICODSA']);
    Route::post('/admin/icicyta/create', [AdminController::class, 'createAdminICICYTA']);

    // Update dan delete admin icodsa dan icicyta oleh superadmin 
    Route::put('/admin/update/{id}', [AdminController::class, 'updateAdmin']);
    Route::delete('/admin/delete/{id}', [AdminController::class, 'deleteAdmin']);

    Route::get('/admin/list', [AdminController::class, 'listAllAdmins']);
    Route::get('/admin/list/icodsa', [AdminController::class, 'listAdminsICODSA']);
    Route::get('/admin/list/icicyta', [AdminController::class, 'listAdminsICICYTA']);
});

// Hanya Super Admin (role_id = 1) bisa menambah, mengedit, dan menghapus
Route::middleware(['auth:sanctum', RoleMiddleware::class . ':1'])->group(function () {
    
    Route::post('/bank-transfer/create', [BankTransferController::class, 'createBankTransfer']);
    Route::put('/bank-transfer/update/{id}', [BankTransferController::class, 'update']);
    Route::delete('/bank-transfer/delete/{id}', [BankTransferController::class, 'destroy']);
});

// Admin ICODSA (role_id = 2) dan Admin ICICYTA (role_id = 3) bisa melihat bank transfer
Route::middleware(['auth:sanctum', RoleMiddleware::class . ':1,2,3'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/bank-transfer/list', [BankTransferController::class, 'index']); // Lihat semua bank transfer
    Route::get('/bank-transfer/{id}', [BankTransferController::class, 'show']); // Lihat detail bank transfer
});

//  Super Admin (role_id = 1) bisa CRUD Virtual Account
Route::middleware(['auth:sanctum', RoleMiddleware::class . ':1'])->group(function () {
    Route::post('/virtual-accounts/create', [VirtualAccountController::class, 'createVirtualAccount']);
    Route::put('/virtual-accounts/update/{id}', [VirtualAccountController::class, 'update']);
    Route::delete('/virtual-accounts/delete/{id}', [VirtualAccountController::class, 'destroy']);
});

//  Admin ICODSA (role_id = 2) dan Admin ICICYTA (role_id = 3) hanya bisa melihat daftar Virtual Account
Route::middleware(['auth:sanctum', RoleMiddleware::class . ':1,2,3'])->group(function () {
    Route::get('/virtual-accounts/list', [VirtualAccountController::class, 'index']);
    Route::get('/virtual-accounts/{id}', [VirtualAccountController::class, 'show']);
});

//  Super Admin (role_id = 1) bisa CRUD Signature
Route::middleware(['auth:sanctum',RoleMiddleware::class . ':1'])->group(function () {
    Route::post('/signatures/create', [SignatureController::class, 'store']);
    Route::put('/signatures/update/{id}', [SignatureController::class, 'update']);
    Route::delete('/signatures/delete/{id}', [SignatureController::class, 'destroy']);
});

//  Admin ICODSA (role_id = 2) dan Admin ICICYTA (role_id = 3) hanya bisa melihat daftar Signature
Route::middleware(['auth:sanctum',RoleMiddleware::class . ':1,2,3'])->group(function () {
    Route::get('/signatures', [SignatureController::class, 'index']);
    Route::get('/signatures/{id}', [SignatureController::class, 'show']);
});


//  Hanya Super Admin bisa CRUD semua LOA
Route::middleware(['auth:sanctum', RoleMiddleware::class . ':1'])->group(function () {
    Route::get('/loas', [LOAController::class, 'index']);
    Route::post('/loas/create', [LOAController::class, 'store']);
    Route::get('/loas/{id}', [LOAController::class, 'show']);
    Route::put('/loas/update/{id}', [LOAController::class, 'update']);
    Route::delete('/loas/delete/{id}', [LOAController::class, 'destroy']);
});

//  Admin ICODSA & ICICYTA hanya bisa CRUD LOA yang dibuat sendiri
Route::middleware(['auth:sanctum', RoleMiddleware::class . ':2,3'])->group(function () {
    Route::get('/loas/admin', [LOAController::class, 'index']);
    Route::post('/loas/create', [LOAController::class, 'store']);
    Route::get('/loas/{id}', [LOAController::class, 'show']);
    Route::put('/loas/update/{id}', [LOAController::class, 'update']);
    Route::delete('/loas/delete/{id}', [LOAController::class, 'destroy']);
});

// Superadmin bisa melihat semua Invoice & Payment
Route::middleware(['auth:sanctum', RoleMiddleware::class . ':1'])->group(function () {
    Route::get('/invoices', [InvoiceController::class, 'index']);
    Route::get('/invoices/{id}', [InvoiceController::class, 'show']);
    Route::get('/payments', [PaymentController::class, 'index']);
});

// Admin hanya bisa CRUD invoice milik mereka
Route::middleware(['auth:sanctum', RoleMiddleware::class . ':1,2'])->group(function () {
    Route::get('/invoices/icodsa/{id}', [InvoiceController::class, 'show']);
    Route::put('/invoices/update/icodsa/{id}', [InvoiceController::class, 'update']);
});
Route::middleware(['auth:sanctum', RoleMiddleware::class . ':1,3'])->group(function () {
    Route::get('/invoices/icicyta/{id}', [InvoiceController::class, 'show']);
    Route::put('/invoices/update/icicyta/ {id}', [InvoiceController::class, 'update']);
});

// Semua user bisa melihat payment milik mereka
Route::middleware(['auth:sanctum', RoleMiddleware::class . ':1,2,3'])->group(function () {
    Route::get('/payments/{id}', [PaymentController::class, 'show']);
});


