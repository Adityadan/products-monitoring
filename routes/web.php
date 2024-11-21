<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DealersController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\RolesController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Route::get('/', function () {
//     // if (Auth::check()) {
//     //     return redirect()->route('product.index');
//     // }
//     return view('auth.login');
// })->name('login');
// Route::get('/', function () {
//     return redirect()->route('login');
// });

Route::get('/', [AuthenticatedSessionController::class, 'create'])
    ->name('login');

Route::middleware('auth')->group(function () {
    Route::prefix('product')->name('product.')->group(function () {
        Route::get('/', [ProductsController::class, 'index'])->name('index');
        Route::get('/list', [ProductsController::class, 'productList'])->name('list');
        // Route::post('/import', [ProductsController::class, 'import'])->name('import');
        Route::post('/preview', [ProductsController::class, 'preview'])->name('preview');
        Route::post('/import', [ProductsController::class, 'import'])->name('import');
    });
    Route::prefix('dealer')->name('dealer.')->group(function () {
        Route::get('/', [DealersController::class, 'index'])->name('index');
        Route::POST('/import', [DealersController::class, 'importExcel'])->name('import');
        Route::get('/datatable', [DealersController::class, 'datatable'])->name('datatable');
        Route::post('/store', [DealersController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [DealersController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [DealersController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [DealersController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RolesController::class, 'index'])->name('index');
        Route::get('/datatabet', [RolesController::class, 'datatable'])->name('datatable');
        Route::get('/create', [RolesController::class, 'create'])->name('create');
        Route::post('/store', [RolesController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [RolesController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [RolesController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [RolesController::class, 'destroy'])->name('destroy');
    });
    Route::prefix('permissions')->name('permissions.')->group(function () {
        Route::get('/', [PermissionController::class, 'index'])->name('index');
        Route::get('/create', [PermissionController::class, 'create'])->name('create');
        Route::post('/store', [PermissionController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [PermissionController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [PermissionController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [PermissionController::class, 'destroy'])->name('destroy');
    });
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
