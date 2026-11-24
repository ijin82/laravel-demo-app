@extends('layouts.blog')

@section('title', $post->title)
@section('description', Str::limit(Str::squish($post->body), 160))

@section('content')
    <article>
        <a href="{{ route('home') }}" class="text-xs font-medium uppercase tracking-[0.16em] text-slate-400 transition hover:text-slate-700">
            ← Все записи
        </a>

        <header class="mt-5 border-b border-slate-200 pb-7">
            <div class="flex items-center gap-3 text-[11px] uppercase tracking-[0.16em] text-slate-400">
                <time datetime="{{ $post->created_at->toIso8601String() }}">{{ $post->created_at->format('d.m.Y') }}</time>
                @if ($post->updated_at->gt($post->created_at))
                    <span aria-hidden="true">·</span>
                    <span>обновлено {{ $post->updated_at->format('d.m.Y') }}</span>
                @endif
            </div>

            <h1 class="mt-3 text-3xl font-semibold leading-tight tracking-tight text-slate-900 sm:text-4xl">
                {{ $post->title }}
            </h1>

            @if ($post->tags->isNotEmpty())
                <div class="mt-4 flex flex-wrap gap-2">
                    @foreach ($post->tags as $tag)
                        <a href="{{ route('tags.show', $tag) }}"
                           class="rounded-full border border-slate-200 bg-white px-3 py-1 text-xs text-slate-600 transition hover:border-slate-400">
                            {{ $tag->name }}
                        </a>
                    @endforeach
                </div>
            @endif
        </header>

        <div class="mt-8 space-y-5 text-[17px] leading-8 text-slate-700">
            @foreach (preg_split('/\R\s*\R/u', trim($post->body)) as $paragraph)
                <p>{{ $paragraph }}</p>
            @endforeach
        </div>
    </article>

    <div class="mt-12 border-t border-slate-200 pt-6">
        <a href="{{ route('home') }}" class="text-sm font-medium text-slate-500 transition hover:text-slate-900">← Вернуться к списку</a>
    </div>
@endsection
