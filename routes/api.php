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



Route::options('{any}', function () {
    return response()->json(['status' => 'CORS preflight passed']);
})->where('any', '.*');

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
    Route::post('/signatures/update/{id}', [SignatureController::class, 'update']);
    Route::delete('/signatures/delete/{id}', [SignatureController::class, 'destroy']);

   
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

     // Edit Admin Icodsa
    Route::put('/icodsa/adminicodsa/update/{id}', [AdminController::class, 'updateAdmin']);

    

    Route::post('/icodsa/loas/create', [LoaController::class, 'store']);
    Route::put('/icodsa/loas/update/{id}', [LoaController::class, 'update']);
    Route::delete('/icodsa/loas/delete/{id}', [LoaController::class, 'destroy']);
    Route::get('/icodsa/loas/download/{id}', [LoaController::class, 'downloadLOA']);
    Route::put('/icodsa/invoices/update/{id}', [InvoiceController::class, 'update']);
    Route::delete('/icodsa/invoices/delete/{id}', [InvoiceController::class, 'destroy']);
    Route::get('/icodsa/invoice/download/{id}', [InvoiceController::class, 'downloadInvoice']);
    Route::put('/icodsa/payments/update/{id}', [PaymentController::class, 'update']);
    Route::delete('/icodsa/payments/delete/{id}', [PaymentController::class, 'destroy']);
    Route::get('/icodsa/payment/download/{id}', [PaymentController::class, 'downloadPayment']);
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

    // Edit Admin Icicyta
    Route::put('/icicyta/adminicicyta/update/{id}', [AdminController::class, 'updateAdmin']);

    Route::post('/icicyta/loas/create', [LoaController::class, 'store']);
    Route::put('/icicyta/loas/update/{id}', [LoaController::class, 'update']);
    Route::delete('/icicyta/loas/delete/{id}', [LoaController::class, 'destroy']);
    Route::get('/icicyta/loas/download/{id}', [LoaController::class, 'downloadLOA']);
    Route::put('/icicyta/invoices/update/{id}', [InvoiceController::class, 'update']);
    Route::delete('/icicyta/invoices/delete/{id}', [InvoiceController::class, 'destroy']);
    Route::get('/icicyta/invoice/download/{id}', [InvoiceController::class, 'downloadInvoice']);
    Route::put('/icicyta/payments/update/{id}', [PaymentController::class, 'update']);
    Route::delete('/icicyta/payments/delete/{id}', [PaymentController::class, 'destroy']);
    Route::get('/icicyta/payment/download/{id}', [PaymentController::class, 'downloadPayment']);
});

