<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class PostController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth')->except(['show', 'index']);
    }

    public function index(User $user)
    {
        // Imprime todos los valores ingresados del user
        // dd($user);

        // Imprime el username del user
        // dd($user->username);
        // dd($user->id);

        // De esta manera el $posts traerá la información del modelo
        // $posts = Post::where('user_id', $user->id);

        // Aquí trae los resultados de la base de datos
        // $posts = Post::where('user_id', $user->id)->get();
        // dd($posts);

        // Nos muestra los posts con un cierto numero de imagenes y darle paginación
        $posts = Post::where('user_id', $user->id)->latest()->paginate(4);

        return view('dashboard', [
            'user' => $user,
            'posts' => $posts
        ]);
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'titulo' => 'required|max:255',
            'descripcion' => 'required',
        ]);

        #Nos permite crear un nuevo post con los atributos requeridos de la base de datos
        #Esta es una manera de crear un post, en una misma instancia
        // Post::create([
        //     'titulo' => $request->titulo,
        //     'descripcion' => $request->descripcion,
        //     'imagen' => $request->imagen,
        //     'user_id' => auth()->user()->id
        // ]);

        #Es otra forma de crear un post, con una instancia, llenando la información a la base de datos
        // $post = new Post;
        // $post->titulo = $request->titulo;
        // $post->descripcion = $request->descripcion;
        // $post->imagen = $request->imagen;
        // $post->user_id = auth()->user()->id;
        // $post->save();

        #Es otra manera de crear un post, es mas al estilo de Laravel, en el request
        #toma al usuario autenticado, direccionado a la relación de posts en el Modelo y crear
        #el post con los datos requeridos. Lo que es un beneficio en este tipo de creación
        #es que las relaciones simplifica el código
        $request->user()->posts()->create([
            'titulo' => $request->titulo,
            'descripcion' => $request->descripcion,
            'imagen' => $request->imagen,
            'user_id' => $request->user_id,
        ]);

        return redirect()->route('posts.index', auth()->user()->username);
    }

    public function show(User $user, Post $post)
    {
        return view('posts.show', [
            'post' => $post,
            'user' => $user,
        ]);
    }

    public function destroy(Post $post)
    {
        // Se eliminará la publicación con respecto a su id
        $this->authorize('delete', $post);
        $post->delete();

        // Eliminar imagen de la publicación
        $imagen_path = public_path('uploads/' . $post->imagen);

        if(File::exists($imagen_path)){
            unlink($imagen_path);
        }

        return redirect()->route('posts.index', auth()->user()->username);
    }
}
