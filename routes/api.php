<?php

use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


//Nos intereas si el usuario ha mandado en el formulario un campo llamado search, ya que el componente de wireUI usado para la busqueda tiene el campo llamado search
// Lo que hace la función es consultar a la base de datos, y buscar si existe algún registro en el campo name o ruc que contenga lo que se ha introducido en el campo search

Route::post('/suppliers', function (request $request) {
    return Supplier::select('id', 'name')
        ->when($request->search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('document_number', 'like', "%{$search}%");
        })
        ->when(
            $request->exists('selected'),
            fn($query) => $query->whereIn('id', $request->input('selected', [])),
            fn($query) => $query->limit(10)
        )
        ->get();
})->name('api.suppliers.index');

Route::post('/products', function (request $request) {
    return Product::select('id', 'name')
        ->when($request->search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%");
        })
        ->when(
            $request->exists('selected'),
            fn($query) => $query->whereIn('id', $request->input('selected', [])),
            fn($query) => $query->limit(10)
        )
        ->get();
})->name('api.products.index');
