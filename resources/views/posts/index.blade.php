@extends('layouts.blog')

@section('title', $activeTag ? 'Тег: '.$activeTag->name : config('app.name').' — код, истории и мысли')
@section('description', 'Небольшие заметки о разработке, инструментах и жизни вокруг кода.')

@section('content')
    <section class="mb-10">
        <h1 class="text-3xl font-semibold tracking-tight text-slate-900 sm:text-4xl">
            {{ $activeTag ? $activeTag->name : 'Записи' }}
        </h1>
        <p class="mt-2 max-w-xl text-slate-500">
            @if ($activeTag)
                Материалы с тегом «{{ $activeTag->name }}».
            @else
                Короткие заметки о коде, инструментах и людях, которые их пишут.
            @endif
        </p>

        @if ($tags->isNotEmpty())
            <div class="mt-6 flex flex-wrap gap-2">
                <a href="{{ route('home') }}"
                   class="rounded-full border px-3 py-1 text-xs transition {{ $activeTag ? 'border-slate-200 bg-white text-slate-600 hover:border-slate-400' : 'border-slate-900 bg-slate-900 text-white' }}">
                    Все
                </a>
                @foreach ($tags as $tag)
                    <a href="{{ route('tags.show', $tag) }}"
                       class="rounded-full border px-3 py-1 text-xs transition {{ $activeTag?->is($tag) ? 'border-slate-900 bg-slate-900 text-white' : 'border-slate-200 bg-white text-slate-600 hover:border-slate-400' }}">
                        {{ $tag->name }}
                        <span class="{{ $activeTag?->is($tag) ? 'text-slate-300' : 'text-slate-400' }}">{{ $tag->posts_count }}</span>
                    </a>
                @endforeach
            </div>
        @endif
    </section>

    @if ($posts->isEmpty())
        <div class="rounded-xl border border-dashed border-slate-300 bg-white/60 px-6 py-16 text-center text-slate-500">
            Здесь пока пусто.
        </div>
    @else
        <div class="divide-y divide-slate-200 border-y border-slate-200">
            @foreach ($posts as $post)
                <article class="py-7">
                    <div class="flex items-center gap-3 text-[11px] uppercase tracking-[0.16em] text-slate-400">
                        <time datetime="{{ $post->created_at->toIso8601String() }}">{{ $post->created_at->format('d.m.Y') }}</time>
                    </div>

                    <h2 class="mt-2 text-xl font-semibold tracking-tight text-slate-900 sm:text-2xl">
                        <a href="{{ route('posts.show', $post) }}" class="transition hover:text-slate-600">{{ $post->title }}</a>
                    </h2>

                    <p class="mt-2 leading-relaxed text-slate-600">
                        {{ Str::limit(Str::squish($post->body), 240) }}
                    </p>

                    <div class="mt-3 flex flex-wrap items-center gap-x-4 gap-y-2">
                        @foreach ($post->tags as $tag)
                            <a href="{{ route('tags.show', $tag) }}" class="text-xs text-slate-400 transition hover:text-slate-700">#{{ $tag->name }}</a>
                        @endforeach

                        <a href="{{ route('posts.show', $post) }}" class="ml-auto text-xs font-medium text-slate-500 transition hover:text-slate-900">
                            Читать →
                        </a>
                    </div>
                </article>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $posts->links() }}
        </div>
    @endif
@endsection
