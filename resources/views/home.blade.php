<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login Berhasil &mdash; {{ config('app.name', 'Aplikasi Bug Tracker') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 flex items-center justify-center p-4 text-slate-900 antialiased">
    <div class="w-full max-w-sm bg-white border border-slate-200 rounded-xl shadow-sm p-6 sm:p-8 text-center">
        <h1 class="text-2xl font-bold text-slate-900 mb-2">Login Berhasil</h1>
        <p class="text-sm text-slate-600 mb-6">Halo, {{ auth()->user()->name }}</p>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="text-sm font-semibold text-rose-600 hover:text-rose-700 transition">
                Logout
            </button>
        </form>
    </div>
</body>
</html>
