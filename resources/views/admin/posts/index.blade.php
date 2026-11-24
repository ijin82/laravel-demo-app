@extends('layouts.admin')

@section('title', 'Посты')

@section('content')
    <div class="mb-6 flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Посты</h1>
            <p class="mt-1 text-sm text-slate-500">
                Всего: {{ $posts->total() }}. Скрытые посты видны только в админке.
            </p>
        </div>

        <a href="{{ route('admin.posts.create') }}"
           class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-700">
            + Создать пост
        </a>
    </div>

    <div class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
        @if ($posts->isEmpty())
            <div class="px-6 py-16 text-center text-sm text-slate-500">
                Постов пока нет. <a href="{{ route('admin.posts.create') }}" class="underline">Создайте первый</a>.
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200 text-sm">
                    <thead class="bg-slate-50 text-left text-[11px] uppercase tracking-[0.12em] text-slate-500">
                        <tr>
                            <th scope="col" class="px-5 py-3 font-medium">Заголовок</th>
                            <th scope="col" class="px-5 py-3 font-medium">Видимость</th>
                            <th scope="col" class="px-5 py-3 font-medium">Создан</th>
                            <th scope="col" class="px-5 py-3 text-right font-medium">Действия</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach ($posts as $post)
                            <tr class="align-top transition hover:bg-slate-50/70">
                                <td class="max-w-md px-5 py-4">
                                    <a href="{{ route('posts.show', $post) }}" target="_blank"
                                       class="font-medium text-slate-900 transition hover:text-slate-600">
                                        {{ $post->title }}
                                    </a>
                                    <div class="mt-1.5 flex flex-wrap gap-1.5">
                                        @foreach ($post->tags as $tag)
                                            <span class="rounded bg-slate-100 px-1.5 py-0.5 text-[11px] text-slate-500">{{ $tag->name }}</span>
                                        @endforeach
                                    </div>
                                </td>

                                <td class="px-5 py-4">
                                    <form method="POST" action="{{ route('admin.posts.visibility', $post) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="is_visible" value="{{ $post->is_visible ? 0 : 1 }}">
                                        <button type="submit"
                                                title="Переключить видимость"
                                                class="inline-flex items-center gap-2 rounded-full border px-2.5 py-1 text-xs transition {{ $post->is_visible ? 'border-emerald-200 bg-emerald-50 text-emerald-700 hover:border-emerald-400' : 'border-slate-200 bg-slate-50 text-slate-500 hover:border-slate-400' }}">
                                            <span class="h-1.5 w-1.5 rounded-full {{ $post->is_visible ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                            {{ $post->is_visible ? 'Опубликован' : 'Скрыт' }}
                                        </button>
                                    </form>
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-slate-500">
                                    {{ $post->created_at->format('d.m.Y') }}
                                    <div class="text-[11px] text-slate-400">{{ $post->created_at->format('H:i') }}</div>
                                </td>

                                <td class="whitespace-nowrap px-5 py-4 text-right">
                                    <a href="{{ route('admin.posts.edit', $post) }}"
                                       class="text-sm font-medium text-slate-600 transition hover:text-slate-900">Изменить</a>

                                    <form method="POST" action="{{ route('admin.posts.destroy', $post) }}" class="ml-4 inline"
                                          onsubmit="return confirm('Удалить пост?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-sm font-medium text-red-600 transition hover:text-red-800">Удалить</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="border-t border-slate-100 px-5 py-4">
                {{ $posts->links() }}
            </div>
        @endif
    </div>
@endsection
