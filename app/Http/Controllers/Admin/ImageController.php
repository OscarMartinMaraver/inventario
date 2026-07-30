<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function destroy(Image $image)
    {
       Storage::delete($image->path);//eliminamos la imagen de la carpeta public/images
       $image->delete();//elimino la imagen de la base de datos
    }
}
