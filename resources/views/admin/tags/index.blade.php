@extends('layouts.admin')

@section('title', 'Теги')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-semibold tracking-tight text-slate-900">Теги</h1>
        <p class="mt-1 text-sm text-slate-500">Теги привязываются к постам и показываются в блоге.</p>
    </div>

    @if ($errors->any())
        <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="grid gap-6 lg:grid-cols-[320px_minmax(0,1fr)]">
        <section class="h-fit rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
            <h2 class="text-sm font-semibold uppercase tracking-[0.12em] text-slate-500">Новый тег</h2>

            <form method="POST" action="{{ route('admin.tags.store') }}" class="mt-4 space-y-3">
                @csrf
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-700">Название</label>
                    <input id="name" name="name" type="text" required maxlength="100"
                           value="{{ old('name') }}"
                           placeholder="Например, Архитектура"
                           class="mt-1.5 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 shadow-sm outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
                </div>
                <button type="submit"
                        class="w-full rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white transition hover:bg-slate-700">
                    Добавить тег
                </button>
            </form>
        </section>

        <section class="overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-100 px-5 py-3 text-xs uppercase tracking-[0.12em] text-slate-500">
                Всего тегов: {{ $tags->count() }}
            </div>

            @if ($tags->isEmpty())
                <div class="px-6 py-14 text-center text-sm text-slate-500">Тегов пока нет.</div>
            @else
                <div class="divide-y divide-slate-100">
                    @foreach ($tags as $tag)
                        <div class="flex flex-wrap items-center gap-3 px-5 py-3">
                            <form method="POST" action="{{ route('admin.tags.update', $tag) }}" class="flex min-w-0 flex-1 items-center gap-2">
                                @csrf
                                @method('PUT')
                                <input name="name" type="text" required maxlength="100" value="{{ $tag->name }}"
                                       class="min-w-0 flex-1 rounded-lg border border-transparent px-2 py-1.5 text-sm text-slate-900 transition hover:border-slate-200 focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-100">

                                <span class="whitespace-nowrap text-xs text-slate-400">Постов: {{ $tag->posts_count }}</span>

                                <button type="submit" class="whitespace-nowrap text-xs font-medium text-slate-500 transition hover:text-slate-900">
                                    Сохранить
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.tags.destroy', $tag) }}"
                                  onsubmit="return confirm('Удалить тег? Он исчезнет у всех постов.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-xs font-medium text-red-600 transition hover:text-red-800">Удалить</button>
                            </form>
                        </div>
                    @endforeach
                </div>
            @endif
        </section>
    </div>
@endsection
