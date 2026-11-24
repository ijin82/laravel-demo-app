<!DOCTYPE html>
<html lang="ru" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Админка') — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-slate-100 text-slate-800 antialiased">
    <header class="bg-slate-900 text-slate-300">
        <div class="mx-auto flex max-w-6xl flex-wrap items-center justify-between gap-4 px-5 py-4 sm:px-6">
            <div class="flex flex-wrap items-center gap-x-8 gap-y-3">
                <a href="{{ route('admin.posts.index') }}" class="flex items-baseline gap-2">
                    <span class="text-base font-semibold tracking-tight text-white">{{ config('app.name') }}</span>
                    <span class="rounded bg-slate-700 px-1.5 py-0.5 text-[10px] font-medium uppercase tracking-wider text-slate-300">admin</span>
                </a>

                <nav class="flex items-center gap-1 text-sm">
                    <a href="{{ route('admin.posts.index') }}"
                       class="rounded-md px-3 py-1.5 transition {{ request()->routeIs('admin.posts.*') ? 'bg-slate-800 text-white' : 'hover:bg-slate-800/60 hover:text-white' }}">
                        Посты
                    </a>
                    <a href="{{ route('admin.tags.index') }}"
                       class="rounded-md px-3 py-1.5 transition {{ request()->routeIs('admin.tags.*') ? 'bg-slate-800 text-white' : 'hover:bg-slate-800/60 hover:text-white' }}">
                        Теги
                    </a>
                </nav>
            </div>

            <div class="flex items-center gap-4 text-sm">
                <a href="{{ route('home') }}" target="_blank" class="text-slate-400 transition hover:text-white">Блог ↗</a>
                <span class="hidden text-slate-500 sm:inline">{{ auth()->user()?->email }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="rounded-md border border-slate-700 px-3 py-1.5 text-slate-300 transition hover:border-slate-500 hover:text-white">
                        Выйти
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="mx-auto w-full max-w-6xl px-5 py-8 sm:px-6">
        <x-flash />
        @yield('content')
    </main>
</body>
</html>
