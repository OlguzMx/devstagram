@extends('layouts.app')
@section('titulo') Perfil: {{ $user->username }} @endsection

@section('contenido')

<div class="flex justify-center">
    <div class="w-full md:w-8/12 lg:w-6/12 flex flex-col items-center md:flex-row">
        <div class="md:w-8/12 lg:w:-6/12 px-5">
            <img src="{{ $user->imagen ? asset('perfiles') . '/' . $user->imagen : asset('img/usuario.svg') }}" alt="imagen usuario">
        </div>
        <div class="md:w-8/12 lg:w:-6/12 px-5 flex flex-col items-center md:justify-center md:items-start py-10 md:py-10">

            <div class="flex items-center gap-2">
                <p class="text-gray-700 text-2xl">{{ $user->username }}</p>

                @auth
                @if ($user->id === auth()->user()->id)
                <a class="text-gray-500 hover:text-gray-600 cursor-pointer" href="{{ route('perfil.index', $user) }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L6.832 19.82a4.5 4.5 0 01-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 011.13-1.897L16.863 4.487zm0 0L19.5 7.125" />
                    </svg>

                </a>
                @endif
                @endauth
            </div>

            <p class="text-gray-800 text-sm mb-3 font-bold mt-5">{{ $user->followers->count() }} <span>@choice('Seguidor|Seguidores', $user->followers->count())</span></p>

            <p class="text-gray-800 text-sm mb-3 font-bold mt-5">{{ $user->following->count() }} <span>Siguiendo</span></p>

            <p class="text-gray-800 text-sm mb-3 font-bold mt-5">{{ $user->posts->count()}} <span>Posts</span></p>

            @auth
            <!-- Si el usuario actual es diferente al usuario que está autenticado, podrá ver el "Seguir" y "Dejar de Seguir" -->
            @if($user->id !== auth()->user()->id)
            @if(!$user->siguiendo(auth()->user()))
            <form action="{{ route('users.follow', $user) }}" method="post">
                @csrf
                <input type="submit" value="Seguir" class="bg-blue-600 text-white uppercase rounded-lg px-3 py-1 text-xs font-bold cursor-pointer" />
            </form>
            @else
            <form action="{{ route('users.unfollow', $user) }}" method="post">
                @csrf
                @method('DELETE')
                <input type="submit" value="Dejar de Seguir" class="bg-red-600 text-white uppercase rounded-lg px-3 py-1 text-xs font-bold cursor-pointer" />
            </form>
            @endif
            @endif
            @endauth
        </div>
    </div>
</div>

<section class="container mx-auto mt-10">
    <h2 class="text-4xl text-center font-black my-10">Publicaciones</h2>
    @if($posts->count())
    <div class="grid md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
        @foreach($posts as $post)
        <div>
            <!-- En este a nos muestra la imagen, con el src de source, buscará la imagen que no se guarda en la base de datos
            se concantena un slasg direccionando al nombre de la imagen en la base de datos -->
            <a href="{{ route('posts.show', ['post' => $post, 'user' => $user]) }}"> <img src="{{ asset('uploads') . '/' . $post->imagen }}" alt="Imagen del Post {{ $post->titulo }}"></a>
        </div>
        @endforeach
        <div class="my-10">
            {{ $posts->links('')}}
        </div>
    </div>
    @else
    <p class="text-gray-600 uppercase text-sm text-center font-bold">No hay posts</p>
    @endif
</section>

@endsection