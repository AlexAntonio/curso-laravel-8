@extends('admin.layouts.app')

@section('title', 'Listagem de posts')

@section('content')
    <h1>Posts</h1>

    @if (session('message'))
        <div>{{ session('message') }}</div>
    @endif

    <a href="{{ route('posts.create') }}">Novo</a>
    <br><br>

    <form action="{{ route('posts.search') }}" method="post">
        @csrf
        <input type="text" name="search" id="search" placeholder="Pesquisar">
        <button type="submit">Filtrar</button>
    </form>

    @foreach ($posts as $post)
        <p>
            <img src="{{ url("storage/{$post->image}") }}" alt="{{ $post->title }}" style="max-width: 100px;">
            <a href="{{ route('posts.show', ['id' => $post->id]) }}">{{ $post->title }}</a>
            | <a href="{{ route('posts.edit', ['id' => $post->id]) }}">Edit</a>
        </p>
    @endforeach

    <hr />
    @if (isset($filters))
        {{ $posts->appends($filters)->links() }}
    @else
        {{ $posts->links() }}
    @endif
@endsection