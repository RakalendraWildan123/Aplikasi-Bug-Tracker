<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk &mdash; {{ config('app.name', 'Aplikasi Bug Tracker') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-screen items-center justify-center bg-slate-50 px-4 py-12 text-slate-900 antialiased">
    <div class="w-full max-w-md rounded-2xl border border-slate-200 bg-white p-8 shadow-sm">
        <div class="mb-6">
            <p class="text-xs font-semibold tracking-wider text-indigo-600 uppercase">{{ config('app.name', 'Aplikasi Bug Tracker') }}</p>
            <h1 class="mt-1 text-2xl font-bold text-slate-900">Masuk ke Akun</h1>
            <p class="mt-1 text-sm text-slate-600">Gunakan akun Atasan (Admin) atau Developer yang telah terdaftar.</p>
        </div>

        @if (session('status'))
            <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                <ul class="list-disc space-y-1 pl-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login.attempt') }}" class="space-y-4">
            @csrf

            <div>
                <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    required
                    autofocus
                    autocomplete="email"
                    placeholder="nama@bugtracker.test"
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm shadow-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:outline-none"
                >
            </div>

            <div>
                <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    required
                    autocomplete="current-password"
                    class="mt-1 block w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm shadow-xs focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 focus:outline-none"
                >
            </div>

            <div class="flex items-center justify-between">
                <label class="inline-flex items-center gap-2 text-sm text-slate-600">
                    <input type="checkbox" name="remember" value="1" class="rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                    Ingat saya
                </label>
                <a href="{{ route('home') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">Kembali ke Beranda</a>
            </div>

            <button
                type="submit"
                class="w-full rounded-lg bg-indigo-600 px-4 py-2.5 text-sm font-semibold text-white shadow-xs transition hover:bg-indigo-700"
            >
                Masuk
            </button>
        </form>
    </div>
</body>
</html>
