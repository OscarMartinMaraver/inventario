<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    protected $fillable=[
        'path',
        'size',
        'imageable_id',
        'imageable_type',
    ];

    // Relacion uno a muchos polimorfica
    public function imageable()
    {
        return $this->morphTo();
    }
}
