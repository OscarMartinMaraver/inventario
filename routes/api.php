<?php

use App\Models\Customer;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\Quote;
use App\Models\Reason;
use App\Models\Supplier;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');


//Nos intereas si el usuario ha mandado en el formulario un campo llamado search, ya que el componente de wireUI usado para la busqueda tiene el campo llamado search
// Lo que hace la función es consultar a la base de datos, y buscar si existe algún registro en el campo name o ruc que contenga lo que se ha introducido en el campo search


/**
 * Esta ruta API será usada para buscar almacenes en la BD u poder agregarlos al formulario de compra sin tener que precagrarlos
 */
Route::post('/warehouses', function (request $request) {
    return Warehouse::select('id', 'name', 'location as description')
        ->when($request->search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('location', 'like', "%{$search}%");
        })
        ->when($request->exclude, function ($query, $exclude) {
            $query->where('id', '!=',  $exclude);
        })//Pregunto si en la consulta hay un parametro llamado exclude. Si es así, le pido que traiga todos los almacenes menos el seleccionadao
        ->when(
            $request->exists('selected'),
            fn($query) => $query->whereIn('id', $request->input('selected', [])),
            fn($query) => $query->limit(10)
        )
        ->get();
})->name('api.warehouses.index');


/**
 * Esta ruta API será usada para buscar proveedores en la BD u poder agregarlos al formulario de compra sin tener que precagrarlos
 */
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

/**
 * Esta ruta API será usada para buscar clientes en la BD u poder agregarlos al formulario de compra sin tener que precagrarlos
 */
Route::post('/customers', function (request $request) {
    return Customer::select('id', 'name')
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
})->name('api.customers.index');

/**
 * Esta ruta API será usada para buscar productos en la BD u poder agregarlos al formulario de compra sin tener que precagrarlos
 */
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

/**
 * Esta ruta API será usada para buscar productos en la BD u poder agregarlos al formulario de compra sin tener que precagrarlos
 */
Route::post('/purchase-orders', function (request $request) {
    $purchaseOrders = PurchaseOrder::when($request->search, function ($query, $search) {
        
        $parts = explode('-', $search);//La serie tiene la estructura XXXX-123

        //Si el array tiene un solo elemento puede que estemoos buscando por el nombre del proveedor
        if (count($parts) == 1) {

            //Buscar por nompre de proveedor.
            //En este caso no estariamos haciendo consultas a las órdenes de compra, sino a la relacion con los proveedores, solo a los proveederes de las ordens de compra que tenemos.
            $query->whereHas('supplier', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('document_number', 'like', "%{$search}%");;
            });

            return;
        }

        //Si el array tiene dos elementos, podemos estar buscando por el nombre de la orden de compra (XXX-123)
        if (count($parts) == 2) {

            $serie = $parts[0];
            $correlative = ltrim($parts[1], 0); //elimino los ceros al principio del numero

            $query->where('serie', $serie)
                ->where('correlative', 'LIKE', "%{$correlative}%");
            return;
        }
    })
        ->when(
            $request->exists('selected'),
            fn($query) => $query->whereIn('id', $request->input('selected', [])),
            fn($query) => $query->limit(10)
        )
        ->with('supplier')
        ->orderBy('created_at', 'desc')
        ->get();

    //map coloca un callback en cada uno de los elementos del array (coloca un bucle foreach en el array)
    //Me interesa que la búsqueda de la Orden de compra devuelva solo cierta información
    return $purchaseOrders->map(function ($purchaseOrder) {
        return [
            'id' => $purchaseOrder->id,
            'name' => $purchaseOrder->serie . '-' . $purchaseOrder->correlative,
            'description' => $purchaseOrder->supplier->name.'-'.$purchaseOrder->supplier->document_number,
        ];
    });
})->name('api.purchase-orders.index');


/**
 * Esta ruta API será usada para buscar productos en la BD u poder agregarlos al formulario de compra sin tener que precagrarlos
 */
Route::post('/quotes', function (request $request) {
    $quotes = Quote::when($request->search, function ($query, $search) {
        
        $parts = explode('-', $search);//La serie tiene la estructura XXXX-123

        //Si el array tiene un solo elemento puede que estemoos buscando por el nombre del proveedor
        if (count($parts) == 1) {

            //Buscar por nompre de proveedor.
            //En este caso no estariamos haciendo consultas a las órdenes de compra, sino a la relacion con los proveedores, solo a los proveederes de las ordens de compra que tenemos.
            $query->whereHas('customer', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('document_number', 'like', "%{$search}%");;
            });

            return;
        }

        //Si el array tiene dos elementos, podemos estar buscando por el nombre de la orden de compra (XXX-123)
        if (count($parts) == 2) {

            $serie = $parts[0];
            $correlative = ltrim($parts[1], 0); //elimino los ceros al principio del numero

            $query->where('serie', $serie)
                ->where('correlative', 'LIKE', "%{$correlative}%");
            return;
        }
    })
        ->when(
            $request->exists('selected'),
            fn($query) => $query->whereIn('id', $request->input('selected', [])),
            fn($query) => $query->limit(10)
        )
        ->with('customer')
        ->orderBy('created_at', 'desc')
        ->get();

    //map coloca un callback en cada uno de los elementos del array (coloca un bucle foreach en el array)
    //Me interesa que la búsqueda de la Orden de compra devuelva solo cierta información
    return $quotes->map(function ($quote) {
        return [
            'id' => $quote->id,
            'name' => $quote->serie . '-' . $quote->correlative,
            'description' => $quote->customer->name.'-'.$quote->customer->document_number,
        ];
    });
})->name('api.sales.index');

/**
 * Esta ruta API será usada para buscar razones de movimiento en la BD u poder agregarlos al formulario de compra sin tener que precagrarlos
 */
Route::post('/reasons', function (request $request) {
    return Reason::select('id', 'name')
        ->when($request->search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%");
        })
        ->when(
            $request->exists('selected'),
            fn($query) => $query->whereIn('id', $request->input('selected', [])),
            fn($query) => $query->limit(10)
        )
        ->where('type', $request->input('type', '')) //va a filtrar por tipo (1 entrada, 2 salida)
        ->get();
})->name('api.reasons.index');