<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Evaluasi Aplikasi - {{ config('app.name', 'Aplikasi Bug Tracker') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
</head>
<body class="min-h-full flex flex-col font-sans antialiased text-slate-800 bg-slate-50">

<div class="min-h-screen flex flex-col lg:flex-row w-full">

    {{-- Backdrop Layar Mobile/Tablet saat Sidebar Terbuka --}}
    <div
        id="sidebar-backdrop"
        class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-xs transition-opacity duration-200 hidden lg:hidden"
        aria-hidden="true"
    ></div>

    {{-- Sidebar Navigasi Utama (Clean, Tegas, Tanpa Tab Bersarang & Tanpa Tombol Logout) --}}
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

                    {{-- Fitur Dashboard --}}
                    <a
                        href="{{ route('dashboard') }}"
                        class="flex items-center gap-3 rounded-lg px-3.5 py-2.5 text-sm font-medium transition text-slate-300 hover:bg-slate-800 hover:text-white"
                    >
                        <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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

                    {{-- Fitur Evaluasi Aplikasi (Active State) --}}
                    <a
                        href="{{ route('evaluations.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3.5 py-2.5 text-sm font-semibold transition bg-slate-800 text-white border border-slate-700/70 shadow-xs"
                    >
                        <svg class="w-5 h-5 text-indigo-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
                        {{-- Fitur Master Akun (Khusus Admin) --}}
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

                    {{-- Fitur Akun Saya --}}
                    <a
                        href="{{ route('account.edit') }}"
                        class="flex items-center gap-3 rounded-lg px-3.5 py-2.5 text-sm font-medium transition text-slate-300 hover:bg-slate-800 hover:text-white"
                    >
                        <svg class="w-5 h-5 text-slate-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                        </svg>
                        <span>Akun Saya</span>
                    </a>
                </div>
            </nav>

            {{-- Ringkasan Pengguna Aktif di Bawah Sidebar (Tanpa Tombol Logout) --}}
            <div class="p-3.5 border-t border-slate-800 bg-slate-950/40">
                <div class="flex items-center gap-3 px-2 py-1.5">
                    <div class="h-8 w-8 rounded-full flex items-center justify-center font-bold text-xs text-white shrink-0 shadow-xs" style="background-color: {{ $user->avatar_bg ?? '#4F46E5' }};">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                    <div class="min-w-0 flex-1">
                        <span class="block text-xs font-semibold text-white truncate">{{ $user->name }}</span>
                        <span class="block text-[11px] text-slate-400 capitalize truncate">{{ $user->role }}</span>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    {{-- Main Content Area --}}
    <div class="flex-1 flex flex-col min-w-0 bg-slate-50 min-h-screen">

        {{-- Top App Bar --}}
        <header class="bg-white border-b border-slate-200 sticky top-0 z-30">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16 gap-4">
                    {{-- Sisi Kiri: Tombol Hamburger Mobile/Tablet + Judul Halaman --}}
                    <div class="flex items-center gap-3 min-w-0">
                        <button
                            type="button"
                            id="btn-sidebar-open"
                            class="p-2 rounded-lg text-slate-500 hover:text-slate-900 hover:bg-slate-100 lg:hidden cursor-pointer shrink-0"
                            aria-label="Buka navigasi sidebar"
                        >
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                            </svg>
                        </button>

                        <div class="min-w-0">
                            <div class="flex items-center gap-2 flex-wrap">
                                <h1 class="text-lg sm:text-xl font-extrabold text-slate-900 tracking-tight truncate">
                                    Evaluasi Aplikasi
                                </h1>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200 shrink-0">
                                    Fitur Operasional
                                </span>
                            </div>
                            <p class="hidden sm:block text-xs text-slate-500 truncate">
                                Evaluasi performa berkala, kendala operasional, umpan balik pengguna, dan tindak lanjut perbaikan.
                            </p>
                        </div>
                    </div>

                    {{-- Sisi Kanan: Tombol Tambah Evaluasi --}}
                    <div class="flex items-center gap-2 shrink-0">
                        <button
                            type="button"
                            id="btn-open-create-evaluation"
                            class="inline-flex items-center gap-2 px-3.5 py-2 rounded-lg text-xs sm:text-sm font-semibold bg-indigo-600 text-white hover:bg-indigo-700 active:bg-indigo-800 transition shadow-sm cursor-pointer"
                        >
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                            </svg>
                            <span>Tambah Evaluasi</span>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        {{-- Isi Konten Halaman --}}
        <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

            {{-- Flash Alert Notifikasi --}}
            @if (session('status'))
                <div class="flex items-center justify-between p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 shadow-xs">
                    <div class="flex items-center gap-3">
                        <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-600 text-white shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                            </svg>
                        </div>
                        <p class="text-xs sm:text-sm font-semibold">{{ session('status') }}</p>
                    </div>
                    <button type="button" onclick="this.closest('div').remove()" class="text-emerald-700 hover:text-emerald-900 p-1 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            @endif

            @if ($errors->any())
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 shadow-xs">
                    <div class="flex items-center gap-2 mb-2 font-bold text-xs sm:text-sm text-rose-800">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                        </svg>
                        <span>Terjadi kesalahan validasi data evaluasi:</span>
                    </div>
                    <ul class="list-disc list-inside text-xs text-rose-700 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- 1. Kartu Metrik Ringkasan Evaluasi (Simple, Bersih & Tegas) --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                {{-- Metrik 1: Total Evaluasi --}}
                <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Evaluasi</span>
                        <div class="p-2 rounded-lg bg-indigo-50 text-indigo-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $metrics['total'] }}</span>
                        <span class="text-xs text-slate-500 font-medium">Periode</span>
                    </div>
                </div>

                {{-- Metrik 2: Proyek Dievaluasi --}}
                <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Proyek Dievaluasi</span>
                        <div class="p-2 rounded-lg bg-emerald-50 text-emerald-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $metrics['projects_evaluated'] }}</span>
                        <span class="text-xs text-slate-500 font-medium">Sistem</span>
                    </div>
                </div>

                {{-- Metrik 3: Rencana Tindak Lanjut (Action Items) --}}
                <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Tindak Lanjut</span>
                        <div class="p-2 rounded-lg bg-blue-50 text-blue-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 17.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $metrics['total_action_items'] }}</span>
                        <span class="text-xs text-slate-500 font-medium">Action Items</span>
                    </div>
                </div>

                {{-- Metrik 4: Isu Operasional Tercatat --}}
                <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-xs">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Isu Operasional</span>
                        <div class="p-2 rounded-lg bg-amber-50 text-amber-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $metrics['issues_recorded'] }}</span>
                        <span class="text-xs text-slate-500 font-medium">Catatan Kendala</span>
                    </div>
                </div>
            </div>

            {{-- 2. Filter & Pencarian (Responsive Desktop & Tablet) --}}
            <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-xs">
                <form method="GET" action="{{ route('evaluations.index') }}" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                    {{-- Input Pencarian --}}
                    <div class="relative flex-1">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                            </svg>
                        </div>
                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Cari periode, isu operasional, umpan balik, atau proyek..."
                            class="w-full pl-9 pr-3.5 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 transition"
                        />
                    </div>

                    {{-- Filter Dropdown Proyek --}}
                    <div class="w-full sm:w-56 shrink-0">
                        <select
                            name="project_id"
                            onchange="this.form.submit()"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs sm:text-sm text-slate-700 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 transition cursor-pointer"
                        >
                            <option value="">Semua Proyek</option>
                            @foreach ($projects as $proj)
                                <option value="{{ $proj->id }}" {{ (string) $projectIdFilter === (string) $proj->id ? 'selected' : '' }}>
                                    [{{ $proj->code }}] {{ $proj->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filter Dropdown Periode --}}
                    <div class="w-full sm:w-44 shrink-0">
                        <select
                            name="period"
                            onchange="this.form.submit()"
                            class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs sm:text-sm text-slate-700 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 transition cursor-pointer"
                        >
                            <option value="">Semua Periode</option>
                            @foreach ($periods as $prd)
                                <option value="{{ $prd }}" {{ (string) $periodFilter === (string) $prd ? 'selected' : '' }}>
                                    {{ $prd }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tombol Aksi Filter --}}
                    <div class="flex items-center gap-2">
                        <button
                            type="submit"
                            class="px-4 py-2 rounded-lg text-xs sm:text-sm font-semibold bg-slate-900 text-white hover:bg-slate-800 transition cursor-pointer"
                        >
                            Terapkan
                        </button>

                        @if ($search !== '' || $projectIdFilter !== '' || $periodFilter !== '')
                            <a
                                href="{{ route('evaluations.index') }}"
                                class="px-3 py-2 rounded-lg text-xs sm:text-sm font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-100 transition cursor-pointer"
                                title="Reset filter pencarian"
                            >
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- 3. Tabel Data Evaluasi Aplikasi (Tegas, Bersih & Tanpa Tab Bersarang) --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs">
                <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm sm:text-base font-bold text-slate-900">Daftar Evaluasi Kinerja Sistem</h2>
                        <p class="text-xs text-slate-500">Menampilkan {{ $evaluations->count() }} data evaluasi berkala</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50/75 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                <th scope="col" class="py-3.5 pl-5 pr-3">Periode & Tanggal</th>
                                <th scope="col" class="py-3.5 px-3">Proyek Induk</th>
                                <th scope="col" class="py-3.5 px-3">Kendala Operasional</th>
                                <th scope="col" class="py-3.5 px-3 hidden md:table-cell">Umpan Balik Pengguna</th>
                                <th scope="col" class="py-3.5 px-3 text-center">Tindak Lanjut</th>
                                <th scope="col" class="py-3.5 pl-3 pr-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 text-sm">
                            @forelse ($evaluations as $eval)
                                @php
                                    $evalData = [
                                        'id' => $eval->id,
                                        'project_id' => $eval->project_id,
                                        'project_name' => $eval->project ? $eval->project->name : '-',
                                        'project_code' => $eval->project ? $eval->project->code : '',
                                        'evaluation_date' => $eval->evaluation_date ? $eval->evaluation_date->format('Y-m-d') : '',
                                        'evaluation_date_formatted' => $eval->evaluation_date ? $eval->evaluation_date->isoFormat('D MMMM Y') : '-',
                                        'period_review' => $eval->period_review,
                                        'operational_issues' => $eval->operational_issues,
                                        'user_feedback' => $eval->user_feedback,
                                        'action_items' => $eval->actionItems->map(function ($item) {
                                            return [
                                                'id' => $item->id,
                                                'action_plan' => $item->action_plan,
                                                'status' => $item->status,
                                                'target_date' => $item->target_date ? $item->target_date->format('Y-m-d') : null,
                                                'target_date_formatted' => $item->target_date ? $item->target_date->isoFormat('D MMM Y') : '-',
                                                'assignee_name' => $item->assignee ? $item->assignee->name : 'Belum Ditugaskan',
                                            ];
                                        })->values(),
                                        'update_url' => route('evaluations.update', $eval->id),
                                        'delete_url' => route('evaluations.destroy', $eval->id),
                                        'action_item_store_url' => route('evaluations.action-items.store', $eval->id),
                                    ];
                                @endphp
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    {{-- 1. Periode & Tanggal Evaluasi --}}
                                    <td class="py-3.5 pl-5 pr-3 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                                                {{ $eval->period_review }}
                                            </span>
                                        </div>
                                        <div class="text-xs text-slate-500 mt-1 flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5"/>
                                            </svg>
                                            <span>{{ $eval->evaluation_date ? $eval->evaluation_date->isoFormat('D MMM Y') : '-' }}</span>
                                        </div>
                                    </td>

                                    {{-- 2. Proyek Induk --}}
                                    <td class="py-3.5 px-3">
                                        @if ($eval->project)
                                            <div class="flex items-center gap-2">
                                                <span
                                                    class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold text-white shrink-0 shadow-2xs"
                                                    style="background-color: {{ $eval->project->color ?? '#4F46E5' }};"
                                                >
                                                    [{{ $eval->project->code }}]
                                                </span>
                                                <span class="font-semibold text-slate-900 text-xs sm:text-sm">
                                                    {{ $eval->project->name }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-slate-400 italic text-xs">Tanpa Proyek</span>
                                        @endif
                                    </td>

                                    {{-- 3. Kendala Operasional --}}
                                    <td class="py-3.5 px-3 max-w-xs text-xs text-slate-600">
                                        @if ($eval->operational_issues)
                                            <div class="line-clamp-2" title="{{ $eval->operational_issues }}">
                                                <span class="inline-block w-2 h-2 rounded-full bg-amber-500 mr-1"></span>
                                                {{ $eval->operational_issues }}
                                            </div>
                                        @else
                                            <span class="text-slate-400 italic">Tidak ada kendala</span>
                                        @endif
                                    </td>

                                    {{-- 4. Umpan Balik Pengguna --}}
                                    <td class="py-3.5 px-3 hidden md:table-cell max-w-xs text-xs text-slate-600">
                                        @if ($eval->user_feedback)
                                            <div class="line-clamp-2" title="{{ $eval->user_feedback }}">
                                                {{ $eval->user_feedback }}
                                            </div>
                                        @else
                                            <span class="text-slate-400 italic">Tidak ada umpan balik</span>
                                        @endif
                                    </td>

                                    {{-- 5. Rencana Tindak Lanjut (Action Items) --}}
                                    <td class="py-3.5 px-3 text-center whitespace-nowrap">
                                        @php
                                            $totalItems = $eval->actionItems->count();
                                            $plannedCount = $eval->actionItems->where('status', 'planned')->count();
                                            $convertedCount = $eval->actionItems->where('status', 'converted_to_task')->count();
                                            $completedCount = $eval->actionItems->where('status', 'completed')->count();
                                        @endphp
                                        @if ($totalItems > 0)
                                            <button
                                                type="button"
                                                class="btn-detail-evaluation inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold bg-slate-100 hover:bg-indigo-50 hover:text-indigo-700 text-slate-700 border border-slate-200 transition cursor-pointer"
                                                data-evaluation='@json($evalData)'
                                                title="Lihat detail rencana tindak lanjut"
                                            >
                                                <svg class="w-3.5 h-3.5 text-indigo-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 17.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                                                </svg>
                                                <span>{{ $totalItems }} Tindak Lanjut</span>
                                            </button>
                                        @else
                                            <button
                                                type="button"
                                                class="btn-detail-evaluation inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-medium text-slate-400 bg-slate-50 border border-slate-200 hover:bg-slate-100 transition cursor-pointer"
                                                data-evaluation='@json($evalData)'
                                                title="Tambah rencana tindak lanjut"
                                            >
                                                <span>+ Tambah</span>
                                            </button>
                                        @endif
                                    </td>

                                    {{-- 6. Aksi (Detail, Edit, Hapus) --}}
                                    <td class="py-3.5 pl-3 pr-5 text-right whitespace-nowrap">
                                        <div class="inline-flex items-center gap-1">
                                            {{-- Tombol Detail --}}
                                            <button
                                                type="button"
                                                class="btn-detail-evaluation p-1.5 rounded-lg text-slate-500 hover:text-indigo-600 hover:bg-indigo-50 transition cursor-pointer"
                                                data-evaluation='@json($evalData)'
                                                title="Rincian & Tindak Lanjut"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                            </button>

                                            {{-- Tombol Edit --}}
                                            <button
                                                type="button"
                                                class="btn-edit-evaluation p-1.5 rounded-lg text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition cursor-pointer"
                                                data-evaluation='@json($evalData)'
                                                title="Edit Evaluasi"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                                </svg>
                                            </button>

                                            {{-- Tombol Hapus --}}
                                            <button
                                                type="button"
                                                class="btn-delete-evaluation p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                                data-evaluation='@json($evalData)'
                                                title="Hapus Evaluasi"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-12 text-center">
                                        <div class="max-w-xs mx-auto text-center">
                                            <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mx-auto mb-3">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                                </svg>
                                            </div>
                                            <p class="text-sm font-semibold text-slate-800">Tidak ada data evaluasi ditemukan</p>
                                            <p class="text-xs text-slate-500 mt-1">Sesuaikan filter pencarian atau buat evaluasi kinerja baru.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </main>
    </div>
</div>

{{-- MODAL 1: TAMBAH EVALUASI BARU (Single Screen, Clean & Tegas) --}}
<div
    id="modal-create-evaluation"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden"
    aria-modal="true"
    role="dialog"
>
    <div class="bg-white rounded-2xl shadow-xl border border-slate-200 w-full max-w-xl max-h-[90vh] flex flex-col overflow-hidden">
        {{-- Modal Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 shrink-0">
            <div>
                <h3 class="text-base font-bold text-slate-900">Tambah Evaluasi Aplikasi</h3>
                <p class="text-xs text-slate-500 mt-0.5">Catat hasil evaluasi berkala kinerja sistem dan rencana tindak lanjut.</p>
            </div>
            <button type="button" class="btn-close-modal p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Form Tambah Evaluasi --}}
        <form method="POST" action="{{ route('evaluations.store') }}" class="p-6 space-y-4 overflow-y-auto flex-1">
            @csrf

            {{-- Pilihan Proyek --}}
            <div>
                <label for="create-project-id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Proyek Induk <span class="text-rose-600">*</span>
                </label>
                <select
                    id="create-project-id"
                    name="project_id"
                    required
                    class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 cursor-pointer"
                >
                    <option value="">-- Pilih Proyek Terkait --</option>
                    @foreach ($projects as $proj)
                        <option value="{{ $proj->id }}">
                            [{{ $proj->code }}] {{ $proj->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Periode Review & Tanggal Evaluasi --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="create-period-review" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Periode Review <span class="text-rose-600">*</span>
                    </label>
                    <input
                        type="text"
                        id="create-period-review"
                        name="period_review"
                        required
                        maxlength="100"
                        placeholder="Contoh: Q4 2026 atau Sprint 15"
                        class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900"
                    />
                </div>

                <div>
                    <label for="create-evaluation-date" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Tanggal Evaluasi <span class="text-rose-600">*</span>
                    </label>
                    <input
                        type="date"
                        id="create-evaluation-date"
                        name="evaluation_date"
                        required
                        value="{{ date('Y-m-d') }}"
                        class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 cursor-pointer"
                    />
                </div>
            </div>

            {{-- Kendala Operasional --}}
            <div>
                <label for="create-operational-issues" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Kendala Operasional Pasca Rilis
                </label>
                <textarea
                    id="create-operational-issues"
                    name="operational_issues"
                    rows="3"
                    placeholder="Deskripsikan kendala operasional, timeout server, atau anomali sistem (opsional)..."
                    class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900"
                ></textarea>
            </div>

            {{-- Umpan Balik Pengguna --}}
            <div>
                <label for="create-user-feedback" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Umpan Balik Pengguna / Klien
                </label>
                <textarea
                    id="create-user-feedback"
                    name="user_feedback"
                    rows="3"
                    placeholder="Catatan kepuasan, masukan kemudahan penggunaan, atau permintaan dari pengguna (opsional)..."
                    class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900"
                ></textarea>
            </div>

            {{-- Rencana Tindak Lanjut Awal (Opsional) --}}
            <div class="pt-3 border-t border-slate-200">
                <span class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">
                    Rencana Tindak Lanjut Awal (Opsional)
                </span>
                <div class="space-y-3 p-3.5 bg-slate-50 rounded-xl border border-slate-200">
                    <div>
                        <input
                            type="text"
                            name="initial_action_plan"
                            placeholder="Rencana tindakan perbaikan (misal: Optimasi Redis cache untuk laporan)"
                            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900"
                        />
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <div>
                            <select
                                name="initial_assigned_to"
                                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 cursor-pointer"
                            >
                                <option value="">-- Tugaskan ke PIC Pengembang --</option>
                                @foreach ($developers as $dev)
                                    <option value="{{ $dev->id }}">{{ $dev->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <input
                                type="date"
                                name="initial_target_date"
                                placeholder="Target Tanggal"
                                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 cursor-pointer"
                            />
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tombol Submit Form --}}
            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-200">
                <button
                    type="button"
                    class="btn-close-modal px-4 py-2 rounded-lg text-xs sm:text-sm font-semibold text-slate-700 hover:bg-slate-100 transition cursor-pointer"
                >
                    Batal
                </button>
                <button
                    type="submit"
                    class="px-4 py-2 rounded-lg text-xs sm:text-sm font-semibold bg-indigo-600 text-white hover:bg-indigo-700 active:bg-indigo-800 transition shadow-sm cursor-pointer"
                >
                    Simpan Evaluasi
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL 2: EDIT EVALUASI (Single Screen, Clean & Tegas) --}}
<div
    id="modal-edit-evaluation"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden"
    aria-modal="true"
    role="dialog"
>
    <div class="bg-white rounded-2xl shadow-xl border border-slate-200 w-full max-w-xl max-h-[90vh] flex flex-col overflow-hidden">
        {{-- Modal Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 shrink-0">
            <div>
                <h3 class="text-base font-bold text-slate-900">Edit Evaluasi Aplikasi</h3>
                <p class="text-xs text-slate-500 mt-0.5">Perbarui rincian periode, kendala, atau umpan balik pengguna.</p>
            </div>
            <button type="button" class="btn-close-modal p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Form Edit Evaluasi --}}
        <form id="form-edit-evaluation" method="POST" action="" class="p-6 space-y-4 overflow-y-auto flex-1">
            @csrf
            @method('PUT')

            {{-- Pilihan Proyek --}}
            <div>
                <label for="edit-project-id" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Proyek Induk <span class="text-rose-600">*</span>
                </label>
                <select
                    id="edit-project-id"
                    name="project_id"
                    required
                    class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 cursor-pointer"
                >
                    <option value="">-- Pilih Proyek Terkait --</option>
                    @foreach ($projects as $proj)
                        <option value="{{ $proj->id }}">
                            [{{ $proj->code }}] {{ $proj->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Periode Review & Tanggal Evaluasi --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="edit-period-review" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Periode Review <span class="text-rose-600">*</span>
                    </label>
                    <input
                        type="text"
                        id="edit-period-review"
                        name="period_review"
                        required
                        maxlength="100"
                        class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900"
                    />
                </div>

                <div>
                    <label for="edit-evaluation-date" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Tanggal Evaluasi <span class="text-rose-600">*</span>
                    </label>
                    <input
                        type="date"
                        id="edit-evaluation-date"
                        name="evaluation_date"
                        required
                        class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 cursor-pointer"
                    />
                </div>
            </div>

            {{-- Kendala Operasional --}}
            <div>
                <label for="edit-operational-issues" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Kendala Operasional Pasca Rilis
                </label>
                <textarea
                    id="edit-operational-issues"
                    name="operational_issues"
                    rows="3"
                    class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900"
                ></textarea>
            </div>

            {{-- Umpan Balik Pengguna --}}
            <div>
                <label for="edit-user-feedback" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Umpan Balik Pengguna / Klien
                </label>
                <textarea
                    id="edit-user-feedback"
                    name="user_feedback"
                    rows="3"
                    class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900"
                ></textarea>
            </div>

            {{-- Tombol Submit Form --}}
            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-200">
                <button
                    type="button"
                    class="btn-close-modal px-4 py-2 rounded-lg text-xs sm:text-sm font-semibold text-slate-700 hover:bg-slate-100 transition cursor-pointer"
                >
                    Batal
                </button>
                <button
                    type="submit"
                    class="px-4 py-2 rounded-lg text-xs sm:text-sm font-semibold bg-slate-900 text-white hover:bg-slate-800 active:bg-slate-950 transition shadow-sm cursor-pointer"
                >
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL 3: HAPUS EVALUASI (Single Screen, Clean & Tegas) --}}
<div
    id="modal-delete-evaluation"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden"
    aria-modal="true"
    role="dialog"
>
    <div class="bg-white rounded-2xl shadow-xl border border-slate-200 w-full max-w-md overflow-hidden">
        <div class="p-6 text-center">
            <div class="w-12 h-12 rounded-full bg-rose-100 flex items-center justify-center text-rose-600 mx-auto mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                </svg>
            </div>
            <h3 class="text-base font-bold text-slate-900">Hapus Evaluasi Aplikasi?</h3>
            <p class="text-xs text-slate-500 mt-2">
                Apakah Anda yakin ingin menghapus data evaluasi periode <span id="delete-eval-period" class="font-bold text-slate-800"></span> untuk proyek <span id="delete-eval-project" class="font-bold text-slate-800"></span>?
            </p>
            <p class="text-[11px] text-rose-600 mt-1 font-medium">
                Seluruh catatan rencana tindak lanjut yang terhubung ke evaluasi ini juga akan dihapus.
            </p>

            <form id="form-delete-evaluation" method="POST" action="" class="mt-6 flex items-center justify-center gap-3">
                @csrf
                @method('DELETE')
                <button
                    type="button"
                    class="btn-close-modal px-4 py-2 rounded-lg text-xs sm:text-sm font-semibold text-slate-700 hover:bg-slate-100 transition cursor-pointer"
                >
                    Batal
                </button>
                <button
                    type="submit"
                    class="px-4 py-2 rounded-lg text-xs sm:text-sm font-semibold bg-rose-600 text-white hover:bg-rose-700 active:bg-rose-800 transition shadow-sm cursor-pointer"
                >
                    Ya, Hapus Evaluasi
                </button>
            </form>
        </div>
    </div>
</div>

{{-- MODAL 4: DETAIL EVALUASI & TINDAK LANJUT (Single Screen, Clean & Tegas) --}}
<div
    id="modal-detail-evaluation"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden"
    aria-modal="true"
    role="dialog"
>
    <div class="bg-white rounded-2xl shadow-xl border border-slate-200 w-full max-w-2xl max-h-[90vh] flex flex-col overflow-hidden">
        {{-- Modal Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 shrink-0 bg-slate-50/50">
            <div class="min-w-0">
                <div class="flex items-center gap-2">
                    <span id="detail-eval-period-badge" class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-indigo-50 text-indigo-700 border border-indigo-200"></span>
                    <span id="detail-eval-project-badge" class="font-bold text-slate-900 text-sm truncate"></span>
                </div>
                <p id="detail-eval-date-text" class="text-xs text-slate-500 mt-0.5"></p>
            </div>
            <button type="button" class="btn-close-modal p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Modal Body: Issues, Feedback, and Action Items --}}
        <div class="p-6 space-y-5 overflow-y-auto flex-1">
            {{-- Bagian Kendala & Umpan Balik --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div class="p-3.5 rounded-xl bg-amber-50/60 border border-amber-200/80">
                    <span class="block text-[11px] font-bold uppercase tracking-wider text-amber-800 mb-1">
                        Kendala Operasional Pasca Rilis
                    </span>
                    <p id="detail-eval-issues" class="text-xs text-slate-700 leading-relaxed"></p>
                </div>

                <div class="p-3.5 rounded-xl bg-blue-50/60 border border-blue-200/80">
                    <span class="block text-[11px] font-bold uppercase tracking-wider text-blue-800 mb-1">
                        Umpan Balik Pengguna / Klien
                    </span>
                    <p id="detail-eval-feedback" class="text-xs text-slate-700 leading-relaxed"></p>
                </div>
            </div>

            {{-- Bagian Rencana Tindak Lanjut (Action Items) --}}
            <div>
                <div class="flex items-center justify-between mb-2">
                    <h4 class="text-xs font-bold text-slate-900 uppercase tracking-wider">
                        Rencana Tindak Lanjut (*Action Items*)
                    </h4>
                    <span id="detail-eval-item-count" class="text-xs font-semibold text-slate-500"></span>
                </div>

                {{-- Tabel / List Tindak Lanjut --}}
                <div id="detail-action-items-list" class="space-y-2 border border-slate-200 rounded-xl divide-y divide-slate-100 overflow-hidden">
                    {{-- Dinamis diisi via JavaScript --}}
                </div>
            </div>

            {{-- Form Tambah Tindak Lanjut Baru --}}
            <div class="p-4 bg-slate-50 rounded-xl border border-slate-200">
                <span class="block text-xs font-bold text-slate-800 uppercase tracking-wider mb-2">
                    + Tambah Rencana Tindak Lanjut
                </span>
                <form id="form-add-action-item" method="POST" action="" class="space-y-2.5">
                    @csrf
                    <div>
                        <input
                            type="text"
                            name="action_plan"
                            required
                            placeholder="Deskripsi rencana tindakan atau perbaikan..."
                            class="w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900"
                        />
                    </div>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                        <div>
                            <select
                                name="assigned_to"
                                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 cursor-pointer"
                            >
                                <option value="">-- Pilih PIC Pengembang --</option>
                                @foreach ($developers as $dev)
                                    <option value="{{ $dev->id }}">{{ $dev->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <input
                                type="date"
                                name="target_date"
                                placeholder="Target Tanggal"
                                class="w-full rounded-lg border border-slate-300 bg-white px-3 py-1.5 text-xs text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 cursor-pointer"
                            />
                        </div>
                    </div>
                    <div class="flex justify-end pt-1">
                        <button
                            type="submit"
                            class="px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-indigo-600 text-white hover:bg-indigo-700 active:bg-indigo-800 transition shadow-2xs cursor-pointer"
                        >
                            Tambahkan Action Item
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Modal Footer --}}
        <div class="flex items-center justify-end px-6 py-3 border-t border-slate-200 shrink-0 bg-slate-50/50">
            <button
                type="button"
                class="btn-close-modal px-4 py-2 rounded-lg text-xs sm:text-sm font-semibold text-slate-700 hover:bg-slate-100 transition cursor-pointer"
            >
                Tutup
            </button>
        </div>
    </div>
</div>

{{-- JAVASCRIPT LOGIC (Modal Interactivity & Collapsible Sidebar) --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // 1. Sidebar Toggle Logic untuk Tablet/Mobile
        const sidebar = document.getElementById('app-sidebar');
        const backdrop = document.getElementById('sidebar-backdrop');
        const btnOpen = document.getElementById('btn-sidebar-open');
        const btnClose = document.getElementById('btn-sidebar-close');

        function openSidebar() {
            sidebar.classList.remove('-translate-x-full');
            backdrop.classList.remove('hidden');
        }

        function closeSidebar() {
            sidebar.classList.add('-translate-x-full');
            backdrop.classList.add('hidden');
        }

        if (btnOpen) btnOpen.addEventListener('click', openSidebar);
        if (btnClose) btnClose.addEventListener('click', closeSidebar);
        if (backdrop) backdrop.addEventListener('click', closeSidebar);

        // 2. Modals Helper
        const createModal = document.getElementById('modal-create-evaluation');
        const editModal = document.getElementById('modal-edit-evaluation');
        const deleteModal = document.getElementById('modal-delete-evaluation');
        const detailModal = document.getElementById('modal-detail-evaluation');

        const btnOpenCreate = document.getElementById('btn-open-create-evaluation');
        const formEdit = document.getElementById('form-edit-evaluation');
        const formDelete = document.getElementById('form-delete-evaluation');
        const formAddActionItem = document.getElementById('form-add-action-item');

        function openModal(el) {
            if (el) el.classList.remove('hidden');
        }

        function closeAllModals() {
            if (createModal) createModal.classList.add('hidden');
            if (editModal) editModal.classList.add('hidden');
            if (deleteModal) deleteModal.classList.add('hidden');
            if (detailModal) detailModal.classList.add('hidden');
        }

        document.querySelectorAll('.btn-close-modal').forEach(btn => {
            btn.addEventListener('click', closeAllModals);
        });

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') closeAllModals();
        });

        // 3. Open Create Modal
        if (btnOpenCreate) {
            btnOpenCreate.addEventListener('click', function () {
                openModal(createModal);
            });
        }

        // 4. Open Edit Modal
        document.querySelectorAll('.btn-edit-evaluation').forEach(btn => {
            btn.addEventListener('click', function () {
                const dataRaw = this.getAttribute('data-evaluation');
                if (!dataRaw) return;
                const data = JSON.parse(dataRaw);

                formEdit.action = data.update_url;
                document.getElementById('edit-project-id').value = data.project_id;
                document.getElementById('edit-period-review').value = data.period_review || '';
                document.getElementById('edit-evaluation-date').value = data.evaluation_date || '';
                document.getElementById('edit-operational-issues').value = data.operational_issues || '';
                document.getElementById('edit-user-feedback').value = data.user_feedback || '';

                openModal(editModal);
            });
        });

        // 5. Open Delete Modal
        document.querySelectorAll('.btn-delete-evaluation').forEach(btn => {
            btn.addEventListener('click', function () {
                const dataRaw = this.getAttribute('data-evaluation');
                if (!dataRaw) return;
                const data = JSON.parse(dataRaw);

                formDelete.action = data.delete_url;
                document.getElementById('delete-eval-period').textContent = data.period_review || '-';
                document.getElementById('delete-eval-project').textContent = '[' + data.project_code + '] ' + data.project_name;

                openModal(deleteModal);
            });
        });

        // 6. Open Detail & Tindak Lanjut Modal
        document.querySelectorAll('.btn-detail-evaluation').forEach(btn => {
            btn.addEventListener('click', function () {
                const dataRaw = this.getAttribute('data-evaluation');
                if (!dataRaw) return;
                const data = JSON.parse(dataRaw);

                document.getElementById('detail-eval-period-badge').textContent = data.period_review || '-';
                document.getElementById('detail-eval-project-badge').textContent = '[' + data.project_code + '] ' + data.project_name;
                document.getElementById('detail-eval-date-text').textContent = 'Tanggal Evaluasi: ' + data.evaluation_date_formatted;

                const issuesEl = document.getElementById('detail-eval-issues');
                if (data.operational_issues) {
                    issuesEl.textContent = data.operational_issues;
                    issuesEl.classList.remove('italic', 'text-slate-400');
                } else {
                    issuesEl.textContent = 'Tidak ada catatan kendala operasional.';
                    issuesEl.classList.add('italic', 'text-slate-400');
                }

                const feedbackEl = document.getElementById('detail-eval-feedback');
                if (data.user_feedback) {
                    feedbackEl.textContent = data.user_feedback;
                    feedbackEl.classList.remove('italic', 'text-slate-400');
                } else {
                    feedbackEl.textContent = 'Tidak ada catatan umpan balik pengguna.';
                    feedbackEl.classList.add('italic', 'text-slate-400');
                }

                formAddActionItem.action = data.action_item_store_url;

                // Render Action Items List
                const itemsListEl = document.getElementById('detail-action-items-list');
                itemsListEl.innerHTML = '';
                const items = data.action_items || [];
                document.getElementById('detail-eval-item-count').textContent = items.length + ' Item Terdaftar';

                if (items.length === 0) {
                    itemsListEl.innerHTML = `
                        <div class="p-6 text-center text-xs text-slate-400 italic bg-white">
                            Belum ada rencana tindak lanjut yang dicatat. Tambahkan melalui form di bawah.
                        </div>
                    `;
                } else {
                    items.forEach(item => {
                        let badgeClass = 'bg-amber-50 text-amber-700 border-amber-200';
                        let labelText = 'Direncanakan';
                        if (item.status === 'converted_to_task') {
                            badgeClass = 'bg-blue-50 text-blue-700 border-blue-200';
                            labelText = 'Dikonversi ke Tugas';
                        } else if (item.status === 'completed') {
                            badgeClass = 'bg-emerald-50 text-emerald-700 border-emerald-200';
                            labelText = 'Selesai';
                        }

                        const itemRow = document.createElement('div');
                        itemRow.className = 'p-3 bg-white flex flex-col sm:flex-row sm:items-center justify-between gap-2';
                        itemRow.innerHTML = `
                            <div class="min-w-0 flex-1">
                                <p class="text-xs font-semibold text-slate-800">${item.action_plan}</p>
                                <div class="flex items-center gap-2 mt-1 text-[11px] text-slate-500">
                                    <span class="inline-flex items-center gap-1 font-medium">
                                        <svg class="w-3 h-3 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                        </svg>
                                        ${item.assignee_name}
                                    </span>
                                    <span>•</span>
                                    <span>Target: ${item.target_date_formatted}</span>
                                </div>
                            </div>
                            <div class="flex items-center gap-2 shrink-0">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold border ${badgeClass}">
                                    ${labelText}
                                </span>
                            </div>
                        `;
                        itemsListEl.appendChild(itemRow);
                    });
                }

                openModal(detailModal);
            });
        });
    });
</script>

</body>
</html>
