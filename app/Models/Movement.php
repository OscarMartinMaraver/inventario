<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Movement extends Model
{
   protected $fillable=[
       'type',
       'serie',
       'correlative',
       'date',
       'warehouse_id',
       'total',
       'observation',
       'reason_id',
   ];

   protected $casts=[
       'date' => 'datetime',
   ];

    // Realación muchos a muchos polimorfica

    /*Primero hya que indicar el modelo con el cual se va a relacionar (Product), despues indicar el nombre de la llave polimórfica (productable), y como quiero que me incluya otra información de la 
    tabla intermedica (quantity, price, subtotoal), se indica con withPivot*/
    public function products()
    {
        return $this->morphToMany(Product::class, 'productable')
        ->withPivot('quantity', 'price', 'subtotal')
        ->withTimestamps(); //para que incluya la marca de tiempo
    }

    //Relaacion uno a muchos polimorfica

    public function inventories()
    {
        return $this->morphMany(Inventory::class, 'inventoryable');
    }

    //Relación uno a muchos inversa

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function reason()
    {
        return $this->belongsTo(Reason::class);
    }
 
}
