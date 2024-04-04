<div>
    <h2>Mostrando Posts</h2>
    @if ($posts->count())
    @foreach ($posts as $post)
    <div class="container mx-auto md:flex">
        <div class="md:w-2/3">
            <div class="p-3 flex items-center gap-2">
                <a href="{{ route('posts.show', ['post' => $post, 'user' => $post->user]) }}"> <img src="{{ asset('uploads') . '/' . $post->imagen }}" alt="Imagen del Post {{ $post->titulo }}" width="500px">
                </a>
                <div class="p-5 border-gray-300">
                    <h2 class="flex-auto">{{$post->titulo}}</h2>
                    <a href="{{ route('posts.index', $post->user) }}" class="font-bold">{{ $post->user->username }}</a>
                    <p class="text-gray-400 text-sm">{{ $post->created_at->diffForHumans() }}</p>
                    <p>{{ $post->descripcion }}</p>
                </div>
            </div>
        </div>
    </div>
    @endforeach
    @else
    <p class="text-center">No hay posts</p>
    @endif  
</div>