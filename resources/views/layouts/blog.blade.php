<!DOCTYPE html>
<html lang="ru" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('description', 'Код, истории и мысли.')">
    <title>@yield('title', config('app.name'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#f8f7f4] text-slate-800 antialiased">
    <header class="border-b border-slate-200 bg-white/80 backdrop-blur">
        <div class="mx-auto flex max-w-3xl items-center justify-between gap-4 px-5 py-5 sm:px-6">
            <a href="{{ route('home') }}" class="group">
                <span class="block text-lg font-semibold tracking-tight text-slate-900">{{ config('app.name') }}</span>
                <span class="mt-0.5 block text-[11px] uppercase tracking-[0.22em] text-slate-400">Код, истории и мысли</span>
            </a>

            <nav class="flex items-center gap-5 text-sm text-slate-500">
                @auth
                    <a href="{{ route('admin.posts.index') }}" class="transition hover:text-slate-900">Админка</a>
                @else
                    <a href="{{ route('login') }}" class="transition hover:text-slate-900">Вход</a>
                @endauth
            </nav>
        </div>
    </header>

    <main class="mx-auto w-full max-w-3xl px-5 py-10 sm:px-6 sm:py-14">
        <x-flash />
        @yield('content')
    </main>

    <footer class="mx-auto w-full max-w-3xl px-5 pb-10 text-xs text-slate-400 sm:px-6">
        <div class="border-t border-slate-200 pt-6">
            {{ now()->year }} · {{ config('app.name') }} · демонстрационное приложение на Laravel
        </div>
    </footer>
</body>
</html>
