<?php

use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // Jika sudah login, arahkan ke halaman 'list-product'
    if (Auth::check()) {
        return redirect()->route('list-product');
    }
    return view('auth.login');
})->name('login');

Route::middleware('auth')->group(function () {
    // Route::get('/list-product', [ProductsController::class, 'index'])->name('list-product');
    Route::prefix('product')->name('product.')->group(function () {
        Route::get('/', [ProductsController::class, 'index'])->name('index');
    });
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
