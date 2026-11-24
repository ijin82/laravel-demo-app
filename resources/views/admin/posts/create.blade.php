@extends('layouts.admin')

@section('title', 'Создание поста')

@section('content')
    <div class="mb-6">
        <a href="{{ route('admin.posts.index') }}" class="text-xs font-medium uppercase tracking-[0.16em] text-slate-400 transition hover:text-slate-700">
            ← К списку постов
        </a>
        <h1 class="mt-3 text-2xl font-semibold tracking-tight text-slate-900">Новый пост</h1>
    </div>

    @include('admin.posts._form')
@endsection
