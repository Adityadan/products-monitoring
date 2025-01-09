<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DealerProductsController;
use App\Http\Controllers\DealersController;
use App\Http\Controllers\DistanceDealerController;
use App\Http\Controllers\MasterProductController;
use App\Http\Controllers\MenusController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PermissionAssignmentController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RequestOrderController;
use App\Http\Controllers\RodController;
use App\Http\Controllers\RoleAssignmentController;
use App\Http\Controllers\RolePermissionController;
use App\Http\Controllers\RolesController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\TargetController;
use App\Http\Controllers\UserController;
use App\Models\Dealer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', [AuthenticatedSessionController::class, 'create'])
    ->name('login');

Route::middleware('auth')->group(function () {
    Route::prefix('dashboard')->name('dashboard.')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('index');
        Route::get('/datatable_target', [DashboardController::class, 'datatable_target'])->name('datatable_target');
        Route::get('/chartTarget', [DashboardController::class, 'chartTarget'])->name('chartTarget');
    });
    Route::prefix('product')->name('product.')->group(function () {
        Route::get('/', [ProductsController::class, 'index'])->name('index');
        Route::post('/list', [ProductsController::class, 'productList'])->name('list');
        Route::get('/filter-no-part', [ProductsController::class, 'filterNoPart'])->name('filterNoPart');
        Route::get('/filter-dealer', [ProductsController::class, 'filterDealer'])->name('filterDealer');
        // Route::get('/datatable', [ProductsController::class, 'datatable'])->name('datatable');
    });
    Route::prefix('dealer-product')->name('dealer-product.')->group(function () {
        Route::get('/', [DealerProductsController::class, 'index'])->name('index');
        Route::get('/datatable', [DealerProductsController::class, 'datatable'])->name('datatable');
        Route::post('/preview', [DealerProductsController::class, 'preview'])->name('preview');
        Route::post('/preview-new', [DealerProductsController::class, 'previewNew'])->name('preview-new'); // new method upload when large data
        Route::post('/import', [DealerProductsController::class, 'import'])->name('import');
    });
    Route::prefix('master-product')->name('master-product.')->group(function () {
        Route::get('/', [MasterProductController::class, 'index'])->name('index');
        Route::get('/datatable', [MasterProductController::class, 'datatable'])->name('datatable');
        Route::get('/edit/{id}', [MasterProductController::class, 'edit'])->name('edit');
        Route::post('/store/{id}', [MasterProductController::class, 'store'])->name('store');
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

    Route::prefix('distance-dealer')->name('distance-dealer.')->group(function () {
        Route::get('/', [DistanceDealerController::class, 'index'])->name('index');
        Route::get('/datatable', [DistanceDealerController::class, 'datatable'])->name('datatable');
        Route::post('/update/{id}', [DistanceDealerController::class, 'update'])->name('update');
        Route::post('/saveArea', [DistanceDealerController::class, 'saveArea'])->name('saveArea');
    });
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', [RolesController::class, 'index'])->name('index');
        Route::get('/datatable', [RolesController::class, 'datatable'])->name('datatable');
        Route::get('/create', [RolesController::class, 'create'])->name('create');
        Route::post('/store', [RolesController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [RolesController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [RolesController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [RolesController::class, 'destroy'])->name('destroy');


        Route::post('/assign-permission', [RolesController::class, 'assignPermission'])->name('assign-permission');
        Route::get('/assign-permission/edit/{roles}', [RolesController::class, 'editAssignedPermission'])->name('assign-permission.edit');
        // Route::get('/assign', [RoleAssignmentController::class, 'index'])->name('assign');
        // Route::post('/assign', [RoleAssignmentController::class, 'assign'])->name('assign.store');
        // Route::get('/assign/edit/{user}', [RoleAssignmentController::class, 'editAssignedRoles'])->name('assign.edit');
        // Route::post('/assign/remove', [RoleAssignmentController::class, 'removeRole'])->name('assign.remove');
    });
    Route::prefix('permissions')->name('permissions.')->group(function () {
        Route::get('/datatable', [PermissionController::class, 'datatable'])->name('datatable');
        Route::get('/', [PermissionController::class, 'index'])->name('index');
        Route::get('/create', [PermissionController::class, 'create'])->name('create');
        Route::post('/store', [PermissionController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [PermissionController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [PermissionController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [PermissionController::class, 'destroy'])->name('destroy');

        // Route::get('/assign', [PermissionAssignmentController::class, 'index'])->name('assign');
        // Route::post('/assign', [PermissionAssignmentController::class, 'assign'])->name('assign.store');
        // Route::get('/assign/edit/{role}', [PermissionAssignmentController::class, 'editAssignedPermissions'])->name('assign.edit');
        // Route::post('/remove', [PermissionAssignmentController::class, 'removePermission'])->name('assign.remove');
    });
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/datatable', [UserController::class, 'datatable'])->name('datatable');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/store', [UserController::class, 'store'])->name('store');
        Route::get('/edit/{id}', [UserController::class, 'edit'])->name('edit');
        Route::put('/update/{id}', [UserController::class, 'update'])->name('update');
        Route::delete('/delete/{id}', [UserController::class, 'destroy'])->name('destroy');

        Route::post('/assign-role', [UserController::class, 'assignRole'])->name('assign-role');
        Route::get('/assign-role/edit/{user}', [UserController::class, 'editAssignedRoles'])->name('assign-role.edit');
    });
    Route::prefix('menus')->name('menus.')->group(function () {
        // Route::resource('/', MenusController::class);
        Route::get('/datatable', [MenusController::class, 'datatable'])->name('datatable');
        Route::get('/parent-menu', [MenusController::class, 'parentMenu'])->name('parent-menu');
    });
    Route::prefix('cart')->name('cart.')->group(function () {
        Route::get('/', [CartController::class, 'index'])->name('index');
        Route::post('/add', [CartController::class, 'addToCart'])->name('add');
        Route::get('/show', [CartController::class, 'showCart'])->name('show');
        Route::post('/update', [CartController::class, 'updateQuantity'])->name('update');
        Route::post('/delete', [CartController::class, 'deleteProduct'])->name('delete');
        Route::post('/checkout', [CartController::class, 'checkout'])->name('checkout');

        Route::get('/load', [CartController::class, 'loadCart'])->name('load');
    });
    Route::prefix('order')->name('order.')->group(function () {
        Route::get('/', [OrderController::class, 'index'])->name('index');
        Route::get('/datatable', [OrderController::class, 'datatable'])->name('datatable');
        // Route::get('/detail/{id}', [OrderController::class, 'show'])->name('detail');
        Route::post('/detail', [OrderController::class, 'show'])->name('detail');
        Route::post('/updateExpedition', [OrderController::class, 'updateExpedition'])->name('updateExpedition');
        Route::post('/editExpedition', [OrderController::class, 'editExpedition'])->name('editExpedition');
    });
    Route::prefix('request-order')->name('request-order.')->group(function () {
        Route::get('/', [RequestOrderController::class, 'index'])->name('index');
        Route::get('/datatable', [RequestOrderController::class, 'datatable'])->name('datatable');
        Route::post('/updateExpedition', [RequestOrderController::class, 'updateExpedition'])->name('updateExpedition');
        Route::post('/editExpedition', [RequestOrderController::class, 'editExpedition'])->name('editExpedition');
        Route::post('/renderListItem', [RequestOrderController::class, 'renderListItem'])->name('renderListItem');
        Route::post('/updateShipping', [RequestOrderController::class, 'updateShipping'])->name('updateShipping');
    });
    Route::prefix('sales')->name('sales.')->group(function () {
        Route::get('/', [SalesController::class, 'index'])->name('index');
        Route::get('/datatable', [SalesController::class, 'datatable'])->name('datatable');
        Route::post('/import', [SalesController::class, 'import'])->name('import');
    });
    Route::prefix('target')->name('target.')->group(function () {
        Route::get('/', [TargetController::class, 'index'])->name('index');
        Route::get('/datatable', [TargetController::class, 'datatable'])->name('datatable');
        Route::post('/import', [TargetController::class, 'import'])->name('import');
    });
    Route::prefix('rod')->name('rod.')->group(function () {
        Route::get('/', [RodController::class, 'index'])->name('index');
        Route::get('/datatable', [RodController::class, 'datatable'])->name('datatable');
        Route::post('/import', [RodController::class, 'import'])->name('import');
        Route::get('/sumDashboardRod', [RodController::class, 'sumDashboardRod'])->name('sumDashboardRod');
    });
    Route::resource('menus', MenusController::class);
    // Route::get('/menus/datatable', [MenusController::class, 'datatable'])->name('menus.datatable');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
