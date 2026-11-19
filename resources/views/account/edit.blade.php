<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Akun Saya &mdash; {{ config('app.name', 'Aplikasi Bug Tracker') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased font-sans flex flex-col lg:flex-row">

    {{-- Backdrop Sidebar (Tablet Portrait & Mobile) --}}
    <div
        id="sidebar-backdrop"
        class="fixed inset-0 z-40 bg-slate-950/60 backdrop-blur-xs hidden lg:hidden"
        aria-hidden="true"
    ></div>

    {{-- Navigasi Sidebar di Sebelah Kiri --}}
    <aside
        id="app-sidebar"
        class="fixed inset-y-0 left-0 z-50 flex w-64 sm:w-68 -translate-x-full flex-col justify-between border-r border-slate-800 bg-slate-900 text-slate-100 transition-transform duration-200 ease-in-out lg:static lg:translate-x-0 lg:min-h-screen shrink-0 shadow-lg lg:shadow-none"
    >
        <div class="flex flex-col flex-1 min-h-0">
            {{-- Brand Header Sidebar --}}
            <div class="flex h-16 items-center justify-between border-b border-slate-800 px-5">
                <div class="flex items-center gap-3">
                    <div class="flex h-9 w-9 items-center justify-center rounded-lg bg-indigo-600 text-white shadow-sm shrink-0">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 18v-5.25m0 0a6.01 6.01 0 001.5-.189m-1.5.189a6.01 6.01 0 01-1.5-.189m3.75 7.478a12.06 12.06 0 01-4.5 0m3.75 2.383a14.406 14.406 0 01-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 10-7.516 0c.85.493 1.508 1.333 1.508 2.316V18" />
                        </svg>
                    </div>
                    <div class="leading-tight min-w-0">
                        <span class="block text-sm font-bold tracking-tight text-white truncate">{{ config('app.name', 'Aplikasi Bug Tracker') }}</span>
                        <span class="block text-[11px] font-medium text-slate-400">IT Dev Workspace</span>
                    </div>
                </div>

                {{-- Tombol Tutup Sidebar untuk Layar Tablet/Mobile --}}
                <button
                    type="button"
                    id="btn-sidebar-close"
                    class="p-1.5 rounded-lg text-slate-400 hover:text-white hover:bg-slate-800 lg:hidden cursor-pointer"
                    aria-label="Tutup menu navigasi"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Menu Navigasi Sidebar (Dipisahkan Berdasarkan Kategori Fitur) --}}
            <nav class="flex-1 px-3 py-5 space-y-5 overflow-y-auto" aria-label="Navigasi Sidebar">
                {{-- KATEGORI 1: FITUR OPERASIONAL --}}
                <div class="space-y-1">
                    <p class="px-3 pb-1.5 text-[11px] font-bold uppercase tracking-wider text-slate-400">
                        <span>Fitur Operasional</span>
                        <span class="sr-only">Menu Utama</span>
                    </p>

                    {{-- Fitur Papan Kanban --}}
                    <a
                        href="{{ route('kanban.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3.5 py-2.5 text-sm font-medium transition text-slate-300 hover:bg-slate-800 hover:text-white"
                    >
                        <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 4.5v15m6-15v15m-10.875 0h15.75c.621 0 1.125-.504 1.125-1.125V5.625c0-.621-.504-1.125-1.125-1.125H4.125C3.504 4.5 3 5.004 3 5.625v12.75c0 .621.504 1.125 1.125 1.125z"/>
                        </svg>
                        <span>Papan Kanban</span>
                    </a>

                    {{-- Fitur Daftar Tugas --}}
                    <a
                        href="{{ route('tasks.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3.5 py-2.5 text-sm font-medium transition text-slate-300 hover:bg-slate-800 hover:text-white"
                    >
                        <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 17.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                        </svg>
                        <span>Daftar Tugas</span>
                    </a>

                    {{-- Fitur Pelacak Bug --}}
                    <a
                        href="{{ route('bugs.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3.5 py-2.5 text-sm font-medium transition text-slate-300 hover:bg-slate-800 hover:text-white"
                    >
                        <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.002A11.959 11.959 0 0112 2.714zm0 13.036a.75.75 0 100-1.5.75.75 0 000 1.5z" />
                        </svg>
                        <span>Pelacak Bug</span>
                    </a>
                </div>

                {{-- KATEGORI 2: FITUR MASTER DATA --}}
                <div class="space-y-1 pt-4 border-t border-slate-800/80">
                    <p class="px-3 pb-1.5 text-[11px] font-bold uppercase tracking-wider text-slate-400">Fitur Master Data</p>

                    {{-- Fitur Master Proyek --}}
                    <a
                        href="{{ route('projects.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3.5 py-2.5 text-sm font-medium transition text-slate-300 hover:bg-slate-800 hover:text-white"
                    >
                        <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z"/>
                        </svg>
                        <span>Master Proyek</span>
                    </a>

                    {{-- Fitur Master Modul --}}
                    <a
                        href="{{ route('modules.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3.5 py-2.5 text-sm font-medium transition text-slate-300 hover:bg-slate-800 hover:text-white"
                    >
                        <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                        </svg>
                        <span>Master Modul</span>
                    </a>

                    @if ($user->isAdmin())
                        {{-- Fitur Master Akun --}}
                        <a
                            href="{{ route('admin.accounts.index') }}"
                            class="flex items-center gap-3 rounded-lg px-3.5 py-2.5 text-sm font-medium transition text-slate-300 hover:bg-slate-800 hover:text-white"
                        >
                            <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0zm6 3a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0zm-13.5 0a2.25 2.25 0 11-4.5 0 2.25 2.25 0 014.5 0z" />
                            </svg>
                            <span>Master Akun</span>
                        </a>
                    @endif
                </div>

                {{-- KATEGORI 3: PENGATURAN / AKUN PRIBADI --}}
                <div class="space-y-1 pt-4 border-t border-slate-800/80">
                    <p class="px-3 pb-1.5 text-[11px] font-bold uppercase tracking-wider text-slate-400">Pengaturan</p>

                    {{-- Fitur Akun Saya (Active State) --}}
                    <a
                        href="{{ route('account.edit') }}"
                        class="flex items-center gap-3 rounded-lg px-3.5 py-2.5 text-sm font-semibold transition bg-slate-800 text-white border border-slate-700/70 shadow-xs"
                    >
                        <svg class="w-5 h-5 text-indigo-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                        </svg>
                        <span>Akun Saya</span>
                    </a>
                </div>
            </nav>
        </div>
    </aside>

    {{-- Area Konten Utama (Kanan) --}}
    <div class="flex-1 min-w-0 flex flex-col min-h-screen">

        {{-- Topbar Khusus Layar Tablet & Mobile --}}
        <header class="lg:hidden w-full bg-white border-b border-slate-200 h-16 flex items-center justify-between px-4 sm:px-6 sticky top-0 z-30 shadow-xs">
            <div class="flex items-center gap-3">
                <button
                    type="button"
                    id="btn-sidebar-open"
                    class="p-2 rounded-lg text-slate-700 hover:bg-slate-100 cursor-pointer"
                    aria-label="Buka menu navigasi sidebar"
                >
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                    </svg>
                </button>
                <span class="text-base font-bold text-slate-900 tracking-tight">{{ config('app.name', 'Aplikasi Bug Tracker') }}</span>
            </div>

            <div class="flex items-center gap-2">
                @if ($user->foto_profil_url)
                    <img src="{{ $user->foto_profil_url }}" alt="{{ $user->name }}" class="w-8 h-8 rounded-full object-cover border border-slate-200">
                @else
                    <div class="w-8 h-8 rounded-full flex items-center justify-center text-xs font-bold text-white shadow-xs" style="background-color: {{ $user->avatar_bg ?? '#1E293B' }}">
                        {{ $user->avatar ?? 'AK' }}
                    </div>
                @endif
            </div>
        </header>

        {{-- Konten Halaman Akun Saya --}}
        <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-6xl w-full mx-auto">

            {{-- Judul Halaman --}}
            <div class="mb-8">
                <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">Akun Saya</h1>
                <p class="mt-1.5 text-sm text-slate-600">Rincian informasi akun dan profil Anda.</p>
            </div>

            {{-- Status Notifikasi Berhasil --}}
            @if (session('profile_status'))
                <div role="status" class="mb-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800 flex items-center gap-2.5">
                    <svg class="w-5 h-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span class="font-medium">{{ session('profile_status') }}</span>
                </div>
            @endif

            {{-- Layout Grid Desktop & Tablet (Tanpa Tab Bersarang) --}}
            <div class="flex flex-col md:flex-row gap-6 lg:gap-8 items-start w-full">

                {{-- Kolom Kiri: Ringkasan Profil & Tombol Logout Asli --}}
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

                    {{-- Fitur Logout Asli dalam Fitur Akun Saya (Tidak Dirubah) --}}
                    <div class="mt-6 pt-6 border-t border-slate-200 w-full">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full inline-flex items-center justify-center gap-2 rounded-lg border border-rose-200 bg-rose-50 px-4 py-2.5 text-sm font-semibold text-rose-700 transition hover:bg-rose-100 hover:border-rose-300 focus:ring-2 focus:ring-rose-500 focus:outline-none cursor-pointer">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9"/>
                                </svg>
                                Logout
                            </button>
                        </form>
                    </div>
                </aside>

                {{-- Kolom Kanan: Tampilan Informasi Saja (Read-Only) + Tombol Buka Pop-up Edit --}}
                <div class="w-full flex-1 min-w-0 space-y-6">

                    <section class="w-full bg-white border border-slate-200 rounded-xl p-6 sm:p-8 shadow-sm">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between pb-5 border-b border-slate-200 gap-4">
                            <div>
                                <h2 class="text-xl font-bold text-slate-900">Informasi Akun</h2>
                                <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Rincian data profil dan keamanan akun Anda saat ini.</p>
                            </div>

                            {{-- Tombol Buka Pop-up Edit --}}
                            <button
                                type="button"
                                id="btn-open-modal"
                                class="inline-flex items-center justify-center gap-2 rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 focus:ring-2 focus:ring-slate-900 focus:ring-offset-2 focus:outline-none shrink-0 cursor-pointer shadow-sm"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                </svg>
                                Edit Akun
                            </button>
                        </div>

                        {{-- Daftar Rincian Informasi Akun (Read-only) --}}
                        <div class="mt-6 divide-y divide-slate-100 text-sm">

                            <div class="py-4 grid grid-cols-1 sm:grid-cols-3 gap-2">
                                <span class="font-semibold text-slate-500">Nama Lengkap</span>
                                <span class="sm:col-span-2 font-medium text-slate-900 text-base">{{ $user->name }}</span>
                            </div>

                            <div class="py-4 grid grid-cols-1 sm:grid-cols-3 gap-2 items-center">
                                <span class="font-semibold text-slate-500">Alamat Email</span>
                                <span class="sm:col-span-2 font-medium text-slate-900 text-base break-all">{{ $user->email }}</span>
                            </div>

                            <div class="py-4 grid grid-cols-1 sm:grid-cols-3 gap-2 items-center">
                                <span class="font-semibold text-slate-500">Kata Sandi (Password)</span>
                                <div class="sm:col-span-2 flex items-center gap-2 text-slate-700">
                                    <span class="font-mono text-lg tracking-widest text-slate-600">••••••••••••</span>
                                    <span class="text-xs text-slate-400 font-medium">(Tersimpan aman & terenkripsi)</span>
                                </div>
                            </div>

                            <div class="py-4 grid grid-cols-1 sm:grid-cols-3 gap-2 items-center">
                                <span class="font-semibold text-slate-500">Foto Profil</span>
                                <div class="sm:col-span-2 flex items-center gap-3">
                                    @if ($user->foto_profil_url)
                                        <img src="{{ $user->foto_profil_url }}" alt="{{ $user->name }}" class="w-12 h-12 rounded-full object-cover border border-slate-200 shadow-xs">
                                        <span class="text-xs text-slate-500">Foto kustom terpasang</span>
                                    @else
                                        <div class="w-10 h-10 rounded-full flex items-center justify-center text-white text-sm font-bold shadow-xs" style="background-color: {{ $user->avatar_bg ?? '#1E293B' }}">
                                            {{ $user->avatar ?? 'AK' }}
                                        </div>
                                        <span class="text-xs text-slate-500">Menggunakan inisial avatar bawaan</span>
                                    @endif
                                </div>
                            </div>

                            <div class="py-4 grid grid-cols-1 sm:grid-cols-3 gap-2">
                                <span class="font-semibold text-slate-500">Hak Akses / Peran</span>
                                <span class="sm:col-span-2 font-medium text-slate-900">{{ $user->roleLabel() }}</span>
                            </div>
                        </div>

                        <div class="mt-6 pt-5 border-t border-slate-100 flex items-center gap-2 text-xs text-slate-500">
                            <svg class="w-4 h-4 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                            </svg>
                            <span>Untuk mengubah nama, foto profil, atau kata sandi, klik tombol <strong>Edit Akun</strong> dan konfirmasi password Anda terlebih dahulu.</span>
                        </div>
                    </section>

                </div>
            </div>

        </main>
    </div>

    {{-- MODAL POP-UP EDIT AKUN (Tidak Pindah Halaman) --}}
    <div
        id="modal-edit"
        class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden"
        aria-labelledby="modal-title"
        role="dialog"
        aria-modal="true"
    >
        <div class="bg-white border border-slate-200 rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden transition-all transform duration-200">

            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-slate-50">
                <div class="flex items-center gap-2">
                    <div class="w-8 h-8 rounded-lg bg-slate-900 text-white flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                        </svg>
                    </div>
                    <h3 id="modal-title" class="text-lg font-bold text-slate-900">Edit Akun</h3>
                </div>
                <button
                    type="button"
                    id="btn-close-modal"
                    class="text-slate-400 hover:text-slate-700 p-1 rounded-lg hover:bg-slate-200/60 transition cursor-pointer"
                    aria-label="Tutup"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- TAHAP 1: FORM VERIFIKASI PASSWORD TERLEBIH DAHULU --}}
            <div id="step-verification" class="p-6">
                <div class="text-center mb-6">
                    <div class="w-12 h-12 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center mx-auto mb-3 text-slate-800">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 15v2m-6 4h12a2.25 2.25 0 002.25-2.25v-6.75A2.25 2.25 0 0018 10.5H6a2.25 2.25 0 00-2.25 2.25v6.75A2.25 2.25 0 006 21zm10.5-10.5a4.5 4.5 0 10-9 0v3.75h9V10.5z"/>
                        </svg>
                    </div>
                    <h4 class="text-base font-bold text-slate-900">Verifikasi Kata Sandi</h4>
                    <p class="text-xs sm:text-sm text-slate-600 mt-1">Anda harus memasukkan kata sandi akun saat ini terlebih dahulu sebelum dapat membuka formulir pengeditan akun.</p>
                </div>

                {{-- Alert Error Verifikasi Password --}}
                <div id="verify-error" class="hidden mb-4 rounded-lg border border-rose-200 bg-rose-50 px-3.5 py-2.5 text-xs sm:text-sm text-rose-800 flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0 text-rose-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                    </svg>
                    <span id="verify-error-text">Password yang Anda masukkan tidak sesuai.</span>
                </div>

                <div class="space-y-4">
                    <div>
                        <label for="input-verify-password" class="block text-sm font-semibold text-slate-800">
                            Password Saat Ini <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative mt-1.5">
                            <input
                                id="input-verify-password"
                                type="password"
                                placeholder="Masukkan password akun Anda"
                                class="block w-full rounded-lg border border-slate-300 py-2.5 pr-11 pl-3.5 text-sm text-slate-900 transition focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900"
                            >
                            <button
                                type="button"
                                class="btn-toggle-eye absolute inset-y-0 right-0 flex w-11 items-center justify-center text-slate-400 hover:text-slate-700 cursor-pointer"
                                aria-label="Lihat password"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </button>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-2.5 pt-2">
                        <button
                            type="button"
                            class="btn-cancel-modal px-4 py-2.5 rounded-lg border border-slate-300 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition cursor-pointer"
                        >
                            Batal
                        </button>
                        <button
                            type="button"
                            id="btn-submit-verify"
                            class="inline-flex items-center justify-center gap-2 rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-800 transition cursor-pointer"
                        >
                            <span id="verify-btn-text">Verifikasi Password</span>
                            <svg id="verify-spinner" class="hidden w-4 h-4 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            {{-- TAHAP 2: FORM PENGEDITAN DATA (Muncul setelah password terverifikasi) --}}
            <div id="step-edit-form" class="hidden p-6 max-h-[80vh] overflow-y-auto">
                <div class="mb-5 pb-3 border-b border-slate-100">
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200 mb-2">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                        Password Terverifikasi
                    </span>
                    <h4 class="text-base font-bold text-slate-900">Form Pengeditan Akun</h4>
                    <p class="text-xs text-slate-500">Silakan perbarui nama, foto profil, atau ubah kata sandi baru Anda.</p>
                </div>

                @if ($errors->any())
                    <div class="mb-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-xs sm:text-sm text-rose-800">
                        <div class="font-semibold mb-1 flex items-center gap-1.5 text-rose-800">
                            <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                            </svg>
                            Terdapat kesalahan pada isian form:
                        </div>
                        <ul class="list-disc list-inside space-y-0.5 text-rose-700">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('account.profile.update') }}" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    @method('PUT')

                    {{-- Hidden input password saat ini yang sudah diverifikasi --}}
                    <input type="hidden" name="current_password" id="hidden-current-password" value="{{ old('current_password') }}">

                    {{-- 1. Nama Lengkap (Dapat Diedit) --}}
                    <div>
                        <label for="edit-name" class="block text-sm font-semibold text-slate-800">
                            Nama Lengkap <span class="text-rose-500">*</span>
                        </label>
                        <input
                            id="edit-name"
                            type="text"
                            name="name"
                            value="{{ old('name', $user->name) }}"
                            required
                            class="mt-1 block w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-900 transition focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900"
                        >
                    </div>

                    {{-- 2. Email Akun (Read-only) --}}
                    <div>
                        <label for="edit-email" class="block text-sm font-semibold text-slate-800 mb-1">Email Akun</label>
                        <input
                            id="edit-email"
                            type="email"
                            value="{{ $user->email }}"
                            readonly
                            tabindex="-1"
                            class="block w-full rounded-lg border border-slate-200 bg-slate-100 px-3.5 py-2 text-sm text-slate-600 cursor-not-allowed select-none"
                        >
                    </div>

                    {{-- 3. Foto Profil --}}
                    <div>
                        <label for="edit-foto" class="block text-sm font-semibold text-slate-800">Ganti Foto Profil</label>
                        <input
                            id="edit-foto"
                            type="file"
                            name="foto_profil"
                            accept="image/png,image/jpeg,image/webp"
                            class="mt-1 block w-full text-sm text-slate-600 file:mr-3 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-800 hover:file:bg-slate-200 file:cursor-pointer"
                        >
                        <p class="text-xs text-slate-500 mt-1">Format: JPG, PNG, atau WebP (Maks. 2 MB).</p>

                        @if ($user->foto_profil)
                            <div class="mt-2">
                                <label class="inline-flex items-center gap-2 text-xs font-medium text-rose-600 cursor-pointer">
                                    <input type="checkbox" name="hapus_foto_profil" value="1" class="rounded border-slate-300 text-rose-600 focus:ring-rose-500">
                                    Hapus foto saat ini (kembali gunakan inisial)
                                </label>
                            </div>
                        @endif
                    </div>

                    {{-- 4. Opsi Ganti Password Baru --}}
                    <div class="pt-3 border-t border-slate-200">
                        <span class="block text-xs font-bold uppercase tracking-wider text-slate-400 mb-2">Ganti Kata Sandi (Opsional)</span>
                        <div class="space-y-3">
                            <div>
                                <label for="edit-new-password" class="block text-xs font-semibold text-slate-700">Password Baru</label>
                                <div class="relative mt-1">
                                    <input
                                        id="edit-new-password"
                                        type="password"
                                        name="new_password"
                                        autocomplete="new-password"
                                        placeholder="Kosongkan jika tidak ingin mengubah"
                                        class="block w-full rounded-lg border border-slate-300 py-2 pr-11 pl-3.5 text-sm text-slate-900 transition focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900"
                                    >
                                    <button
                                        type="button"
                                        class="btn-toggle-eye absolute inset-y-0 right-0 flex w-11 items-center justify-center text-slate-400 hover:text-slate-700 cursor-pointer"
                                        aria-label="Lihat password baru"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label for="edit-new-password-confirm" class="block text-xs font-semibold text-slate-700">Konfirmasi Password Baru</label>
                                <div class="relative mt-1">
                                    <input
                                        id="edit-new-password-confirm"
                                        type="password"
                                        name="new_password_confirmation"
                                        autocomplete="new-password"
                                        placeholder="Ulangi password baru"
                                        class="block w-full rounded-lg border border-slate-300 py-2 pr-11 pl-3.5 text-sm text-slate-900 transition focus:outline-none focus:border-slate-900 focus:ring-1 focus:ring-slate-900"
                                    >
                                    <button
                                        type="button"
                                        class="btn-toggle-eye absolute inset-y-0 right-0 flex w-11 items-center justify-center text-slate-400 hover:text-slate-700 cursor-pointer"
                                        aria-label="Lihat konfirmasi password baru"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol Aksi Form --}}
                    <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-200">
                        <button
                            type="button"
                            class="btn-cancel-modal px-4 py-2.5 rounded-lg border border-slate-300 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition cursor-pointer"
                        >
                            Batal
                        </button>
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-800 transition cursor-pointer shadow-sm"
                        >
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    {{-- Script Interaktivitas Pop-Up Modal & Sidebar --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Kontrol Sidebar Responsif
            const sidebar = document.getElementById('app-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            const btnSidebarOpen = document.getElementById('btn-sidebar-open');
            const btnSidebarClose = document.getElementById('btn-sidebar-close');

            const openSidebar = () => {
                if (!sidebar || !backdrop) return;
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            };

            const closeSidebar = () => {
                if (!sidebar || !backdrop) return;
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            };

            btnSidebarOpen?.addEventListener('click', openSidebar);
            btnSidebarClose?.addEventListener('click', closeSidebar);
            backdrop?.addEventListener('click', closeSidebar);

            // Kontrol Modal Pop-up Edit Akun
            const modal = document.getElementById('modal-edit');
            const btnOpenModal = document.getElementById('btn-open-modal');
            const btnCloseModal = document.getElementById('btn-close-modal');
            const cancelButtons = document.querySelectorAll('.btn-cancel-modal');

            const stepVerify = document.getElementById('step-verification');
            const stepEditForm = document.getElementById('step-edit-form');

            const inputVerifyPassword = document.getElementById('input-verify-password');
            const btnSubmitVerify = document.getElementById('btn-submit-verify');
            const verifyError = document.getElementById('verify-error');
            const verifyErrorText = document.getElementById('verify-error-text');
            const verifyBtnText = document.getElementById('verify-btn-text');
            const verifySpinner = document.getElementById('verify-spinner');
            const hiddenCurrentPassword = document.getElementById('hidden-current-password');

            // Buka Modal Pop-up
            btnOpenModal?.addEventListener('click', () => {
                modal.classList.remove('hidden');
                stepVerify.classList.remove('hidden');
                stepEditForm.classList.add('hidden');
                inputVerifyPassword.value = '';
                verifyError.classList.add('hidden');
                setTimeout(() => inputVerifyPassword.focus(), 100);
            });

            // Tutup Modal
            const closeModal = () => {
                modal.classList.add('hidden');
                inputVerifyPassword.value = '';
                verifyError.classList.add('hidden');
            };

            btnCloseModal?.addEventListener('click', closeModal);
            cancelButtons.forEach(btn => btn.addEventListener('click', closeModal));

            // Tutup modal jika klik backdrop di luar dialog
            modal?.addEventListener('click', (e) => {
                if (e.target === modal) {
                    closeModal();
                }
            });

            // Tutup modal jika tekan tombol Escape
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    if (!modal.classList.contains('hidden')) {
                        closeModal();
                    } else if (sidebar && !sidebar.classList.contains('-translate-x-full')) {
                        closeSidebar();
                    }
                }
            });

            @if ($errors->any())
                // Jika terdapat error validasi setelah submit, buka kembali modal pada tahap form edit
                modal.classList.remove('hidden');
                stepVerify.classList.add('hidden');
                stepEditForm.classList.remove('hidden');
            @endif

            // Toggle intip password
            document.querySelectorAll('.btn-toggle-eye').forEach(btn => {
                btn.addEventListener('click', () => {
                    const input = btn.previousElementSibling;
                    if (input) {
                        input.type = input.type === 'password' ? 'text' : 'password';
                    }
                });
            });

            // Proses Verifikasi Password via AJAX
            const executeVerify = async () => {
                const password = inputVerifyPassword.value.trim();
                if (!password) {
                    verifyError.classList.remove('hidden');
                    verifyErrorText.textContent = 'Silakan masukkan password akun Anda terlebih dahulu.';
                    inputVerifyPassword.focus();
                    return;
                }

                // Tampilkan loading
                btnSubmitVerify.disabled = true;
                verifyBtnText.textContent = 'Memverifikasi...';
                verifySpinner.classList.remove('hidden');
                verifyError.classList.add('hidden');

                try {
                    const response = await fetch("{{ route('account.verify-password') }}", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ password: password })
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        // Password benar! Masukkan password ke input hidden dan buka Tahap 2 Form Edit
                        hiddenCurrentPassword.value = password;
                        stepVerify.classList.add('hidden');
                        stepEditForm.classList.remove('hidden');
                        document.getElementById('edit-name').focus();
                    } else {
                        // Password salah
                        verifyError.classList.remove('hidden');
                        verifyErrorText.textContent = data.message || 'Password yang Anda masukkan salah.';
                        inputVerifyPassword.select();
                    }
                } catch (error) {
                    verifyError.classList.remove('hidden');
                    verifyErrorText.textContent = 'Terjadi kesalahan saat memverifikasi. Silakan coba lagi.';
                } finally {
                    btnSubmitVerify.disabled = false;
                    verifyBtnText.textContent = 'Verifikasi Password';
                    verifySpinner.classList.add('hidden');
                }
            };

            btnSubmitVerify?.addEventListener('click', executeVerify);

            // Bisa tekan Enter di input password verifikasi
            inputVerifyPassword?.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    executeVerify();
                }
            });
        });
    </script>

</body>
</html>
