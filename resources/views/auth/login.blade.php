@extends('layouts.guest')

@section('title', 'Вход')

@section('content')
    <div class="rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
        <div class="mb-6">
            <h1 class="text-xl font-semibold tracking-tight text-slate-900">{{ config('app.name') }}</h1>
            <p class="mt-1 text-sm text-slate-500">Вход в административную панель</p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                <input id="email" name="email" type="email" required autofocus autocomplete="username"
                       value="{{ old('email') }}"
                       class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
                @error('email')
                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-700">Пароль</label>
                <input id="password" name="password" type="password" required autocomplete="current-password"
                       class="mt-1.5 block w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-900 shadow-sm outline-none transition focus:border-slate-500 focus:ring-2 focus:ring-slate-200">
                @error('password')
                    <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <label class="flex items-center gap-2 text-sm text-slate-600">
                <input type="checkbox" name="remember" value="1"
                       class="h-4 w-4 rounded border-slate-300 text-slate-900 focus:ring-slate-400">
                Запомнить меня
            </label>

            <button type="submit"
                    class="w-full rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-medium text-white transition hover:bg-slate-700">
                Войти
            </button>
        </form>

        <p class="mt-6 border-t border-slate-100 pt-4 text-xs text-slate-400">
            Демо-доступ: <span class="text-slate-600">admin@example.com</span> / <span class="text-slate-600">password</span>
        </p>
    </div>

    <p class="mt-6 text-center text-xs text-slate-400">
        <a href="{{ route('home') }}" class="transition hover:text-slate-700">← Вернуться в блог</a>
    </p>
@endsection
