<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\ImageController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\WarehouseController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {//No es necesario indicar el prefijo  /admin (ya que se estableción en bootstrap.app.php)
    return view('admin.dashboard');
})->name('dashboard'); //No es necesario nombrarla con admin.dashboard (ya que se estableción en bootstrap.app.php)


Route::resource('categories', CategoryController::class)->except(['show']);

Route::resource('products', ProductController::class)->except(['show']);

Route::resource('customers', CustomerController::class)->except(['show']);

Route::resource('suppliers', SupplierController::class)->except(['show']);

Route::resource('warehouses', WarehouseController::class)->except(['show']);

// Ruta para almacenar imagenes con Dropzone
Route::post('products/{product}/dropzone', [ProductController::class, 'dropzone'])->name('products.dropzone');

//Ruta para eliminar imagenes
Route::delete('images/{image}', [ImageController::class, 'destroy'])->name('images.destroy');