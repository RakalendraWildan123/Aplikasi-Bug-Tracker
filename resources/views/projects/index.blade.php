<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Master Proyek - {{ config('app.name', 'Aplikasi Bug Tracker') }}</title>
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

                    {{-- Fitur Master Proyek (Active State) --}}
                    <a
                        href="{{ route('projects.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3.5 py-2.5 text-sm font-semibold transition bg-slate-800 text-white border border-slate-700/70 shadow-xs"
                    >
                        <svg class="w-5 h-5 text-indigo-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
                        {{ $user->avatar ?? 'DV' }}
                    </div>
                @endif
                <span class="hidden sm:inline-block text-xs font-semibold text-slate-700 truncate max-w-28">{{ $user->name }}</span>
            </div>
        </header>

        {{-- Main Page Container --}}
        <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto">

            {{-- Flash Alert Notifikasi --}}
            @if (session('status'))
                <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 p-4 text-sm font-semibold text-emerald-800 shadow-xs flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>{{ session('status') }}</span>
                    </div>
                    <button type="button" onclick="this.parentElement.remove()" class="text-emerald-600 hover:text-emerald-900 cursor-pointer">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm font-semibold text-rose-800 shadow-xs">
                    <div class="flex items-start gap-2">
                        <svg class="w-5 h-5 text-rose-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                        </svg>
                        <div>
                            <p class="font-bold">Terjadi kendala dalam pengisian data proyek:</p>
                            <ul class="list-disc list-inside mt-1 font-normal text-xs sm:text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Header Halaman & Tombol Tambah Proyek --}}
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">Master Proyek</h1>
                    <p class="mt-1 text-sm text-slate-600">Kelola data proyek sistem, kode unik, repositori kode sumber, status siklus, dan label warna identitas.</p>
                </div>

                {{-- Tombol Buka Pop-up Tambah Proyek --}}
                <div class="flex items-center gap-2">
                    <button
                        type="button"
                        id="btn-open-create-project"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs sm:text-sm font-semibold transition shadow-xs cursor-pointer focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                        </svg>
                        <span>Tambah Proyek Baru</span>
                    </button>
                </div>
            </div>

            {{-- Ringkasan Metrik Statistik Proyek (Bersih & Tegas) --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
                <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-2xs">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Total Proyek</div>
                    <div class="mt-1 text-2xl font-black tracking-tight text-slate-900">{{ $metrics['total'] }}</div>
                    <div class="mt-1 text-[11px] text-slate-500">Terdaftar dalam workspace</div>
                </div>

                <div class="p-4 rounded-xl border border-emerald-200 bg-emerald-50/50 shadow-2xs">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-emerald-700">Proyek Aktif</div>
                    <div class="mt-1 text-2xl font-black tracking-tight text-emerald-900">{{ $metrics['active'] }}</div>
                    <div class="mt-1 text-[11px] text-emerald-600">Fase pengerjaan aktif</div>
                </div>

                <div class="p-4 rounded-xl border border-amber-200 bg-amber-50/50 shadow-2xs">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-amber-700">Masa Pemeliharaan</div>
                    <div class="mt-1 text-2xl font-black tracking-tight text-amber-900">{{ $metrics['maintenance'] }}</div>
                    <div class="mt-1 text-[11px] text-amber-600">Fase perawatan / rilis rutin</div>
                </div>

                <div class="p-4 rounded-xl border border-slate-200 bg-slate-100/50 shadow-2xs">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-slate-600">Diarsipkan</div>
                    <div class="mt-1 text-2xl font-black tracking-tight text-slate-800">{{ $metrics['archived'] }}</div>
                    <div class="mt-1 text-[11px] text-slate-500">Proyek selesai / nonaktif</div>
                </div>
            </div>

            {{-- Bar Filter & Pencarian (Ramah Desktop & Tablet) --}}
            <div class="mb-6 p-4 rounded-xl border border-slate-200 bg-white shadow-2xs">
                <form method="GET" action="{{ route('projects.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    {{-- Input Pencarian --}}
                    <div class="sm:col-span-2">
                        <label for="search-input" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Cari Proyek</label>
                        <div class="relative">
                            <input
                                type="text"
                                id="search-input"
                                name="search"
                                value="{{ $search }}"
                                placeholder="Cari nama proyek, kode unik, repositori, atau deskripsi..."
                                class="w-full pl-9 pr-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                            />
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Filter Status Proyek --}}
                    <div>
                        <label for="status-filter" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Status Siklus</label>
                        <div class="flex items-center gap-2">
                            <select
                                id="status-filter"
                                name="status"
                                onchange="this.form.submit()"
                                class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition cursor-pointer"
                            >
                                <option value="">Semua Status</option>
                                <option value="active" @selected($statusFilter === 'active')>Aktif (Active)</option>
                                <option value="maintenance" @selected($statusFilter === 'maintenance')>Pemeliharaan (Maintenance)</option>
                                <option value="archived" @selected($statusFilter === 'archived')>Diarsipkan (Archived)</option>
                            </select>

                            @if ($search || $statusFilter)
                                <a
                                    href="{{ route('projects.index') }}"
                                    class="p-2 rounded-lg border border-slate-300 bg-slate-50 hover:bg-slate-100 text-slate-600 transition shrink-0 cursor-pointer"
                                    title="Reset Semua Filter"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                                    </svg>
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            {{-- Tabel Master Proyek (Tegas, Responsif & Ramah Desktop/Tablet) --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-2xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs sm:text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50/75 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                <th scope="col" class="py-3.5 pl-5 pr-3">Proyek & Kode</th>
                                <th scope="col" class="py-3.5 px-3">Status Siklus</th>
                                <th scope="col" class="py-3.5 px-3 hidden md:table-cell">Repositori Git</th>
                                <th scope="col" class="py-3.5 px-3">Cakupan Data</th>
                                <th scope="col" class="py-3.5 pl-3 pr-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            @forelse ($projects as $project)
                                @php
                                    $projectData = [
                                        'id' => $project->id,
                                        'name' => $project->name,
                                        'code' => $project->code,
                                        'description' => $project->description,
                                        'repo_url' => $project->repo_url,
                                        'status' => $project->status,
                                        'color' => $project->color ?? '#4F46E5',
                                        'modules_count' => $project->modules_count ?? 0,
                                        'tasks_count' => $project->tasks_count ?? 0,
                                        'bugs_count' => $project->bugs_count ?? 0,
                                    ];
                                @endphp
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    {{-- 1. Proyek & Kode --}}
                                    <td class="py-3.5 pl-5 pr-3">
                                        <div class="flex items-start gap-3">
                                            <div
                                                class="w-10 h-10 rounded-lg flex items-center justify-center font-black text-white shrink-0 shadow-2xs text-xs tracking-wider"
                                                style="background-color: {{ $project->color ?? '#4F46E5' }}"
                                            >
                                                {{ Str::substr($project->code, 0, 3) }}
                                            </div>
                                            <div class="min-w-0">
                                                <div class="flex items-center gap-1.5 flex-wrap">
                                                    <span class="font-bold text-slate-900 truncate">{{ $project->name }}</span>
                                                    <span
                                                        class="px-2 py-0.5 rounded text-[10px] font-bold font-mono border"
                                                        style="border-color: {{ $project->color ?? '#4F46E5' }}33; color: {{ $project->color ?? '#4F46E5' }}; background-color: {{ $project->color ?? '#4F46E5' }}12;"
                                                    >
                                                        [{{ $project->code }}]
                                                    </span>
                                                </div>
                                                @if ($project->description)
                                                    <p class="text-xs text-slate-500 mt-0.5 line-clamp-1 max-w-md">{{ $project->description }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>

                                    {{-- 2. Status Siklus --}}
                                    <td class="py-3.5 px-3 whitespace-nowrap">
                                        @if ($project->status === 'active')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Aktif
                                            </span>
                                        @elseif ($project->status === 'maintenance')
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                                Pemeliharaan
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                                Diarsipkan
                                            </span>
                                        @endif
                                    </td>

                                    {{-- 3. Repositori Git --}}
                                    <td class="py-3.5 px-3 hidden md:table-cell whitespace-nowrap text-xs">
                                        @if ($project->repo_url)
                                            <a
                                                href="{{ $project->repo_url }}"
                                                target="_blank"
                                                rel="noopener noreferrer"
                                                class="inline-flex items-center gap-1.5 text-indigo-600 hover:text-indigo-800 hover:underline font-mono"
                                                title="{{ $project->repo_url }}"
                                            >
                                                <svg class="w-3.5 h-3.5 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.53 1.032 1.53 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/>
                                                </svg>
                                                <span>{{ Str::limit(str_replace(['https://', 'http://'], '', $project->repo_url), 28) }}</span>
                                            </a>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>

                                    {{-- 4. Cakupan Data (Modul, Tugas, Bug) --}}
                                    <td class="py-3.5 px-3 whitespace-nowrap">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200" title="Jumlah Modul">
                                                {{ $project->modules_count }} Modul
                                            </span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200" title="Jumlah Tugas">
                                                {{ $project->tasks_count }} Tugas
                                            </span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200" title="Jumlah Bug">
                                                {{ $project->bugs_count }} Bug
                                            </span>
                                        </div>
                                    </td>

                                    {{-- 5. Aksi: Edit & Hapus --}}
                                    <td class="py-3.5 pl-3 pr-5 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-1.5">
                                            {{-- Tombol Edit Proyek --}}
                                            <button
                                                type="button"
                                                class="btn-edit-project inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg border border-slate-300 bg-white text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition cursor-pointer shadow-2xs"
                                                data-project="{{ json_encode($projectData) }}"
                                                title="Edit data proyek"
                                            >
                                                <svg class="w-3.5 h-3.5 text-slate-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                                </svg>
                                                <span>Edit</span>
                                            </button>

                                            {{-- Tombol Hapus Proyek --}}
                                            <button
                                                type="button"
                                                class="btn-delete-project p-1.5 rounded-lg border border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100 hover:text-rose-900 transition cursor-pointer shadow-2xs"
                                                data-id="{{ $project->id }}"
                                                data-name="{{ $project->name }}"
                                                data-code="{{ $project->code }}"
                                                title="Hapus proyek"
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
                                    <td colspan="5" class="py-12 text-center">
                                        <div class="flex flex-col items-center justify-center text-slate-400">
                                            <svg class="w-12 h-12 stroke-1 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.69-6.44l-2.12-2.12a1.5 1.5 0 00-1.061-.44H4.5A2.25 2.25 0 002.25 6v12a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9a2.25 2.25 0 00-2.25-2.25h-5.379a1.5 1.5 0 01-1.06-.44z"/>
                                            </svg>
                                            <p class="text-sm font-semibold text-slate-600">Tidak ada proyek ditemukan</p>
                                            <p class="text-xs text-slate-400 mt-1">Gunakan kata kunci atau filter status yang berbeda.</p>
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

{{-- ========================================================================= --}}
{{-- 1. POP-UP MODAL: TAMBAH PROYEK BARU                                       --}}
{{-- ========================================================================= --}}
<div
    id="modal-create-project"
    class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4 overflow-y-auto hidden"
    aria-hidden="true"
>
    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden my-8">
        {{-- Header Modal --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-slate-50/75">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Tambah Proyek Baru</h3>
                <p class="text-xs text-slate-500 mt-0.5">Daftarkan proyek baru ke dalam sistem monitoring.</p>
            </div>
            <button type="button" class="btn-close-modal p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-200 cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Form Tambah Proyek --}}
        <form method="POST" action="{{ route('projects.store') }}" class="p-6 space-y-4">
            @csrf

            {{-- Nama Proyek --}}
            <div>
                <label for="create-name" class="block text-xs font-bold text-slate-700 mb-1">Nama Proyek <span class="text-rose-500">*</span></label>
                <input
                    type="text"
                    id="create-name"
                    name="name"
                    required
                    placeholder="Contoh: Sistem Informasi Penggajian"
                    class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                />
            </div>

            {{-- Kode Proyek & Status --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="create-code" class="block text-xs font-bold text-slate-700 mb-1">Kode Unik Proyek <span class="text-rose-500">*</span></label>
                    <input
                        type="text"
                        id="create-code"
                        name="code"
                        required
                        placeholder="Contoh: PAYROLL"
                        class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-800 uppercase placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition font-mono"
                    />
                </div>

                <div>
                    <label for="create-status" class="block text-xs font-bold text-slate-700 mb-1">Status Siklus <span class="text-rose-500">*</span></label>
                    <select
                        id="create-status"
                        name="status"
                        required
                        class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition cursor-pointer"
                    >
                        <option value="active" selected>Aktif (Active)</option>
                        <option value="maintenance">Pemeliharaan (Maintenance)</option>
                        <option value="archived">Diarsipkan (Archived)</option>
                    </select>
                </div>
            </div>

            {{-- Warna Identitas & URL Repositori --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="create-color" class="block text-xs font-bold text-slate-700 mb-1">Warna Label Identitas <span class="text-rose-500">*</span></label>
                    <div class="flex items-center gap-2">
                        <input
                            type="color"
                            id="create-color"
                            name="color"
                            value="#4F46E5"
                            class="h-9 w-12 rounded border border-slate-300 p-0.5 cursor-pointer"
                        />
                        <span class="text-xs text-slate-500">Pilih warna proyek</span>
                    </div>
                </div>

                <div>
                    <label for="create-repo-url" class="block text-xs font-bold text-slate-700 mb-1">URL Repositori Git <span class="text-slate-400 font-normal">(Opsional)</span></label>
                    <input
                        type="url"
                        id="create-repo-url"
                        name="repo_url"
                        placeholder="https://github.com/org/repo"
                        class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                    />
                </div>
            </div>

            {{-- Deskripsi Proyek --}}
            <div>
                <label for="create-description" class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Ringkas <span class="text-slate-400 font-normal">(Opsional)</span></label>
                <textarea
                    id="create-description"
                    name="description"
                    rows="3"
                    placeholder="Tuliskan tujuan dan cakupan utama proyek ini..."
                    class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                ></textarea>
            </div>

            {{-- Tombol Aksi Modal --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
                <button
                    type="button"
                    class="btn-close-modal px-4 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm font-semibold text-slate-700 hover:bg-slate-50 transition cursor-pointer"
                >
                    Batal
                </button>
                <button
                    type="submit"
                    class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs sm:text-sm font-semibold transition shadow-xs cursor-pointer focus:ring-2 focus:ring-indigo-500"
                >
                    Simpan Proyek
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ========================================================================= --}}
{{-- 2. POP-UP MODAL: EDIT PROYEK                                              --}}
{{-- ========================================================================= --}}
<div
    id="modal-edit-project"
    class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4 overflow-y-auto hidden"
    aria-hidden="true"
>
    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden my-8">
        {{-- Header Modal --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-slate-50/75">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Edit Data Proyek</h3>
                <p class="text-xs text-slate-500 mt-0.5">Perbarui informasi, status, atau repositori proyek.</p>
            </div>
            <button type="button" class="btn-close-modal p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-200 cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Form Edit Proyek --}}
        <form id="form-edit-project" method="POST" action="" class="p-6 space-y-4">
            @csrf
            @method('PUT')

            {{-- Nama Proyek --}}
            <div>
                <label for="edit-name" class="block text-xs font-bold text-slate-700 mb-1">Nama Proyek <span class="text-rose-500">*</span></label>
                <input
                    type="text"
                    id="edit-name"
                    name="name"
                    required
                    class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                />
            </div>

            {{-- Kode Proyek & Status --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="edit-code" class="block text-xs font-bold text-slate-700 mb-1">Kode Unik Proyek <span class="text-rose-500">*</span></label>
                    <input
                        type="text"
                        id="edit-code"
                        name="code"
                        required
                        class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-800 uppercase placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition font-mono"
                    />
                </div>

                <div>
                    <label for="edit-status" class="block text-xs font-bold text-slate-700 mb-1">Status Siklus <span class="text-rose-500">*</span></label>
                    <select
                        id="edit-status"
                        name="status"
                        required
                        class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition cursor-pointer"
                    >
                        <option value="active">Aktif (Active)</option>
                        <option value="maintenance">Pemeliharaan (Maintenance)</option>
                        <option value="archived">Diarsipkan (Archived)</option>
                    </select>
                </div>
            </div>

            {{-- Warna Identitas & URL Repositori --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="edit-color" class="block text-xs font-bold text-slate-700 mb-1">Warna Label Identitas <span class="text-rose-500">*</span></label>
                    <div class="flex items-center gap-2">
                        <input
                            type="color"
                            id="edit-color"
                            name="color"
                            class="h-9 w-12 rounded border border-slate-300 p-0.5 cursor-pointer"
                        />
                        <span class="text-xs text-slate-500">Pilih warna proyek</span>
                    </div>
                </div>

                <div>
                    <label for="edit-repo-url" class="block text-xs font-bold text-slate-700 mb-1">URL Repositori Git <span class="text-slate-400 font-normal">(Opsional)</span></label>
                    <input
                        type="url"
                        id="edit-repo-url"
                        name="repo_url"
                        placeholder="https://github.com/org/repo"
                        class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                    />
                </div>
            </div>

            {{-- Deskripsi Proyek --}}
            <div>
                <label for="edit-description" class="block text-xs font-bold text-slate-700 mb-1">Deskripsi Ringkas <span class="text-slate-400 font-normal">(Opsional)</span></label>
                <textarea
                    id="edit-description"
                    name="description"
                    rows="3"
                    class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                ></textarea>
            </div>

            {{-- Tombol Aksi Modal Edit --}}
            <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
                <button
                    type="button"
                    class="btn-close-modal px-4 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm font-semibold text-slate-700 hover:bg-slate-50 transition cursor-pointer"
                >
                    Batal
                </button>
                <button
                    type="submit"
                    class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs sm:text-sm font-semibold transition shadow-xs cursor-pointer focus:ring-2 focus:ring-indigo-500"
                >
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ========================================================================= --}}
{{-- 3. POP-UP MODAL: HAPUS PROYEK                                             --}}
{{-- ========================================================================= --}}
<div
    id="modal-delete-project"
    class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4 overflow-y-auto hidden"
    aria-hidden="true"
>
    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden my-8">
        <div class="p-6">
            <div class="flex items-center gap-3 text-rose-600 mb-3">
                <div class="w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center shrink-0">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900">Hapus Proyek?</h3>
            </div>

            <p class="text-sm text-slate-600 mb-2">
                Apakah Anda yakin ingin menghapus proyek <span id="delete-project-name" class="font-bold text-slate-900"></span> (<span id="delete-project-code" class="font-mono font-semibold text-slate-700"></span>)?
            </p>
            <p class="text-xs text-rose-600 bg-rose-50 p-2.5 rounded-lg border border-rose-200">
                Peringatan: Seluruh modul, tugas, dan temuan bug yang tertaut pada proyek ini akan ikut terhapus secara otomatis karena relasi database terkait.
            </p>

            <form id="form-delete-project" method="POST" action="" class="mt-6 flex items-center justify-end gap-3">
                @csrf
                @method('DELETE')
                <button
                    type="button"
                    class="btn-close-modal px-4 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm font-semibold text-slate-700 hover:bg-slate-50 transition cursor-pointer"
                >
                    Batal
                </button>
                <button
                    type="submit"
                    class="px-4 py-2 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-xs sm:text-sm font-semibold transition shadow-xs cursor-pointer focus:ring-2 focus:ring-rose-500"
                >
                    Hapus Proyek
                </button>
            </form>
        </div>
    </div>
</div>

{{-- Script Interaktivitas Modal & Sidebar --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // 1. KONTROL SIDEBAR (Mobile & Tablet)
        const sidebar = document.getElementById('app-sidebar');
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

        // 2. KONTROL MODAL TAMBAH PROYEK
        const modalCreate = document.getElementById('modal-create-project');
        const btnOpenCreate = document.getElementById('btn-open-create-project');

        btnOpenCreate?.addEventListener('click', () => {
            modalCreate?.classList.remove('hidden');
        });

        // 3. KONTROL MODAL EDIT PROYEK
        const modalEdit = document.getElementById('modal-edit-project');
        const formEdit = document.getElementById('form-edit-project');

        document.querySelectorAll('.btn-edit-project').forEach(btn => {
            btn.addEventListener('click', () => {
                const data = JSON.parse(btn.dataset.project);
                formEdit.action = `/projects/${data.id}`;

                document.getElementById('edit-name').value = data.name || '';
                document.getElementById('edit-code').value = data.code || '';
                document.getElementById('edit-status').value = data.status || 'active';
                document.getElementById('edit-color').value = data.color || '#4F46E5';
                document.getElementById('edit-repo-url').value = data.repo_url || '';
                document.getElementById('edit-description').value = data.description || '';

                modalEdit?.classList.remove('hidden');
            });
        });

        // 4. KONTROL MODAL HAPUS PROYEK
        const modalDelete = document.getElementById('modal-delete-project');
        const formDelete = document.getElementById('form-delete-project');
        const deleteName = document.getElementById('delete-project-name');
        const deleteCode = document.getElementById('delete-project-code');

        document.querySelectorAll('.btn-delete-project').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                const name = btn.dataset.name;
                const code = btn.dataset.code;

                formDelete.action = `/projects/${id}`;
                deleteName.textContent = name;
                deleteCode.textContent = `[${code}]`;
                modalDelete?.classList.remove('hidden');
            });
        });

        // 5. TUTUP SEMUA MODAL
        document.querySelectorAll('.btn-close-modal').forEach(btn => {
            btn.addEventListener('click', () => {
                modalCreate?.classList.add('hidden');
                modalEdit?.classList.add('hidden');
                modalDelete?.classList.add('hidden');
            });
        });

        // Tutup modal jika klik di luar box modal
        [modalCreate, modalEdit, modalDelete].forEach(modal => {
            modal?.addEventListener('click', (e) => {
                if (e.target === modal) {
                    modal.classList.add('hidden');
                }
            });
        });
    });
</script>

</body>
</html>
