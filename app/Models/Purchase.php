<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'voucher_type',
        'serie',
        'correlative',
        'date',
        'purchase_order_id',
        'supplier_id',
        'warehouse_id',
        'total',
        'observation',
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

     // Realación uno a muchos inversa
    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    // Realación muchos a muchos polimorfica

    /*Primero hya que indicar el modelo con el cual se va a relacionar (Product), despues indicar el nombre de la llave polimórfica (productable), y como quiero que me incluya otra información de la 
    tabla intermedica (quantity, price, subtotoal), se indica con withPivot*/
    public function products()
    {
        return $this->morphToMany(Product::class, 'productable')
        ->withPivot('quantity', 'price', 'subtotal')
        ->withTimestamps(); //para que incluya la marca de tiempo
    }

    //Relacion uno a muchos polimórgica con inventario
    public function inventories()
    {
        return $this->morphMany(Inventory::class, 'inventoryable'); //el segundo parámetro es el nombre de la llave polimórfica
    }
}
