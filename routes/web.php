<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\WarehouseController;
use App\Http\Controllers\OrderController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('dashboard');
});

Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/chart-data', [DashboardController::class, 'getChartData'])->name('dashboard.chart-data');

    // Products
    Route::resource('products', ProductController::class);
    Route::post('/products/{product}/adjust-stock', [ProductController::class, 'adjustStock'])->name('products.adjust-stock');
    Route::post('/products/{product}/transfer-stock', [ProductController::class, 'transferStock'])->name('products.transfer-stock');

    // Categories
    Route::resource('categories', CategoryController::class);

    // Suppliers
    Route::resource('suppliers', SupplierController::class);

    // Warehouses
    Route::resource('warehouses', WarehouseController::class);

    // Orders
    Route::resource('orders', OrderController::class);
    Route::post('/orders/{order}/update-status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::post('/orders/{order}/process', [OrderController::class, 'process'])->name('orders.process');

    // Stock Movements
    Route::resource('stock-movements', App\Http\Controllers\StockMovementController::class);

    // Purchase Orders
    Route::resource('purchase-orders', App\Http\Controllers\PurchaseOrderController::class);
    Route::post('/purchase-orders/{purchaseOrder}/update-status', [App\Http\Controllers\PurchaseOrderController::class, 'updateStatus'])->name('purchase-orders.update-status');
    Route::post('/purchase-orders/{purchaseOrder}/receive', [App\Http\Controllers\PurchaseOrderController::class, 'receive'])->name('purchase-orders.receive');

    // Returns
    Route::resource('returns', App\Http\Controllers\ProductReturnController::class);
    Route::post('/returns/{return}/update-status', [App\Http\Controllers\ProductReturnController::class, 'updateStatus'])->name('returns.update-status');

    // Reports
    Route::get('/reports/inventory', [App\Http\Controllers\ReportController::class, 'inventory'])->name('reports.inventory');
    Route::get('/reports/sales', [App\Http\Controllers\ReportController::class, 'sales'])->name('reports.sales');
    Route::get('/reports/purchases', [App\Http\Controllers\ReportController::class, 'purchases'])->name('reports.purchases');
    Route::get('/reports/low-stock', [App\Http\Controllers\ReportController::class, 'lowStock'])->name('reports.low-stock');
});

require __DIR__.'/auth.php';
