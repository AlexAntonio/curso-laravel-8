@extends('admin.layouts.app')

@section('title', 'Editar post')

@section('content')

<h1>Editar post {{ $post->title }}</h1>

<form action="{{ route('posts.update', $post->id) }}" method="post" enctype="multipart/form-data">
    @method('put')
    <img src="{{ url("storage/{$post->image}") }}" alt="{{ $post->title }}" style="max-width: 100px;">
    @include('admin.posts._partials.form')
</form>

@endsection