<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard &mdash; {{ config('app.name', 'Aplikasi Bug Tracker') }}</title>

    {{-- Asset CSS & JS Terkompilasi via Vite & Optimasi Navigasi Instan --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.speed-meta')
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased font-sans flex flex-col lg:flex-row">

    {{-- Backdrop Sidebar untuk Layar Tablet/Mobile --}}
    <div
        id="sidebar-backdrop"
        class="fixed inset-0 bg-slate-900/50 backdrop-blur-xs z-30 lg:hidden hidden transition-opacity duration-200"
        aria-hidden="true"
    ></div>

    {{-- Navigasi Sidebar di Sebelah Kiri (Bersih & Tegas) --}}
    <aside
        id="sidebar"
        class="fixed inset-y-0 left-0 z-40 w-64 bg-slate-900 text-white transform -translate-x-full transition-transform duration-200 ease-in-out lg:sticky lg:top-0 lg:h-screen lg:translate-x-0 flex flex-col shrink-0 border-r border-slate-800 shadow-xl lg:shadow-none"
        aria-label="Sidebar Utama"
    >
        <div class="flex flex-col h-full">
            {{-- Brand Header Sidebar --}}
            <div class="h-16 flex items-center justify-between px-6 border-b border-slate-800 shrink-0 bg-slate-950/40">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-lg bg-indigo-600 flex items-center justify-center text-white font-bold text-sm shadow-xs">
                        BT
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

                    {{-- Fitur Dashboard (Active State) --}}
                    <a
                        href="{{ route('dashboard') }}"
                        class="flex items-center gap-3 rounded-lg px-3.5 py-2.5 text-sm font-semibold transition bg-slate-800 text-white border border-slate-700/70 shadow-xs"
                    >
                        <svg class="w-5 h-5 text-indigo-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12l8.954-8.955c.44-.439 1.152-.439 1.591 0L21.75 12M4.5 9.75v10.125c0 .621.504 1.125 1.125 1.125H9.75v-4.875c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125V21h4.125c.621 0 1.125-.504 1.125-1.125V9.75M8.25 21h8.25"/>
                        </svg>
                        <span>Dashboard</span>
                    </a>

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

                    {{-- Fitur Riwayat Rilis & Repo Modul --}}
                    <a
                        href="{{ route('releases.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3.5 py-2.5 text-sm font-medium transition text-slate-300 hover:bg-slate-800 hover:text-white"
                    >
                        <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.386a10.96 10.96 0 003.882-3.882c.486-.827.313-1.908-.386-2.607L9.762 3.659A2.25 2.25 0 009.568 3z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/>
                        </svg>
                        <span>Riwayat Rilis</span>
                    </a>

                    {{-- Fitur Evaluasi Aplikasi --}}
                    <a
                        href="{{ route('evaluations.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3.5 py-2.5 text-sm font-medium transition text-slate-300 hover:bg-slate-800 hover:text-white"
                    >
                        <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <span>Evaluasi Aplikasi</span>
                    </a>

                    {{-- Fitur Laporan Periodik --}}
                    <a
                        href="{{ route('reports.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3.5 py-2.5 text-sm font-medium transition text-slate-300 hover:bg-slate-800 hover:text-white"
                    >
                        <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                        </svg>
                        <span>Laporan Periodik</span>
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
                        {{-- Fitur Master Akun (Khusus Administrator) --}}
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

            </nav>

            {{-- Profil Pengguna di Bawah Sidebar (Klik untuk Masuk ke Akun Saya) --}}
            <div class="p-3.5 border-t border-slate-800 bg-slate-950/40">
                <a href="{{ route('account.edit') }}" class="flex items-center gap-3 px-2 py-1.5 rounded-lg hover:bg-slate-800/60 transition group" title="Buka Akun Saya">
                    @if ($user->foto_profil_url)
                        <img src="{{ $user->foto_profil_url }}" alt="{{ $user->name }}" class="h-8 w-8 rounded-full object-cover border border-slate-700 shrink-0 shadow-xs">
                    @else
                        <div class="h-8 w-8 rounded-full flex items-center justify-center font-bold text-xs text-white shrink-0 shadow-xs" style="background-color: {{ $user->avatar_bg ?? '#4F46E5' }};">
                            {{ $user->avatar ?? strtoupper(substr($user->name, 0, 2)) }}
                        </div>
                    @endif
                    <div class="min-w-0 flex-1">
                        <span class="block text-xs font-semibold text-white truncate group-hover:text-indigo-300 transition">{{ $user->name }}</span>
                        <span class="block text-[11px] text-slate-400 capitalize truncate">{{ $user->role }}</span>
                    </div>
                    <span class="sr-only">Pengaturan Akun Saya</span>
                </a>
            </div>
        </div>
    </aside>

    {{-- Area Konten Utama (Kanan) --}}
    <div class="main-content-wrapper flex-1 min-w-0 flex flex-col min-h-screen">

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

        {{-- Konten Halaman Dashboard --}}
        <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto flex flex-col">

            {{-- Header Halaman Dashboard --}}
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">Dashboard</h1>
                    <p class="mt-1 text-sm text-slate-600">
                        Selamat datang kembali, <span class="font-semibold text-slate-800">{{ $user->name }}</span>
                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold {{ $user->isAdmin() ? 'bg-indigo-50 text-indigo-700 border border-indigo-200' : 'bg-slate-100 text-slate-700 border border-slate-200' }} ml-1">
                            {{ ucfirst($user->role) }}
                        </span>.
                        Pantau progres pengerjaan proyek dan temuan isu secara terpadu.
                    </p>
                </div>

                <div class="flex items-center gap-2.5 shrink-0">
                    {{-- Badge Tanggal Hari Ini --}}
                    <div class="hidden sm:inline-flex items-center gap-1.5 px-3 py-2 rounded-lg border border-slate-200 bg-white text-xs font-semibold text-slate-600 shadow-2xs">
                        <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5"/>
                        </svg>
                        <span>{{ now()->translatedFormat('l, d F Y') }}</span>
                    </div>

                    {{-- Profil Pengguna Aktif (Desktop Header) --}}
                    <a href="{{ route('account.edit') }}" class="hidden sm:inline-flex items-center gap-2.5 px-3 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-50 transition shadow-2xs group" title="Pengaturan Akun Saya">
                        @if ($user->foto_profil_url)
                            <img src="{{ $user->foto_profil_url }}" alt="{{ $user->name }}" class="w-7 h-7 rounded-full object-cover border border-slate-200 shrink-0 shadow-2xs">
                        @else
                            <div class="w-7 h-7 rounded-full flex items-center justify-center text-xs font-bold text-white shrink-0 shadow-2xs" style="background-color: {{ $user->avatar_bg ?? '#1E293B' }}">
                                {{ $user->avatar ?? 'AK' }}
                            </div>
                        @endif
                        <div class="text-left">
                            <span class="block text-xs font-bold text-slate-800 group-hover:text-indigo-600 transition truncate max-w-[130px]">{{ $user->name }}</span>
                            <span class="block text-[10px] text-slate-400 capitalize">{{ $user->role }}</span>
                        </div>
                    </a>
                </div>
            </div>

            {{-- Ringkasan 4 Metrik Cepat Utama (Bersih & Tegas, Ramah Desktop & Tablet) --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                {{-- 1. Proyek Aktif --}}
                <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-2xs flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Proyek Aktif</span>
                        <div class="w-7 h-7 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="block text-2xl sm:text-3xl font-extrabold text-slate-900">{{ $metrics['active_projects'] }}</span>
                        <span class="text-xs text-slate-500 mt-0.5 block">dari {{ $metrics['total_projects'] }} total proyek terdaftar</span>
                    </div>
                    <div class="mt-3 pt-2.5 border-t border-slate-100">
                        <a href="{{ route('projects.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition">
                            Kelola Proyek
                        </a>
                    </div>
                </div>

                {{-- 2. Tugas yang Telah Diselesaikan --}}
                <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-2xs flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Tugas Telah Diselesaikan</span>
                        <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="block text-2xl sm:text-3xl font-extrabold text-slate-900">{{ $metrics['tasks_done'] }}</span>
                        <span class="text-xs text-slate-500 mt-0.5 block">{{ $metrics['tasks_in_progress'] }} Sedang Berjalan &bull; {{ $metrics['tasks_todo'] }} To Do</span>
                    </div>
                    <div class="mt-3 pt-2.5 border-t border-slate-100">
                        <a href="{{ route('kanban.index') }}" class="text-xs font-semibold text-emerald-600 hover:text-emerald-800 transition">
                            Buka Papan Kanban
                        </a>
                    </div>
                </div>

                {{-- 3. Akun Aktif & Nonaktif (Klik untuk Melihat Daftar Akun) --}}
                <div id="card-active-accounts" class="bg-white border border-slate-200 hover:border-sky-300 rounded-xl p-4 shadow-2xs flex flex-col justify-between transition group cursor-pointer" title="Klik untuk melihat daftar akun yang aktif maupun nonaktif">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-400 group-hover:text-sky-600 uppercase tracking-wider transition-colors">Akun Aktif</span>
                        <div class="w-7 h-7 rounded-lg bg-sky-50 text-sky-600 group-hover:bg-sky-100 flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="block text-2xl sm:text-3xl font-extrabold text-slate-900 group-hover:text-sky-600 transition-colors">{{ $metrics['active_accounts_count'] }}</span>
                        <span class="text-xs text-slate-500 mt-0.5 block">
                            {{ $metrics['active_developers_count'] }} Dev &bull; {{ $metrics['active_admins_count'] }} Admin &bull; {{ $metrics['inactive_accounts_count'] }} Nonaktif
                        </span>
                    </div>
                    <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between">
                        <button type="button" id="btn-open-active-accounts" class="text-xs font-semibold text-sky-600 group-hover:text-sky-800 transition inline-flex items-center gap-1 cursor-pointer">
                            <span>Lihat Akun Aktif &amp; Nonaktif</span>
                            <svg class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- 4. Jumlah Bug & Ancaman Bug --}}
                <div class="bg-white border border-slate-200 rounded-xl p-4 shadow-2xs flex flex-col justify-between">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-bold text-slate-400 uppercase tracking-wider">Jumlah Bug</span>
                        <div class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-3">
                        <span class="block text-2xl sm:text-3xl font-extrabold text-slate-900">{{ $metrics['total_bugs'] }}</span>
                        <span class="text-xs mt-0.5 block truncate" title="Ancaman Bug: {{ $metrics['critical_bugs'] }} Kritis, {{ $metrics['high_bugs'] }} Tinggi, {{ $metrics['medium_bugs'] }} Sedang">
                            <span class="font-bold text-rose-600">{{ $metrics['critical_bugs'] }} Kritis</span> &bull;
                            <span class="font-semibold text-amber-600">{{ $metrics['high_bugs'] }} Tinggi</span> &bull;
                            <span class="text-slate-500">{{ $metrics['medium_bugs'] }} Sedang</span>
                        </span>
                    </div>
                    <div class="mt-3 pt-2.5 border-t border-slate-100">
                        <a href="{{ route('bugs.index') }}" class="text-xs font-semibold text-rose-600 hover:text-rose-800 transition">
                            Lihat Detail Bug
                        </a>
                    </div>
                </div>
            </div>

            {{-- Banner Kendala Teknis Tugas (Blocker Alert - Jika Ada) --}}
            @if ($blockerTasks->count() > 0)
                <div class="mb-6 rounded-xl border border-amber-300 bg-amber-50/80 p-4 shadow-2xs">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-amber-200/80 text-amber-800 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="text-sm font-bold text-amber-900">Perhatian: Terdapat {{ $blockerTasks->count() }} Tugas Mengalami Kendala (Blocker)</h3>
                            <div class="mt-1.5 space-y-1">
                                @foreach ($blockerTasks as $bt)
                                    <div class="text-xs text-amber-800 flex flex-wrap items-center gap-1.5">
                                        <span class="font-bold text-amber-950">[{{ $bt->project?->code ?? 'PROYEK' }}]</span>
                                        <span class="font-semibold">{{ $bt->title }}:</span>
                                        <span class="italic text-amber-900">"{{ $bt->blocker_notes }}"</span>
                                        <span class="text-amber-700 font-medium">({{ $bt->assignee?->name ?? 'Belum Ditugaskan' }})</span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <a
                            href="{{ route('kanban.index') }}"
                            class="shrink-0 px-3 py-1.5 rounded-lg bg-amber-200/90 text-xs font-bold text-amber-900 hover:bg-amber-300 transition"
                        >
                            Tinjau di Kanban
                        </a>
                    </div>
                </div>
            @endif

            {{-- Dua Kolom Konten Utama (Grid Tanpa Tab Bersarang) --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">

                {{-- Kolom Kiri: Tugas Terkini & Navigasi Cepat (7 Kolom Desktop) --}}
                <div class="lg:col-span-7 space-y-6">

                    {{-- Card: Tugas Terkini Dalam Pengerjaan --}}
                    <div class="bg-white border border-slate-200 rounded-xl shadow-2xs overflow-hidden">
                        <div class="px-5 py-3.5 border-b border-slate-200 flex items-center justify-between bg-slate-50/50">
                            <div class="flex items-center gap-2">
                                <div class="w-2.5 h-2.5 rounded-full bg-sky-500"></div>
                                <h2 class="text-sm font-bold text-slate-900">Aktivitas Tugas Terkini</h2>
                                <span class="px-2 py-0.2 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                    {{ $metrics['tasks_in_progress'] + $metrics['tasks_todo'] }} Aktif
                                </span>
                            </div>
                            <a href="{{ route('kanban.index') }}" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800 transition">
                                Buka Papan Kanban
                            </a>
                        </div>

                        <div class="divide-y divide-slate-100">
                            @forelse ($recentTasks as $task)
                                @php
                                    $statusStyles = [
                                        'todo' => 'bg-slate-100 text-slate-700 border-slate-200',
                                        'in_progress' => 'bg-sky-50 text-sky-700 border-sky-200',
                                        'review' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'done' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                    ];
                                    $statusNames = [
                                        'todo' => 'To Do',
                                        'in_progress' => 'In Progress',
                                        'review' => 'Review',
                                        'done' => 'Done',
                                    ];
                                    $priorityStyles = [
                                        'urgent' => 'text-rose-600 bg-rose-50 border-rose-200',
                                        'high' => 'text-amber-700 bg-amber-50 border-amber-200',
                                        'medium' => 'text-slate-600 bg-slate-50 border-slate-200',
                                        'low' => 'text-slate-500 bg-slate-50 border-slate-200',
                                    ];
                                @endphp
                                <div class="p-4 hover:bg-slate-50/70 transition flex items-start justify-between gap-3">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center gap-2 mb-1">
                                            <span class="px-1.5 py-0.2 rounded text-[10px] font-bold bg-slate-100 text-slate-700 border border-slate-200 shrink-0">
                                                {{ $task->project?->code ?? 'PROYEK' }}
                                            </span>
                                            @if ($task->task_type === 'bug')
                                                <span class="px-1.5 py-0.2 rounded text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200 shrink-0">
                                                    Bug
                                                </span>
                                            @endif
                                            <h3 class="text-xs sm:text-sm font-semibold text-slate-900 truncate">
                                                {{ $task->title }}
                                            </h3>
                                        </div>

                                        <div class="flex flex-wrap items-center gap-2 mt-1.5 text-[11px] text-slate-500">
                                            {{-- Assignee --}}
                                            <span class="inline-flex items-center gap-1 font-medium text-slate-600">
                                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                                </svg>
                                                <span>{{ $task->assignee?->name ?? 'Belum Ditugaskan' }}</span>
                                            </span>

                                            <span>&bull;</span>

                                            {{-- Waktu Update --}}
                                            <span>Diperbarui {{ $task->updated_at ? $task->updated_at->diffForHumans() : '-' }}</span>

                                            @if ($task->blocker_notes && $task->status !== 'done')
                                                <span>&bull;</span>
                                                <span class="text-rose-600 font-bold inline-flex items-center gap-0.5">
                                                    Kendala: {{ Str::limit($task->blocker_notes, 30) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-1.5 shrink-0">
                                        {{-- Priority --}}
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold border uppercase tracking-wider {{ $priorityStyles[$task->priority] ?? 'text-slate-600 bg-slate-50 border-slate-200' }}">
                                            {{ $task->priority ?? 'medium' }}
                                        </span>

                                        {{-- Status --}}
                                        <span class="px-2 py-0.5 rounded-full text-[11px] font-bold border {{ $statusStyles[$task->status] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}">
                                            {{ $statusNames[$task->status] ?? ucfirst($task->status) }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="p-8 text-center text-slate-400">
                                    <p class="text-xs font-semibold">Tidak ada tugas aktif saat ini.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Card: Pintasan Navigasi Cepat (Quick Actions) --}}
                    <div class="bg-white border border-slate-200 rounded-xl p-5 shadow-2xs">
                        <h2 class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-3">Pintasan Cepat Operasional</h2>

                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                            {{-- Pintasan Papan Kanban --}}
                            <a
                                href="{{ route('kanban.index') }}"
                                class="flex flex-col items-center justify-center p-3 rounded-lg border border-slate-200 hover:border-slate-300 hover:bg-slate-50/80 transition text-center group"
                            >
                                <div class="w-9 h-9 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center mb-2 group-hover:scale-105 transition-transform">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 4.5v15m6-15v15m-10.875 0h15.75c.621 0 1.125-.504 1.125-1.125V5.625c0-.621-.504-1.125-1.125-1.125H4.125C3.504 4.5 3 5.004 3 5.625v12.75c0 .621.504 1.125 1.125 1.125z"/>
                                    </svg>
                                </div>
                                <span class="text-xs font-bold text-slate-800">Papan Kanban</span>
                                <span class="text-[10px] text-slate-500 mt-0.5">Alur Tugas 4 Kolom</span>
                            </a>

                            {{-- Pintasan Daftar Tugas --}}
                            <a
                                href="{{ route('tasks.index') }}"
                                class="flex flex-col items-center justify-center p-3 rounded-lg border border-slate-200 hover:border-slate-300 hover:bg-slate-50/80 transition text-center group"
                            >
                                <div class="w-9 h-9 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center mb-2 group-hover:scale-105 transition-transform">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 17.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                                    </svg>
                                </div>
                                <span class="text-xs font-bold text-slate-800">Daftar Tugas</span>
                                <span class="text-[10px] text-slate-500 mt-0.5">Tabel & Penugasan</span>
                            </a>

                            {{-- Pintasan Pelacak Bug --}}
                            <a
                                href="{{ route('bugs.index') }}"
                                class="flex flex-col items-center justify-center p-3 rounded-lg border border-slate-200 hover:border-slate-300 hover:bg-slate-50/80 transition text-center group"
                            >
                                <div class="w-9 h-9 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center mb-2 group-hover:scale-105 transition-transform">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.002A11.959 11.959 0 0112 2.714zm0 13.036a.75.75 0 100-1.5.75.75 0 000 1.5z" />
                                    </svg>
                                </div>
                                <span class="text-xs font-bold text-slate-800">Pelacak Bug</span>
                                <span class="text-[10px] text-slate-500 mt-0.5">Solusi & Akar Masalah</span>
                            </a>

                            {{-- Pintasan Laporan Periodik --}}
                            <a
                                href="{{ route('reports.index') }}"
                                class="flex flex-col items-center justify-center p-3 rounded-lg border border-slate-200 hover:border-slate-300 hover:bg-slate-50/80 transition text-center group"
                            >
                                <div class="w-9 h-9 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center mb-2 group-hover:scale-105 transition-transform">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                                    </svg>
                                </div>
                                <span class="text-xs font-bold text-slate-800">Laporan</span>
                                <span class="text-[10px] text-slate-500 mt-0.5">Rekapitulasi & PDF</span>
                            </a>
                        </div>
                    </div>

                </div>

                {{-- Kolom Kanan: Bug Mendesak & Rilis/Evaluasi (5 Kolom Desktop) --}}
                <div class="lg:col-span-5 space-y-6">

                    {{-- Card: Temuan Bug Mendesak --}}
                    <div class="bg-white border border-slate-200 rounded-xl shadow-2xs overflow-hidden">
                        <div class="px-5 py-3.5 border-b border-slate-200 flex items-center justify-between bg-slate-50/50">
                            <div class="flex items-center gap-2">
                                <div class="w-2.5 h-2.5 rounded-full bg-rose-500"></div>
                                <h2 class="text-sm font-bold text-slate-900">Temuan Bug Mendesak</h2>
                                <span class="px-2 py-0.2 rounded-full text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                    {{ $metrics['open_bugs'] }} Terbuka
                                </span>
                            </div>
                            <a href="{{ route('bugs.index') }}" class="text-xs font-semibold text-rose-600 hover:text-rose-800 transition">
                                Pelacak Bug
                            </a>
                        </div>

                        <div class="divide-y divide-slate-100">
                            @forelse ($urgentBugs as $bug)
                                @php
                                    $severityBadge = [
                                        'critical' => 'bg-rose-100 text-rose-800 border-rose-300 font-bold',
                                        'high' => 'bg-rose-50 text-rose-700 border-rose-200 font-semibold',
                                        'medium' => 'bg-amber-50 text-amber-700 border-amber-200 font-medium',
                                        'low' => 'bg-slate-100 text-slate-600 border-slate-200 font-medium',
                                    ];
                                @endphp
                                <div class="p-4 hover:bg-slate-50/70 transition">
                                    <div class="flex items-start justify-between gap-2 mb-1">
                                        <h3 class="text-xs font-bold text-slate-900 leading-snug">
                                            {{ $bug->title }}
                                        </h3>
                                        <span class="px-1.5 py-0.5 rounded text-[10px] uppercase border shrink-0 {{ $severityBadge[$bug->severity] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}">
                                            {{ $bug->severity }}
                                        </span>
                                    </div>

                                    <div class="flex items-center gap-2 text-[11px] text-slate-500 mt-2">
                                        <span class="font-medium text-slate-600">Modul: {{ $bug->module?->name ?? 'Sistem' }}</span>
                                        <span>&bull;</span>
                                        <span>{{ $bug->project?->code ?? 'BUG' }}</span>
                                        <span>&bull;</span>
                                        <span>{{ $bug->created_at ? $bug->created_at->diffForHumans() : '-' }}</span>
                                    </div>
                                </div>
                            @empty
                                <div class="p-6 text-center text-slate-400">
                                    <p class="text-xs font-semibold">Tidak ada temuan bug terbuka saat ini.</p>
                                    <p class="text-[11px] text-slate-400 mt-0.5">Semua sistem stabil dan aman.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Card: Ringkasan Rilis & Tindak Lanjut Evaluasi --}}
                    <div class="bg-white border border-slate-200 rounded-xl shadow-2xs overflow-hidden">
                        <div class="px-5 py-3.5 border-b border-slate-200 flex items-center justify-between bg-slate-50/50">
                            <h2 class="text-sm font-bold text-slate-900">Rilis & Evaluasi Sistem</h2>
                            <a href="{{ route('evaluations.index') }}" class="text-xs font-semibold text-slate-600 hover:text-slate-900 transition">
                                Evaluasi
                            </a>
                        </div>

                        <div class="p-4 space-y-4">
                            {{-- Info Rilis Terkini --}}
                            <div class="p-3 rounded-lg border border-slate-100 bg-slate-50/70">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs font-bold text-slate-800">Publikasi Rilis Terakhir</span>
                                    <a href="{{ route('releases.index') }}" class="text-[11px] font-semibold text-emerald-600 hover:text-emerald-800">
                                        Lihat Rilis
                                    </a>
                                </div>
                                @if ($latestRelease)
                                    <div class="flex items-center gap-2 text-xs text-slate-700 mt-1">
                                        <span class="px-2 py-0.5 rounded bg-emerald-50 text-emerald-700 border border-emerald-200 font-bold">
                                            {{ $latestRelease->release_tags ?? 'v1.0' }}
                                        </span>
                                        <span class="font-medium text-slate-600">[{{ $latestRelease->project?->code }}] {{ $latestRelease->project?->name }}</span>
                                    </div>
                                    <p class="text-[11px] text-slate-500 mt-1 line-clamp-1">
                                        {{ $latestRelease->description ?? 'Tidak ada catatan rilis.' }}
                                    </p>
                                @else
                                    <p class="text-xs text-slate-400 mt-1">Belum ada riwayat rilis repositori yang tercatat.</p>
                                @endif
                            </div>

                            {{-- Action Items Evaluasi Terkini --}}
                            <div>
                                <span class="text-xs font-bold text-slate-700 block mb-2">Tindak Lanjut Perbaikan Aktif</span>
                                <div class="space-y-2">
                                    @forelse ($activeActionItems as $action)
                                        <div class="p-2.5 rounded-lg border border-slate-100 bg-white shadow-2xs text-xs">
                                            <div class="flex items-start justify-between gap-2">
                                                <span class="font-semibold text-slate-800 line-clamp-1">{{ $action->action_plan }}</span>
                                                <span class="px-1.5 py-0.2 rounded text-[10px] font-bold shrink-0 {{ $action->status === 'converted_to_task' ? 'bg-sky-50 text-sky-700 border border-sky-200' : 'bg-amber-50 text-amber-700 border border-amber-200' }}">
                                                    {{ $action->status === 'converted_to_task' ? 'Di Tugas' : 'Direncanakan' }}
                                                </span>
                                            </div>
                                            <div class="flex items-center gap-2 text-[10px] text-slate-400 mt-1">
                                                <span>PIC: {{ $action->assignee?->name ?? 'Tim Dev' }}</span>
                                                <span>&bull;</span>
                                                <span>Target: {{ $action->target_date ? $action->target_date->format('d M Y') : 'Segera' }}</span>
                                            </div>
                                        </div>
                                    @empty
                                        <p class="text-xs text-slate-400 italic">Tidak ada rencana tindak lanjut evaluasi yang aktif.</p>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

            </div>

        </main>
    </div>

    {{-- Modal Pop-up: Daftar Akun Pengguna (Aktif & Nonaktif) --}}
    <div
        id="modal-active-accounts"
        class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs hidden items-center justify-center p-4 overflow-y-auto"
        role="dialog"
        aria-modal="true"
        aria-labelledby="modal-active-accounts-title"
    >
        <div class="bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-lg overflow-hidden flex flex-col max-h-[90vh] my-auto animate-in fade-in zoom-in-95 duration-150">
            {{-- Header Modal --}}
            <div class="px-5 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/70">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl bg-sky-100 text-sky-700 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                        </svg>
                    </div>
                    <div>
                        <h3 id="modal-active-accounts-title" class="text-base font-bold text-slate-900 leading-tight">Daftar Akun Pengguna</h3>
                        <p class="text-xs text-slate-500 mt-0.5">
                            Menampilkan seluruh anggota tim (aktif maupun nonaktif) dalam sistem
                        </p>
                    </div>
                </div>
                <button
                    type="button"
                    id="btn-close-active-accounts-x"
                    class="w-8 h-8 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-200/60 transition flex items-center justify-center cursor-pointer"
                    aria-label="Tutup modal"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

            {{-- Filter Status Tabs & Kolom Pencarian --}}
            <div class="p-4 border-b border-slate-100 bg-white space-y-3">
                {{-- Tabs Status: Semua, Aktif, Nonaktif --}}
                <div class="flex items-center gap-1.5 p-1 bg-slate-100/80 rounded-xl text-xs font-medium" id="status-filter-tabs">
                    <button
                        type="button"
                        data-status-filter="all"
                        class="tab-status-btn flex-1 py-1.5 px-3 rounded-lg text-center transition cursor-pointer font-bold bg-white text-slate-900 shadow-2xs"
                    >
                        Semua ({{ $metrics['total_accounts_count'] }})
                    </button>
                    <button
                        type="button"
                        data-status-filter="active"
                        class="tab-status-btn flex-1 py-1.5 px-3 rounded-lg text-center transition cursor-pointer text-slate-600 hover:text-slate-900 hover:bg-slate-200/60"
                    >
                        Aktif ({{ $metrics['active_accounts_count'] }})
                    </button>
                    <button
                        type="button"
                        data-status-filter="inactive"
                        class="tab-status-btn flex-1 py-1.5 px-3 rounded-lg text-center transition cursor-pointer text-slate-600 hover:text-slate-900 hover:bg-slate-200/60"
                    >
                        Nonaktif ({{ $metrics['inactive_accounts_count'] }})
                    </button>
                </div>

                {{-- Kolom Pencarian --}}
                <div class="relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                    </span>
                    <input
                        type="text"
                        id="search-active-accounts"
                        placeholder="Cari nama, email, atau peran..."
                        class="w-full pl-9 pr-4 py-2 rounded-xl text-xs bg-slate-50 border border-slate-200 focus:bg-white focus:outline-none focus:ring-2 focus:ring-sky-500 focus:border-transparent transition"
                        autocomplete="off"
                    >
                </div>
            </div>

            {{-- Daftar Akun (Scrollable) --}}
            <div id="list-active-accounts" class="overflow-y-auto divide-y divide-slate-100 p-2 sm:p-3 max-h-96">
                @forelse ($activeAccounts as $acc)
                    @php
                        $words = explode(' ', trim($acc->name));
                        $initials = strtoupper(substr($words[0] ?? 'U', 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));
                        $isAdmin = $acc->role === 'admin';
                        $isActive = (bool) $acc->is_active;
                    @endphp
                    <div
                        class="active-account-item p-3 rounded-xl hover:bg-slate-50 transition flex items-center justify-between gap-3 {{ $isActive ? '' : 'bg-slate-50/60 opacity-90' }}"
                        data-account-name="{{ strtolower($acc->name) }}"
                        data-account-email="{{ strtolower($acc->email) }}"
                        data-account-role="{{ strtolower($acc->role) }}"
                        data-account-status="{{ $isActive ? 'active' : 'inactive' }}"
                    >
                        <div class="flex items-center gap-3 min-w-0">
                            {{-- Avatar / Foto Profil dengan Indikator Online/Offline --}}
                            <div class="relative shrink-0">
                                @if ($acc->foto_profil_url)
                                    <img src="{{ $acc->foto_profil_url }}" alt="{{ $acc->name }}" class="w-10 h-10 rounded-full object-cover border border-slate-200 shadow-2xs">
                                @else
                                    <div
                                        class="w-10 h-10 rounded-full {{ $isActive ? ($isAdmin ? 'bg-purple-100 text-purple-700' : 'bg-sky-100 text-sky-700') : 'bg-slate-200 text-slate-500' }} flex items-center justify-center font-bold text-xs shadow-2xs"
                                        style="{{ $acc->avatar_bg && $isActive ? 'background-color: ' . $acc->avatar_bg . '; color: white;' : '' }}"
                                    >
                                        {{ $acc->avatar ?? $initials }}
                                    </div>
                                @endif
                                @if ($isActive)
                                    <span class="absolute bottom-0 right-0 w-2.5 h-2.5 rounded-full bg-emerald-500 border-2 border-white ring-1 ring-emerald-200" title="Akun Aktif"></span>
                                @else
                                    <span class="absolute bottom-0 right-0 w-2.5 h-2.5 rounded-full bg-slate-400 border-2 border-white ring-1 ring-slate-300" title="Akun Nonaktif"></span>
                                @endif
                            </div>

                            <div class="min-w-0">
                                <div class="flex items-center gap-2">
                                    <h4 class="text-xs sm:text-sm font-bold text-slate-800 truncate">{{ $acc->name }}</h4>
                                    @if (auth()->id() === $acc->id)
                                        <span class="px-1.5 py-0.2 rounded text-[10px] font-bold bg-slate-200 text-slate-700 shrink-0">Anda</span>
                                    @endif
                                </div>
                                <p class="text-xs text-slate-500 truncate">{{ $acc->email }}</p>
                            </div>
                        </div>

                        <div class="flex items-center gap-2 shrink-0">
                            {{-- Badge Role --}}
                            <span class="px-2 py-0.5 rounded-full text-[11px] font-bold border capitalize {{ $isAdmin ? 'bg-purple-50 text-purple-700 border-purple-200' : 'bg-sky-50 text-sky-700 border-sky-200' }}">
                                {{ $acc->role }}
                            </span>
                            {{-- Badge Status Aktif / Nonaktif --}}
                            @if ($isActive)
                                <span class="hidden sm:inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Aktif
                                </span>
                            @else
                                <span class="hidden sm:inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-bold bg-slate-100 text-slate-600 border border-slate-300">
                                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                    Nonaktif
                                </span>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center text-slate-400">
                        <p class="text-xs font-semibold">Tidak ada akun yang terdaftar saat ini.</p>
                    </div>
                @endforelse

                {{-- Empty Search Results State --}}
                <div id="empty-search-active-accounts" class="p-8 text-center text-slate-400 hidden">
                    <svg class="w-8 h-8 mx-auto text-slate-300 mb-2" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                    </svg>
                    <p class="text-xs font-semibold">Tidak ada akun yang sesuai dengan filter atau kata kunci pencarian.</p>
                </div>
            </div>

            {{-- Footer Modal --}}
            <div class="px-5 py-3.5 border-t border-slate-100 bg-slate-50/70 flex items-center justify-between text-xs">
                <span class="text-slate-500 font-medium">
                    Total: <strong class="text-slate-800">{{ $metrics['total_accounts_count'] }}</strong> Akun
                    ({{ $metrics['active_accounts_count'] }} Aktif, {{ $metrics['inactive_accounts_count'] }} Nonaktif)
                </span>
                <button
                    type="button"
                    id="btn-close-active-accounts-bottom"
                    class="px-4 py-1.5 rounded-lg border border-slate-200 bg-white hover:bg-slate-100 font-semibold text-slate-700 transition cursor-pointer shadow-2xs"
                >
                    Tutup
                </button>
            </div>
        </div>
    </div>

    {{-- Script Navigasi Sidebar Responsif & Modal Akun Pengguna --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // 1. Sidebar Responsif
            const sidebar = document.getElementById('sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            const btnOpen = document.getElementById('btn-sidebar-open');
            const btnClose = document.getElementById('btn-sidebar-close');

            const openSidebar = () => {
                sidebar?.classList.remove('-translate-x-full');
                backdrop?.classList.remove('hidden');
            };

            const closeSidebar = () => {
                sidebar?.classList.add('-translate-x-full');
                backdrop?.classList.add('hidden');
            };

            btnOpen?.addEventListener('click', openSidebar);
            btnClose?.addEventListener('click', closeSidebar);
            backdrop?.addEventListener('click', closeSidebar);

            // 2. Modal Daftar Akun (Aktif & Nonaktif)
            const modalActiveAccounts = document.getElementById('modal-active-accounts');
            const cardActiveAccounts = document.getElementById('card-active-accounts');
            const btnOpenActiveAccounts = document.getElementById('btn-open-active-accounts');
            const btnCloseX = document.getElementById('btn-close-active-accounts-x');
            const btnCloseBottom = document.getElementById('btn-close-active-accounts-bottom');
            const searchInput = document.getElementById('search-active-accounts');
            const accountItems = document.querySelectorAll('.active-account-item');
            const emptySearchNotice = document.getElementById('empty-search-active-accounts');
            const statusFilterTabs = document.querySelectorAll('.tab-status-btn');

            let currentStatusFilter = 'all';

            const filterAccounts = () => {
                const q = (searchInput?.value || '').toLowerCase().trim();
                let visibleCount = 0;

                accountItems.forEach((item) => {
                    const name = item.getAttribute('data-account-name') || '';
                    const email = item.getAttribute('data-account-email') || '';
                    const role = item.getAttribute('data-account-role') || '';
                    const status = item.getAttribute('data-account-status') || '';

                    const matchesQuery = !q || name.includes(q) || email.includes(q) || role.includes(q);
                    const matchesStatus = currentStatusFilter === 'all' || status === currentStatusFilter;

                    if (matchesQuery && matchesStatus) {
                        item.classList.remove('hidden');
                        visibleCount++;
                    } else {
                        item.classList.add('hidden');
                    }
                });

                if (emptySearchNotice) {
                    if (visibleCount === 0 && accountItems.length > 0) {
                        emptySearchNotice.classList.remove('hidden');
                    } else {
                        emptySearchNotice.classList.add('hidden');
                    }
                }
            };

            const setStatusFilter = (filter) => {
                currentStatusFilter = filter;
                statusFilterTabs.forEach((tab) => {
                    const isActive = tab.getAttribute('data-status-filter') === filter;
                    if (isActive) {
                        tab.classList.add('bg-white', 'text-slate-900', 'font-bold', 'shadow-2xs');
                        tab.classList.remove('text-slate-600');
                    } else {
                        tab.classList.remove('bg-white', 'text-slate-900', 'font-bold', 'shadow-2xs');
                        tab.classList.add('text-slate-600');
                    }
                });
                filterAccounts();
            };

            statusFilterTabs.forEach((tab) => {
                tab.addEventListener('click', () => {
                    const filter = tab.getAttribute('data-status-filter') || 'all';
                    setStatusFilter(filter);
                });
            });

            const openActiveAccountsModal = () => {
                if (!modalActiveAccounts) return;
                modalActiveAccounts.classList.remove('hidden');
                modalActiveAccounts.classList.add('flex');
                document.body.classList.add('overflow-hidden');
                setStatusFilter('all');
                if (searchInput) {
                    searchInput.value = '';
                    filterAccounts();
                    setTimeout(() => searchInput.focus(), 50);
                }
            };

            const closeActiveAccountsModal = () => {
                if (!modalActiveAccounts) return;
                modalActiveAccounts.classList.add('hidden');
                modalActiveAccounts.classList.remove('flex');
                document.body.classList.remove('overflow-hidden');
            };

            cardActiveAccounts?.addEventListener('click', (e) => {
                openActiveAccountsModal();
            });

            btnOpenActiveAccounts?.addEventListener('click', (e) => {
                e.stopPropagation();
                openActiveAccountsModal();
            });

            btnCloseX?.addEventListener('click', closeActiveAccountsModal);
            btnCloseBottom?.addEventListener('click', closeActiveAccountsModal);

            modalActiveAccounts?.addEventListener('click', (e) => {
                if (e.target === modalActiveAccounts) {
                    closeActiveAccountsModal();
                }
            });

            searchInput?.addEventListener('input', () => {
                filterAccounts();
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    if (modalActiveAccounts && !modalActiveAccounts.classList.contains('hidden')) {
                        closeActiveAccountsModal();
                    } else if (sidebar && !sidebar.classList.contains('-translate-x-full')) {
                        closeSidebar();
                    }
                }
            });
        });
    </script>

</body>
</html>
