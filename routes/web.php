<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProfileController;
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
        Route::post('/import', [ProductsController::class, 'import'])->name('import');
    });
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
