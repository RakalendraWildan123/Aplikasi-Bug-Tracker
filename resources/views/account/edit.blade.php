<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Akun Saya &mdash; {{ config('app.name', 'Aplikasi Bug Tracker') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased font-sans">

    {{-- Top Navigation Bar --}}
    <header class="w-full bg-white border-b border-slate-200 sticky top-0 z-10 shadow-sm">
        <div class="max-w-6xl w-full mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center">
            <div class="flex items-center gap-2.5">
                <span class="text-base font-bold text-slate-900 tracking-tight">{{ config('app.name', 'Aplikasi Bug Tracker') }}</span>
                <span class="text-slate-300 font-normal">/</span>
                <span class="text-sm font-medium text-slate-600">Akun Saya</span>
            </div>
        </div>
    </header>

    {{-- Main Container --}}
    <main class="max-w-6xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-8 sm:py-10">

        {{-- Page Header --}}
        <div class="mb-8">
            <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">Akun Saya</h1>
            <p class="mt-1.5 text-sm text-slate-600">Kelola informasi profil pribadi dan kata sandi akun Anda.</p>
        </div>

        {{-- Layout Flex Desktop & Tablet (Tanpa Tab Bersarang) --}}
        <div class="flex flex-col md:flex-row gap-6 lg:gap-8 items-start w-full">

            {{-- Kolom Kiri: Ringkasan Profil --}}
            <aside class="w-full md:w-72 lg:w-80 shrink-0 bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
                <div class="flex flex-col items-center text-center">
                    {{-- Avatar Display --}}
                    @if ($user->foto_profil_url)
                        <img src="{{ $user->foto_profil_url }}" alt="{{ $user->name }}" class="w-24 h-24 rounded-full object-cover border-2 border-slate-200 shadow-sm mb-4">
                    @else
                        <div class="w-24 h-24 rounded-full flex items-center justify-center text-white text-2xl font-bold mb-4 shadow-sm" style="background-color: {{ $user->avatar_bg ?? '#1E293B' }}">
                            {{ $user->avatar ?? 'AK' }}
                        </div>
                    @endif

                    <h2 class="text-lg font-bold text-slate-900 leading-snug">{{ $user->name }}</h2>
                    <span class="mt-1.5 inline-block px-3 py-0.5 text-xs font-semibold rounded-full bg-slate-100 text-slate-700 border border-slate-200">
                        {{ $user->roleLabel() }}
                    </span>
                </div>

                <div class="mt-6 pt-6 border-t border-slate-200 space-y-4 text-sm w-full">
                    <div>
                        <span class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Email</span>
                        <span class="font-medium text-slate-800 break-all">{{ $user->email }}</span>
                    </div>

                    <div>
                        <span class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Status Akun</span>
                        <span class="inline-flex items-center gap-1.5 font-medium text-emerald-700">
                            <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                            Aktif
                        </span>
                    </div>

                    @if ($user->isDeveloper() && $user->role_title)
                    <div>
                        <span class="block text-xs font-semibold uppercase tracking-wider text-slate-400">Spesialisasi</span>
                        <span class="font-medium text-slate-800">{{ $user->role_title }}</span>
                    </div>
                    @endif
                </div>

                {{-- Fitur Logout di Halaman Akun Saya --}}
                <div class="mt-6 pt-6 border-t border-slate-200 w-full">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-lg border border-rose-200 bg-rose-50 px-4 py-2.5 text-sm font-semibold text-rose-700 transition hover:bg-rose-100 hover:border-rose-300 focus:ring-2 focus:ring-rose-500 focus:outline-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
                            </svg>
                            Logout
                        </button>
                    </form>
                </div>
            </aside>

            {{-- Kolom Kanan: Form Edit Profil & Password (Mengalir Tegas, Tanpa Tab) --}}
            <div class="w-full flex-1 min-w-0 space-y-6">

                {{-- Card 1: Edit Informasi Profil --}}
                <section class="w-full bg-white border border-slate-200 rounded-xl p-6 sm:p-7 shadow-sm">
                    <div class="border-b border-slate-200 pb-4 mb-6">
                        <h2 class="text-lg font-bold text-slate-900">Informasi Profil</h2>
                        <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Perbarui nama lengkap dan foto profil akun Anda.</p>
                    </div>

                    {{-- Status Notifikasi Profil --}}
                    @if (session('profile_status'))
                        <div role="status" class="mb-5 rounded-lg border border-emerald-200 bg-emerald-50 px-3.5 py-2.5 text-sm text-emerald-800 flex items-center gap-2">
                            <svg class="w-4 h-4 shrink-0 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                            </svg>
                            {{ session('profile_status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('account.profile.update') }}" enctype="multipart/form-data" class="space-y-5 w-full">
                        @csrf
                        @method('PUT')

                        {{-- Input Nama Lengkap (Dapat Diedit) --}}
                        <div class="w-full">
                            <label for="name" class="block text-sm font-semibold text-slate-800">
                                Nama Lengkap <span class="text-rose-500">*</span>
                            </label>
                            <input
                                id="name"
                                type="text"
                                name="name"
                                value="{{ old('name', $user->name) }}"
                                required
                                class="mt-1.5 block w-full rounded-lg border px-3.5 py-2 text-sm text-slate-900 transition focus:outline-none sm:text-sm {{ $errors->has('name') ? 'border-rose-400 focus:border-rose-500 focus:ring-1 focus:ring-rose-500' : 'border-slate-300 focus:border-slate-900 focus:ring-1 focus:ring-slate-900' }}"
                            >
                            @error('name')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Input Email (TIDAK DAPAT DIEDIT / PERMANEN) --}}
                        <div class="w-full">
                            <div class="flex items-center justify-between mb-1">
                                <label for="email" class="block text-sm font-semibold text-slate-800">Email Akun</label>
                                <span class="text-xs font-semibold px-2 py-0.5 rounded bg-slate-100 text-slate-600 border border-slate-200">
                                    Permanen (Tidak dapat diubah)
                                </span>
                            </div>
                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ $user->email }}"
                                readonly
                                tabindex="-1"
                                class="mt-1 block w-full rounded-lg border border-slate-200 bg-slate-100 px-3.5 py-2 text-sm text-slate-600 cursor-not-allowed select-none"
                            >
                            <p class="text-xs text-slate-500 mt-1">Alamat email akun bersifat permanen untuk keamanan identitas Anda.</p>
                        </div>

                        {{-- Input Foto Profil --}}
                        <div class="w-full">
                            <label for="foto_profil" class="block text-sm font-semibold text-slate-800">Ganti Foto Profil</label>
                            <input
                                id="foto_profil"
                                type="file"
                                name="foto_profil"
                                accept="image/png,image/jpeg,image/webp"
                                class="mt-1.5 block w-full text-sm text-slate-600 file:mr-4 file:py-2 file:px-3.5 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-slate-100 file:text-slate-800 hover:file:bg-slate-200 file:cursor-pointer"
                            >
                            <p class="text-xs text-slate-500 mt-1">Format: JPG, PNG, atau WebP (Maksimal 2 MB).</p>
                            @error('foto_profil')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror

                            @if ($user->foto_profil)
                                <div class="mt-2.5 flex items-center gap-2">
                                    <label class="inline-flex items-center gap-2 text-xs font-medium text-rose-600 cursor-pointer">
                                        <input type="checkbox" name="hapus_foto_profil" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                        Hapus foto profil saat ini dan gunakan inisial
                                    </label>
                                </div>
                            @endif
                        </div>

                        <div class="pt-2">
                            <button
                                type="submit"
                                class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 focus:ring-2 focus:ring-slate-900 focus:ring-offset-2 focus:outline-none"
                            >
                                Simpan Profil
                            </button>
                        </div>
                    </form>
                </section>

                {{-- Card 2: Ubah Password Akun --}}
                <section class="w-full bg-white border border-slate-200 rounded-xl p-6 sm:p-7 shadow-sm">
                    <div class="border-b border-slate-200 pb-4 mb-6">
                        <h2 class="text-lg font-bold text-slate-900">Ubah Password</h2>
                        <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Pastikan akun menggunakan password baru minimal 8 karakter.</p>
                    </div>

                    {{-- Status Notifikasi Password --}}
                    @if (session('password_status'))
                        <div role="status" class="mb-5 rounded-lg border border-emerald-200 bg-emerald-50 px-3.5 py-2.5 text-sm text-emerald-800 flex items-center gap-2">
                            <svg class="w-4 h-4 shrink-0 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                            </svg>
                            {{ session('password_status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('account.password.update') }}" class="space-y-4 w-full">
                        @csrf
                        @method('PUT')

                        {{-- Password Saat Ini --}}
                        <div class="w-full">
                            <label for="current_password" class="block text-sm font-semibold text-slate-800">
                                Password Saat Ini <span class="text-rose-500">*</span>
                            </label>
                            <input
                                id="current_password"
                                type="password"
                                name="current_password"
                                required
                                autocomplete="current-password"
                                placeholder="••••••••"
                                class="mt-1.5 block w-full rounded-lg border px-3.5 py-2 text-sm text-slate-900 transition focus:outline-none sm:text-sm {{ $errors->has('current_password') ? 'border-rose-400 focus:border-rose-500 focus:ring-1 focus:ring-rose-500' : 'border-slate-300 focus:border-slate-900 focus:ring-1 focus:ring-slate-900' }}"
                            >
                            @error('current_password')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password Baru --}}
                        <div class="w-full">
                            <label for="password" class="block text-sm font-semibold text-slate-800">
                                Password Baru <span class="text-rose-500">*</span>
                            </label>
                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="new-password"
                                placeholder="Minimal 8 karakter"
                                class="mt-1.5 block w-full rounded-lg border px-3.5 py-2 text-sm text-slate-900 transition focus:outline-none sm:text-sm {{ $errors->has('password') ? 'border-rose-400 focus:border-rose-500 focus:ring-1 focus:ring-rose-500' : 'border-slate-300 focus:border-slate-900 focus:ring-1 focus:ring-slate-900' }}"
                            >
                            @error('password')
                                <p class="text-xs text-rose-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Konfirmasi Password Baru --}}
                        <div class="w-full">
                            <label for="password_confirmation" class="block text-sm font-semibold text-slate-800">
                                Konfirmasi Password Baru <span class="text-rose-500">*</span>
                            </label>
                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                placeholder="Ulangi password baru"
                                class="mt-1.5 block w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-900 transition focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900 sm:text-sm"
                            >
                        </div>

                        <div class="pt-2">
                            <button
                                type="submit"
                                class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 focus:ring-2 focus:ring-slate-900 focus:ring-offset-2 focus:outline-none"
                            >
                                Perbarui Password
                            </button>
                        </div>
                    </form>
                </section>

            </div>
        </div>
    </main>

</body>
</html>
