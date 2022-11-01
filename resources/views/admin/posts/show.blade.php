@extends('admin.layouts.app')

@section('title', 'Detalhes do post')

@section('content')

<h1>Detalhes do post {{ $post->title }}</h1>

<ul>
    <li><img src="{{ url("storage/{$post->image}") }}" alt="{{ $post->title }}" style="max-width: 100px;"></li>
    <li>Título: {{ $post->title }}</li>
    <li>Conteúdo: {{ $post->content }}</li>
</ul>

<form action="{{ route('posts.destroy', $post->id) }}" method="post">
    @csrf
    <input type="hidden" name="_method" value="DELETE">
    <button type="submit">Remover</button>
</form>

@endsection