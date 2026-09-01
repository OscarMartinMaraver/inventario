<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ImageController;
use App\Http\Controllers\Admin\MovementController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\PurchaseController;
use App\Http\Controllers\Admin\PurchaseOrderController;
use App\Http\Controllers\Admin\QuoteController;
use App\Http\Controllers\Admin\SaleController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\TransferController;
use App\Http\Controllers\Admin\WarehouseController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {//No es necesario indicar el prefijo  /admin (ya que se estableción en bootstrap.app.php)
    return view('admin.dashboard');
})->name('dashboard'); //No es necesario nombrarla con admin.dashboard (ya que se estableción en bootstrap.app.php)


//INVENTARIO

Route::resource('categories', CategoryController::class)->except(['show']);

Route::resource('products', ProductController::class)->except(['show']);
Route::post('products/{product}/dropzone', [ProductController::class, 'dropzone'])->name('products.dropzone');// Ruta para almacenar imagenes con Dropzone
Route::get('products/{product}/kardex',[ProductController::class, 'kardex'] )->name('products.kardex');

Route::resource('warehouses', WarehouseController::class)->except(['show']);


//COMPRAS

Route::resource('suppliers', SupplierController::class)->except(['show']);
Route::resource('purchase-orders', PurchaseOrderController::class)->only(['index', 'create']);
Route::resource('purchases', PurchaseController::class)->only(['index', 'create']);
Route::get('purchases/{purchase}/pdf', [PurchaseController::class, 'pdf'])->name('purchases.pdf');
Route::get('purchase-orders/{purchaseOrder}/pdf', [PurchaseOrderController::class, 'pdf'])->name('purchase-orders.pdf');

//VENTAS

Route::resource('customers', CustomerController::class)->except(['show']);
Route::resource('quotes', QuoteController::class)->only(['index', 'create']);
Route::resource('sales', SaleController::class)->only(['index', 'create']);
Route::get('quotes/{quote}/pdf', [QuoteController::class, 'pdf'])->name('quotes.pdf');
Route::get('sales/{sale}/pdf', [SaleController::class, 'pdf'])->name('sales.pdf');


//MOVIMIENTOS

Route::resource('movements', MovementController::class)->only(['index', 'create']);
Route::get('movements/{movement}/pdf', [MovementController::class, 'pdf'])->name('movements.pdf');
Route::resource('transfers', TransferController::class)->only(['index', 'create']);
Route::get('transfers/{transfer}/pdf', [TransferController::class, 'pdf'])->name('transfers.pdf');


//Ruta para eliminar imagenes
Route::delete('images/{image}', [ImageController::class, 'destroy'])->name('images.destroy');