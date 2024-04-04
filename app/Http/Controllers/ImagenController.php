<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class ImagenController extends Controller
{
    //
    public function store(Request $request)
    {
        $imagen = $request->file('file');
        // Asignamos un nombre a caad imagen que se suba
        $nombreImagen = Str::uuid() . "." . $imagen->extension();
        $imagenServidor = Image::make($imagen);
        
        // Nos ayuda a cortar la imagen
        $imagenServidor->fit(1000, 1000);

        // Creamos la ruta con el nombre de cada imagen
        $imagenPath = public_path('uploads') . "/" . $nombreImagen;
        
        // Almacenamos la imagen en la ruta asignada una vez procesada
        $imagenServidor->save($imagenPath);

        return response()->json(['imagen' => $nombreImagen]);
    }
}
