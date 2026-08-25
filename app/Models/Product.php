<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory; //Es necesario importar el trait para usar el factory en el DatabaseSeeder

    protected $fillable=[
        'name',
        'description',
        'sku',
        'barcode',
        'price',
        'category_id',
        'stock',
    ];

    //Accesores (nos permiten añadir - acceder a los atributos de la clase)
    protected function image():Attribute
    {
        //la función anónima definida mas adelante accede a la relación que tiene el producto con las imagenes, comprueba si hay almenos una imagen con el metodo count()
        // y si es así, genera la URL de la primera imagen con Storage::url. Si no hay imagen, devuelve la URL de la imagen por defecto
        return Attribute::make(
            get: fn() => $this->images()->count()? Storage::url($this->images()->first()->path) : 'https://upload.wikimedia.org/wikipedia/commons/d/d1/Image_not_available.png?utm_source=commons.wikimedia.org&utm_campaign=index&utm_content=thumbnail_unscaled&_=20210219185637'
        );
       
    }




    // Relación uno a muchos inversa
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relacion uno a muchos
    public function inventories()
    {
        return $this->hasMany(Inventory::class);
    }

    // Relación muchos a muchos polimorfica

    public function purchaseOrders()
    {
        return $this->morphedByMany(PurchaseOrder::class, 'productable');
    }

    public function quotes()
    {
        return $this->morphedByMany(Quote::class, 'productable');
    }
    
    // Relacion polimorfica

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}   
