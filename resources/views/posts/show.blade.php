@extends('layouts.app')
@section('titulo') {{ $post->titulo }} @endsection

@section('contenido')
<div class="container mx-auto md:flex">
    <div class="md:w-1/2">
        <!-- INICIO - MOSTRAR IMAGEN DE PUBLICACIÓN CON SU TÍTULO -->
        <img src="{{ asset('uploads' . '/' . $post->imagen) }}" alt="Imagen del post {{ $post->titulo }}">
        <!-- FIN - MOSTRAR IMAGEN DE PUBLICACIÓN CON SU TÍTULO -->
        <!-- INICIO - LIKES -->
        <div class="p-3 flex items-center gap-4">
            @auth
            <livewire:like-post :post="$post" />
            <!--  -->
            <!-- @if ($post->checkLike(auth()->user()))
            <form action="{{ route('posts.likes.destroy', $post) }}" method="POST">
                @method('DELETE')
                @csrf
                <div class="my-4">
                    <button type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="red" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg>
                    </button>
                </div>
            </form>
            @else
            <form action="{{ route('posts.likes.store', $post) }}" method="POST">
                @csrf
                <div class="my-4">
                    <button type="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg>
                    </button>
                </div>
            </form>
            @endif -->
            @endauth
        </div>
        <!-- INICIO - MOSTRAR USERNAME DEL USUARIO -->
        <p class="font-bold"><a href="{{ route('posts.index', $user) }}">{{ $post->user->username }}</a></p>
        <!-- FIN - MOSTRAR USERNAME DEL USUARIO -->

        <!-- Laravel tiene integrada la api sencilla para DateTime llamada Carbon: diffForHumans() -->
        <p class="text-sm text-gray-500">{{ $post->created_at->diffForHumans() }}</p>

        <!-- INICIO - MOSTRAR DESCRIPCION DEL POST -->
        <p class="mt-5">{{ $post->descripcion }}</p>
        <!-- FIN - MOSTRAR DESCRIPCION DEL POST -->

        <!-- INICIO - HELPER PARA AUTENTICAR AL USUARIO -->
        @auth
        @if ($post->user_id === auth()->user()->id)
        <!-- INICIO - BOTÓN ELIMINAR PUBLICACIÓN -->
        <form method="post" action="{{ route('posts.destroy', $post) }}">
            @method('DELETE')
            @csrf
            <input type="submit" value="Eliminar publicación" class="bg-red-500 hover:bg-red-600 p-2 rounded text-white font-bold mt-4 cursor-pointer">
        </form>
        <!-- FIN - BOTÓN ELIMINAR PUBLICACIÓN -->
        @endif
        @endauth
        <!-- FIN - HELPER PARA AUTENTICAR AL USUARIO -->

    </div>

    <div class="md:w-1/2 p-5">
        <div class="shadow bg-white p-5 mb-5">
            <!-- INICIO - AUTH -->
            @auth

            <!-- INICIO - TITULO AGREGAR COMENTARIO -->
            <p class="text-xl font-bold text-center mb-4">Agrega un nuevo comentario</p>
            <!-- FIN - TITULO AGREGAR COMENTARIO -->

            <!-- INICIO - ALERTA DE MENSAJE AGREGADO -->
            @if (session('mensaje'))
            <div class="bg-green-500 p-2 rounded-lg mb-6 text-white text-center uppercase font-bold">{{ session('mensaje') }}</div>
            @endif
            <!-- FIN - ALERTA DE MENSAJE AGREGADO -->

            <form action="{{ route('comentarios.store', ['user' => $user, 'post' => $post]) }}" method="post">
                @csrf
                <!-- INICIO - AGREGAR COMENTARIO -->
                <div class="mb-5">
                    <label for="comentario" class="mb-2 block uppercase text-gray-500 font-bold">Añade un comentario</label>
                    <textarea id="comentario" name="comentario" placeholder="Agrega un comentario" class="border p-3 w-full rounded-lg @error('comentario') border-red-500 @enderror">
                </textarea>
                    <!-- INICIO - ALERTA DE COMENTARIO REQUERIDO -->
                    @error('comentario')
                    <p class="bg-red-500 text-white my-2 rounded-lg text-sm p-2 text-center">{{$message}}</p>
                    @enderror
                    <!-- FIN - ALERTA DE COMENTARIO REQUERIDO -->

                </div>
                <!-- FIN - AGREGAR COMENTARIO -->
                <input type="submit" value="Comentar" class="bg-sky-600 hover:bg-sky-700 transition-colors cursor-pointer uppercase font-bold w-full p-3 text-white rounded-lg">
            </form>
            @endauth
            <!-- FIN - AUTH -->

            <div class="bg-white shadow mb-5 max-h-96 overflow-scroll mt-10">
                @if ($post->comentarios->count())
                @foreach ($post->comentarios as $comentario)
                <div class="p-5 border-gray-300 border-b">
                    <!-- Mostramos el username con la variable del foreach, usando la function user para llamar el username del user
                            que realizó el comentario-->
                    <!-- En el href nos direcciona al muro del usuario que realizó el comentario -->
                    <a href="{{ route('posts.index', $comentario->user) }}" class="font-bold">{{ $comentario->user->username }}</a>
                    <!-- Muestra el comentario del usuario -->
                    <p>{{ $comentario->comentario }}</p>
                    <!-- Muestra hace cuánto se realizó el comentario con ayuda de la api de Carbon -->
                    <p class="text-sm text-gray-500">{{ $comentario->created_at->diffForHumans() }}</p>
                </div>
                @endforeach
                @else
                <p class="p-10 text-center text-gray-500">No hay comentarios aún</p>
                @endif
            </div>

        </div>
    </div>
</div>
@endsection