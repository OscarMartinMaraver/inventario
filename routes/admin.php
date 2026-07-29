<?php

use App\Http\Controllers\Admin\CategoryController;
use Illuminate\Support\Facades\Route;


Route::get('/', function () {//No es necesario indicar el prefijo  /admin (ya que se estableción en bootstrap.app.php)
    return view('admin.dashboard');
})->name('dashboard'); //No es necesario nombrarla con admin.dashboard (ya que se estableción en bootstrap.app.php)


Route::resource('categories', CategoryController::class)->except(['show']);