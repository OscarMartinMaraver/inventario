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

    // Realación muchos a muchos polimorfica

    /*Primero hya que indicar el modelo con el cual se va a relacionar (Product), despues indicar el nombre de la llave polimórfica (productable), y como quiero que me incluya otra información de la 
    tabla intermedica (quantity, price, subtotoal), se indica con withPivot*/
    public function porducts()
    {
        return $this->morphToMany(Product::class, 'productable')
        ->withPivot('quantity', 'price', 'subtotal')
        ->withTimestamps(); //para que incluya la marca de tiempo
    }
}
