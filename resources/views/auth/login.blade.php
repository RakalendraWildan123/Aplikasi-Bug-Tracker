<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk &mdash; {{ config('app.name', 'Aplikasi Bug Tracker') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-900 antialiased">
    <div class="grid min-h-screen md:grid-cols-12">
        {{-- Panel Identitas (Desktop & Tablet Landscape) --}}
        <aside class="hidden flex-col justify-between bg-slate-950 p-10 text-slate-100 md:col-span-5 md:flex lg:col-span-6 lg:p-14">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 items-center justify-center rounded-lg border border-slate-700 bg-slate-900 text-indigo-400">
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h10M4 18h16" />
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold tracking-tight text-white">{{ config('app.name', 'Aplikasi Bug Tracker') }}</p>
                    <p class="text-xs text-slate-400">Monitoring &amp; Manajemen IT Developer</p>
                </div>
            </div>

            <div class="my-12 max-w-lg space-y-8">
                <div class="space-y-3">
                    <p class="text-xs font-semibold tracking-widest text-indigo-400 uppercase">Internal Engineering Workspace</p>
                    <h1 class="text-3xl leading-tight font-bold tracking-tight text-white lg:text-4xl">
                        Kendali penuh atas proyek, tugas, dan penanganan bug tim.
                    </h1>
                    <p class="text-sm leading-relaxed text-slate-400">
                        Satu akses masuk untuk Atasan (Admin) dan Developer tanpa alur berlapis.
                    </p>
                </div>

                <dl class="divide-y divide-slate-800 border-y border-slate-800 text-sm">
                    <div class="py-4">
                        <dt class="font-semibold text-white">Pelacakan Task &amp; Bug Terpadu</dt>
                        <dd class="mt-1 text-slate-400">Pantau status modul, prioritas severity, blocker, dan riwayat handover.</dd>
                    </div>
                    <div class="py-4">
                        <dt class="font-semibold text-white">Riwayat Rilis &amp; Evaluasi Aplikasi</dt>
                        <dd class="mt-1 text-slate-400">Catat tag rilis repositori, review berkala, serta tindak lanjut action item.</dd>
                    </div>
                    <div class="py-4">
                        <dt class="font-semibold text-white">Akses Berbasis Peran</dt>
                        <dd class="mt-1 text-slate-400">Pengelolaan akun terpusat oleh Atasan (Admin) dengan autentikasi sesi aman.</dd>
                    </div>
                </dl>
            </div>

            <p class="text-xs text-slate-500">&copy; {{ date('Y') }} {{ config('app.name', 'Aplikasi Bug Tracker') }} &middot; Internal Use Only</p>
        </aside>

        {{-- Panel Form Login --}}
        <main class="flex flex-col justify-center bg-white px-6 py-10 sm:px-10 md:col-span-7 md:px-12 lg:col-span-6 lg:px-16">
            <div class="mx-auto w-full max-w-md">
                {{-- Header Ringkas (Tablet Portrait & Mobile) --}}
                <div class="mb-8 flex items-center gap-3 border-b border-slate-200 pb-5 md:hidden">
                    <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-slate-950 text-indigo-400">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h10M4 18h16" />
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-950">{{ config('app.name', 'Aplikasi Bug Tracker') }}</p>
                        <p class="text-xs text-slate-500">Monitoring &amp; Manajemen IT Developer</p>
                    </div>
                </div>

                <div class="mb-8">
                    <h2 class="text-2xl font-bold tracking-tight text-slate-950 sm:text-3xl">Masuk ke Sistem</h2>
                    <p class="mt-2 text-sm text-slate-600">
                        Masukkan email dan password akun Atasan atau Developer Anda.
                    </p>
                </div>

                @if (session('status'))
                    <div role="status" class="mb-6 rounded-lg border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-900">
                        {{ session('status') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div role="alert" class="mb-6 rounded-lg border border-rose-300 bg-rose-50 px-4 py-3 text-sm text-rose-900">
                        <p class="font-semibold">Gagal masuk</p>
                        <ul class="mt-1 list-disc space-y-0.5 pl-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div id="login-form-app">
                    <form
                        method="POST"
                        action="{{ route('login.attempt') }}"
                        @submit="onSubmit"
                        class="space-y-5"
                    >
                        @csrf

                        <div>
                            <label for="email" class="block text-sm font-semibold text-slate-800">Alamat Email</label>
                            <input
                                id="email"
                                name="email"
                                type="email"
                                value="{{ old('email') }}"
                                required
                                autofocus
                                autocomplete="email"
                                inputmode="email"
                                placeholder="nama@bugtracker.test"
                                class="mt-1.5 block min-h-12 w-full rounded-lg border px-4 py-3 text-base text-slate-900 placeholder:text-slate-400 transition focus:outline-none focus:ring-2 sm:text-sm {{ $errors->has('email') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-200' : 'border-slate-300 focus:border-slate-900 focus:ring-slate-200' }}"
                            >
                        </div>

                        <div>
                            <label for="password" class="block text-sm font-semibold text-slate-800">Password</label>
                            <div class="relative mt-1.5">
                                <input
                                    id="password"
                                    name="password"
                                    :type="showPassword ? 'text' : 'password'"
                                    required
                                    autocomplete="current-password"
                                    placeholder="Masukkan password"
                                    class="block min-h-12 w-full rounded-lg border py-3 pr-12 pl-4 text-base text-slate-900 placeholder:text-slate-400 transition focus:outline-none focus:ring-2 sm:text-sm {{ $errors->has('password') ? 'border-rose-400 focus:border-rose-500 focus:ring-rose-200' : 'border-slate-300 focus:border-slate-900 focus:ring-slate-200' }}"
                                >
                                <button
                                    type="button"
                                    @click="showPassword = !showPassword"
                                    :aria-pressed="showPassword ? 'true' : 'false'"
                                    :aria-label="showPassword ? 'Sembunyikan password' : 'Tampilkan password'"
                                    class="absolute inset-y-0 right-0 flex w-12 items-center justify-center rounded-r-lg text-slate-500 transition hover:text-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-300"
                                >
                                    <svg v-if="!showPassword" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-7.5 9.75-7.5S21.75 12 21.75 12s-3.75 7.5-9.75 7.5S2.25 12 2.25 12Z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                    <svg v-else class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.584 10.587A3 3 0 0 0 13.413 13.416M9.88 5.09A9.77 9.77 0 0 1 12 4.5c6 0 9.75 7.5 9.75 7.5a17.24 17.24 0 0 1-3.162 4.212M6.228 6.228A17.13 17.13 0 0 0 2.25 12s3.75 7.5 9.75 7.5a9.72 9.72 0 0 0 4.272-.978" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="pt-1">
                            <label class="inline-flex min-h-10 cursor-pointer items-center gap-2.5 text-sm text-slate-700 select-none">
                                <input
                                    type="checkbox"
                                    name="remember"
                                    value="1"
                                    @checked(old('remember'))
                                    class="h-4 w-4 rounded border-slate-300 text-slate-950 focus:ring-slate-900"
                                >
                                Ingat sesi di perangkat ini
                            </label>
                        </div>

                        <button
                            type="submit"
                            :disabled="isSubmitting"
                            class="flex min-h-12 w-full cursor-pointer items-center justify-center gap-2 rounded-lg bg-slate-950 px-4 py-3 text-sm font-semibold text-white transition hover:bg-slate-800 focus:ring-2 focus:ring-slate-900 focus:ring-offset-2 focus:outline-none disabled:cursor-not-allowed disabled:opacity-60"
                        >
                            <span v-if="!isSubmitting">Masuk</span>
                            <span v-else>Memverifikasi...</span>
                        </button>
                    </form>
                </div>

                <div class="mt-8 border-t border-slate-200 pt-5 text-xs leading-relaxed text-slate-500">
                    Pembuatan akun baru dan reset password hanya dikelola oleh <span class="font-semibold text-slate-700">Atasan / Admin IT</span>.
                </div>
            </div>
        </main>
    </div>
</body>
</html>
