<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

// Route untuk login
Route::post('/login', [AuthController::class, 'login']); //done

// Route logout dengan middleware Sanctum
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']); //done



// Super Admin dapat mengakses semua admin
// Route::middleware(['auth:sanctum', 'role:superadmin'])->group(function () {
//     Route::post('/admin/create', [AdminController::class, 'createAdmin']);
//     Route::get('/admin/list', [AdminController::class, 'listAdmins']);
// });

Route::post('/admin/icodsa/create', [AdminController::class, 'createAdminICODSA']); 
Route::post('/admin/icicyta/create', [AdminController::class, 'createAdminICICYTA']); 


// Group API untuk Super Admin
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/admin/icodsa/create', [AdminController::class, 'createAdminICODSA']); //done
    //->middleware('role:superadmin')
    Route::post('/admin/icicyta/create', [AdminController::class, 'createAdminICICYTA']); //done
    // /->middleware('role:superadmin')
});


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


