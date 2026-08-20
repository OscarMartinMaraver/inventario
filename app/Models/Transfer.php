<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    protected $fillable = [
        'serie',
        'correlative',
        'date',
        'total',
        'observation',
        'origin_warehouse_id',
        'destination_warehouse_id'
    ];

    protected $casts = [
        'date' => 'datetime',
    ];

    // Relacion uno a muchos inversa

    /*Hay que distinguier que llave foranea se va a relacionar en estos dos métodos*/
    public function originWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'origin_warehouse_id');
    }

    public function destinationWarehouse()
    {
        return $this->belongsTo(Warehouse::class, 'destination_warehouse_id');
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

    //Relacion uno a muchos polimófricas con inventario
    public function inventories()
    {
        return $this->morphMany(Inventory::class, 'inventoryable');
    }
}
