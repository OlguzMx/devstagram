<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;

class PerfilController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('perfil.index');
    }

    public function store(Request $request)
    {
        // Modificar el Request
        $request->request->add(['username' => Str::slug($request->username)]);

        $this->validate($request, [
            'username' => ['required', 'unique:users,username,' . auth()->user()->id, 'min:3', 'max:20', 'not_in:twitter, editar-perfil'],
            'email' => ['required', 'unique:users,imagen,' . auth()->user()->id],
        ]);

        if ($request->imagen) {
            $imagen = $request->file('imagen');
            // Asignamos un nombre a caad imagen que se suba
            $nombreImagen = Str::uuid() . "." . $imagen->extension();
            $imagenServidor = Image::make($imagen);

            // Nos ayuda a cortar la imagen
            $imagenServidor->fit(1000, 1000);

            // Creamos la ruta con el nombre de cada imagen
            $imagenPath = public_path('perfiles') . "/" . $nombreImagen;

            // Almacenamos la imagen en la ruta asignada una vez procesada
            $imagenServidor->save($imagenPath);
        }

        // Guardar cambios
        $usuario = User::find(auth()->user()->id);
        $usuario->username = $request->username;
        $usuario->imagen = $nombreImagen ?? auth()->user()->imagen ?? null;
        $usuario->email = $request->email ?? auth()->user()->email;

        // if($request->password || $request->new_password){
        //     $this->validate($request,[
        //         'password' => 'required|min:6',
        //         'new_password' => 'required|min:6|confirmed',
        //     ]);
            
        //     if(Hash::check($request->password, $usuario->password)){
        //         $usuario->password = Hash::make($request->new_password) ;
        //         $usuario->save();
        //     }
        // } else {
        //     return back()->with('mensaje', 'Contraseña actual incorrecta');
        // }

        $usuario->save();


        return redirect()->route('posts.index', $usuario->username);
    }
}
