@php
    $selectedTags = array_map('intval', old('tags', $post->exists ? $post->tags->pluck('id')->all() : []));
    $formAction = $post->exists ? route('admin.posts.update', $post) : route('admin.posts.store');
@endphp

@if ($errors->any())
    <div class="mb-5 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
        <p class="font-medium">Проверьте поля формы:</p>
        <ul class="mt-1 list-inside list-disc">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ $formAction }}" class="space-y-5">
    @csrf
    @if ($post->exists)
        @method('PUT')
    @endif

    <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
        <div class="space-y-5">
            <div>
                <label for="title" class="block text-sm font-medium text-slate-700">Заголовок</label>
                <input id="title" name="title" type="text" required maxlength="255"
                       value="{{ old('title', $post->title) }}"
                       class="mt-1.5 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-900 shadow-sm outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
                @error('title')
                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="body" class="block text-sm font-medium text-slate-700">Текст</label>
                <textarea id="body" name="body" required rows="14"
                          class="mt-1.5 block w-full rounded-lg border border-slate-300 px-3 py-2 text-sm leading-6 text-slate-900 shadow-sm outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200">{{ old('body', $post->body) }}</textarea>
                <p class="mt-1.5 text-xs text-slate-400">Пустая строка между абзацами создаёт новый абзац.</p>
                @error('body')
                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <span class="block text-sm font-medium text-slate-700">Теги</span>
                @if ($tags->isEmpty())
                    <p class="mt-1.5 text-sm text-slate-400">
                        Тегов нет. <a href="{{ route('admin.tags.index') }}" class="underline">Создайте первый</a>.
                    </p>
                @else
                    <div class="mt-2 grid gap-2 sm:grid-cols-2 lg:grid-cols-3">
                        @foreach ($tags as $tag)
                            <label class="flex items-center gap-2 rounded-lg border border-slate-200 px-3 py-2 text-sm text-slate-700 transition hover:border-slate-400">
                                <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                       @checked(in_array($tag->id, $selectedTags, true))
                                       class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-400">
                                {{ $tag->name }}
                            </label>
                        @endforeach
                    </div>
                @endif
                @error('tags.*')
                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <label class="flex items-start gap-3 rounded-lg border border-slate-200 bg-slate-50 px-3 py-3">
                <input type="checkbox" name="is_visible" value="1"
                       @checked(old('is_visible', $post->is_visible))
                       class="mt-0.5 h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-400">
                <span>
                    <span class="block text-sm font-medium text-slate-700">Опубликован</span>
                    <span class="block text-xs text-slate-400">Если снять галочку, пост скроется из блога.</span>
                </span>
            </label>
        </div>
    </div>

    <div class="flex flex-wrap items-center gap-3">
        <button type="submit"
                class="rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-medium text-white transition hover:bg-slate-700">
            {{ $post->exists ? 'Сохранить изменения' : 'Создать пост' }}
        </button>
        <a href="{{ route('admin.posts.index') }}" class="text-sm font-medium text-slate-500 transition hover:text-slate-900">
            Отмена
        </a>
    </div>
</form>
