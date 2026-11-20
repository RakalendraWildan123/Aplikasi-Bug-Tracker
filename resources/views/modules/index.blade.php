<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Master Modul - {{ config('app.name', 'Aplikasi Bug Tracker') }}</title>
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

                    {{-- Fitur Master Modul (Active State) --}}
                    <a
                        href="{{ route('modules.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3.5 py-2.5 text-sm font-semibold transition bg-slate-800 text-white border border-slate-700/70 shadow-xs"
                    >
                        <svg class="w-5 h-5 text-indigo-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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

            {{-- Flash Alert Status Berhasil / Notifikasi --}}
            @if (session('status'))
                <div class="mb-6 flex items-center gap-3 p-4 rounded-xl border border-emerald-200 bg-emerald-50/90 text-emerald-800 text-sm font-semibold shadow-xs">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <span>{{ session('status') }}</span>
                </div>
            @endif

            {{-- Flash Alert Error Validasi --}}
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-xl border border-rose-200 bg-rose-50/90 text-rose-800 text-sm shadow-xs">
                    <div class="flex items-center gap-2 font-bold mb-1">
                        <svg class="w-5 h-5 text-rose-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z" />
                        </svg>
                        <span>Gagal Menyimpan Data Modul:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Header Halaman Master Modul --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">Master Modul</h1>
                    <p class="mt-1 text-sm text-slate-600">Kelola modul fitur untuk setiap proyek, pemetaan tugas, dan cakupan bug.</p>
                </div>

                {{-- Tombol Buka Modal Tambah Modul --}}
                <button
                    type="button"
                    id="btn-open-create-module"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-xs sm:text-sm font-semibold text-white shadow-xs hover:bg-indigo-700 transition cursor-pointer shrink-0"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    <span>Tambah Modul</span>
                </button>
            </div>

            {{-- Kartu Metrik Ringkasan (Bersih, Jelas, Informatif) --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
                {{-- Total Modul --}}
                <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-2xs">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Total Modul</div>
                    <div class="mt-2 text-2xl font-extrabold text-slate-900">{{ $metrics['total'] }}</div>
                    <div class="mt-1 text-xs text-slate-500">Seluruh modul terdaftar</div>
                </div>

                {{-- Modul dengan Tugas --}}
                <div class="rounded-xl border border-indigo-200 bg-indigo-50/50 p-4 shadow-2xs">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-indigo-700">Dengan Tugas</div>
                    <div class="mt-2 text-2xl font-extrabold text-indigo-900">{{ $metrics['with_tasks'] }}</div>
                    <div class="mt-1 text-xs text-indigo-700/80">Memiliki rincian tugas dev</div>
                </div>

                {{-- Modul dengan Bug --}}
                <div class="rounded-xl border border-rose-200 bg-rose-50/50 p-4 shadow-2xs">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-rose-700">Dengan Bug</div>
                    <div class="mt-2 text-2xl font-extrabold text-rose-900">{{ $metrics['with_bugs'] }}</div>
                    <div class="mt-1 text-xs text-rose-700/80">Memiliki laporan temuan bug</div>
                </div>

                {{-- Proyek Terkait --}}
                <div class="rounded-xl border border-emerald-200 bg-emerald-50/50 p-4 shadow-2xs">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-emerald-700">Proyek Terkait</div>
                    <div class="mt-2 text-2xl font-extrabold text-emerald-900">{{ $metrics['projects_count'] }}</div>
                    <div class="mt-1 text-xs text-emerald-700/80">Proyek memiliki modul</div>
                </div>
            </div>

            {{-- Filter & Search Bar --}}
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-2xs mb-6">
                <form method="GET" action="{{ route('modules.index') }}" class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3">
                    {{-- Pencarian Kata Kunci --}}
                    <div class="relative flex-1">
                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Cari modul, deskripsi, atau proyek..."
                            class="w-full rounded-lg border border-slate-300 py-2 pl-9 pr-3.5 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900"
                        >
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                        </svg>
                    </div>

                    {{-- Filter Dropdown Proyek & Tombol Reset --}}
                    <div class="flex items-center gap-2 shrink-0">
                        <select
                            name="project_id"
                            onchange="this.form.submit()"
                            class="rounded-lg border border-slate-300 bg-white py-2 px-3 text-xs sm:text-sm text-slate-700 focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 cursor-pointer"
                        >
                            <option value="">Semua Proyek (Semua)</option>
                            @foreach ($projects as $proj)
                                <option value="{{ $proj->id }}" {{ (string) $projectIdFilter === (string) $proj->id ? 'selected' : '' }}>
                                    [{{ $proj->code }}] {{ $proj->name }}
                                </option>
                            @endforeach
                        </select>

                        @if ($search || $projectIdFilter)
                            <a
                                href="{{ route('modules.index') }}"
                                class="p-2 rounded-lg border border-slate-300 bg-slate-50 hover:bg-slate-100 text-slate-600 transition shrink-0 cursor-pointer"
                                title="Reset Semua Filter"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/>
                                </svg>
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Tabel Master Modul (Tegas, Responsif & Ramah Desktop/Tablet) --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-2xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs sm:text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50/75 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                <th scope="col" class="py-3.5 pl-5 pr-3">Modul & Deskripsi</th>
                                <th scope="col" class="py-3.5 px-3">Proyek Induk</th>
                                <th scope="col" class="py-3.5 px-3">Cakupan Terkait</th>
                                <th scope="col" class="py-3.5 pl-3 pr-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            @forelse ($modules as $module)
                                @php
                                    $moduleData = [
                                        'id' => $module->id,
                                        'project_id' => $module->project_id,
                                        'name' => $module->name,
                                        'description' => $module->description,
                                        'project_code' => $module->project?->code ?? '-',
                                        'project_name' => $module->project?->name ?? 'Tanpa Proyek',
                                    ];
                                @endphp
                                <tr class="hover:bg-slate-50/70 transition-colors" id="row-module-{{ $module->id }}">
                                    {{-- 1. Modul & Deskripsi --}}
                                    <td class="py-3.5 pl-5 pr-3 max-w-xs sm:max-w-md">
                                        <div class="font-bold text-slate-900 text-sm">
                                            {{ $module->name }}
                                        </div>
                                        <div class="text-xs text-slate-500 mt-0.5 line-clamp-2">
                                            {{ $module->description ?: 'Tidak ada keterangan tambahan.' }}
                                        </div>
                                    </td>

                                    {{-- 2. Proyek Induk --}}
                                    <td class="py-3.5 px-3 whitespace-nowrap">
                                        @if ($module->project)
                                            <div class="flex items-center gap-2">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-mono font-bold text-white shadow-2xs" style="background-color: {{ $module->project->color ?? '#4F46E5' }}">
                                                    [{{ $module->project->code }}]
                                                </span>
                                                <span class="text-xs font-semibold text-slate-800 truncate max-w-44" title="{{ $module->project->name }}">
                                                    {{ $module->project->name }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-xs text-slate-400 italic">Tanpa Proyek</span>
                                        @endif
                                    </td>

                                    {{-- 3. Cakupan Terkait (Tugas, Bug) --}}
                                    <td class="py-3.5 px-3 whitespace-nowrap">
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200" title="Jumlah Tugas Terkait">
                                                {{ $module->tasks_count }} Tugas
                                            </span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200" title="Jumlah Bug Terkait">
                                                {{ $module->bugs_count }} Bug
                                            </span>
                                        </div>
                                    </td>

                                    {{-- 4. Aksi: Edit & Hapus --}}
                                    <td class="py-3.5 pl-3 pr-5 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-1.5">
                                            {{-- Tombol Edit Modul --}}
                                            <button
                                                type="button"
                                                class="btn-edit-module inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg border border-slate-300 bg-white text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition cursor-pointer shadow-2xs"
                                                data-module="{{ json_encode($moduleData) }}"
                                                title="Edit data modul"
                                            >
                                                <svg class="w-3.5 h-3.5 text-slate-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                                </svg>
                                                <span>Edit</span>
                                            </button>

                                            {{-- Tombol Hapus Modul --}}
                                            <button
                                                type="button"
                                                class="btn-delete-module p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer"
                                                data-id="{{ $module->id }}"
                                                data-name="{{ $module->name }}"
                                                data-project="{{ $module->project?->name ?? 'Umum' }}"
                                                title="Hapus modul"
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
                                    <td colspan="4" class="py-12 text-center">
                                        <div class="max-w-xs mx-auto text-center">
                                            <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mx-auto mb-3">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6A2.25 2.25 0 016 3.75h2.25A2.25 2.25 0 0110.5 6v2.25a2.25 2.25 0 01-2.25 2.25H6a2.25 2.25 0 01-2.25-2.25V6zM3.75 15.75A2.25 2.25 0 016 13.5h2.25a2.25 2.25 0 012.25 2.25V18a2.25 2.25 0 01-2.25 2.25H6A2.25 2.25 0 013.75 18v-2.25zM13.5 6a2.25 2.25 0 012.25-2.25H18A2.25 2.25 0 0120.25 6v2.25A2.25 2.25 0 0118 10.5h-2.25a2.25 2.25 0 01-2.25-2.25V6zM13.5 15.75a2.25 2.25 0 012.25-2.25H18a2.25 2.25 0 012.25 2.25V18A2.25 2.25 0 0118 20.25h-2.25A2.25 2.25 0 0113.5 18v-2.25z"/>
                                                </svg>
                                            </div>
                                            <p class="text-sm font-semibold text-slate-800">Tidak ada modul ditemukan</p>
                                            <p class="text-xs text-slate-500 mt-1">Coba sesuaikan kata kunci pencarian atau filter proyek.</p>
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

{{-- MODAL 1: TAMBAH MODUL (Single Screen, Clean & Tegas) --}}
<div
    id="modal-create-module"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden"
    aria-modal="true"
    role="dialog"
>
    <div class="bg-white rounded-2xl shadow-xl border border-slate-200 w-full max-w-lg overflow-hidden">
        {{-- Modal Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
            <div>
                <h3 class="text-base font-bold text-slate-900">Tambah Modul Baru</h3>
                <p class="text-xs text-slate-500 mt-0.5">Daftarkan modul fitur baru ke proyek terkait.</p>
            </div>
            <button type="button" class="btn-close-modal p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Form Tambah Modul --}}
        <form method="POST" action="{{ route('modules.store') }}" class="p-6 space-y-4">
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

            {{-- Nama Modul --}}
            <div>
                <label for="create-name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Nama Modul <span class="text-rose-600">*</span>
                </label>
                <input
                    type="text"
                    id="create-name"
                    name="name"
                    required
                    maxlength="150"
                    placeholder="Contoh: Autentikasi Pengguna & SSO"
                    class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900"
                >
            </div>

            {{-- Deskripsi Modul --}}
            <div>
                <label for="create-description" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Deskripsi Cakupan Modul (Opsional)
                </label>
                <textarea
                    id="create-description"
                    name="description"
                    rows="3"
                    placeholder="Jelaskan ruang lingkup fitur yang dicakup oleh modul ini..."
                    class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900"
                ></textarea>
            </div>

            {{-- Tombol Aksi Modal --}}
            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-200">
                <button type="button" class="btn-close-modal px-4 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm font-semibold text-slate-700 hover:bg-slate-50 cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-lg bg-indigo-600 text-xs sm:text-sm font-semibold text-white hover:bg-indigo-700 transition cursor-pointer shadow-xs">
                    Simpan Modul
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL 2: EDIT MODUL (Single Screen, Clean & Tegas) --}}
<div
    id="modal-edit-module"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden"
    aria-modal="true"
    role="dialog"
>
    <div class="bg-white rounded-2xl shadow-xl border border-slate-200 w-full max-w-lg overflow-hidden">
        {{-- Modal Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
            <div>
                <h3 class="text-base font-bold text-slate-900">Edit Data Modul</h3>
                <p class="text-xs text-slate-500 mt-0.5">Perbarui nama, proyek induk, atau deskripsi modul.</p>
            </div>
            <button type="button" class="btn-close-modal p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Form Edit Modul --}}
        <form id="form-edit-module" method="POST" action="" class="p-6 space-y-4">
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

            {{-- Nama Modul --}}
            <div>
                <label for="edit-name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Nama Modul <span class="text-rose-600">*</span>
                </label>
                <input
                    type="text"
                    id="edit-name"
                    name="name"
                    required
                    maxlength="150"
                    class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900"
                >
            </div>

            {{-- Deskripsi Modul --}}
            <div>
                <label for="edit-description" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Deskripsi Cakupan Modul (Opsional)
                </label>
                <textarea
                    id="edit-description"
                    name="description"
                    rows="3"
                    class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900"
                ></textarea>
            </div>

            {{-- Tombol Aksi Modal --}}
            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-200">
                <button type="button" class="btn-close-modal px-4 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm font-semibold text-slate-700 hover:bg-slate-50 cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-lg bg-indigo-600 text-xs sm:text-sm font-semibold text-white hover:bg-indigo-700 transition cursor-pointer shadow-xs">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

{{-- MODAL 3: HAPUS MODUL (Konfirmasi Tegas) --}}
<div
    id="modal-delete-module"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden"
    aria-modal="true"
    role="dialog"
>
    <div class="bg-white rounded-2xl shadow-xl border border-slate-200 w-full max-w-md overflow-hidden">
        <div class="p-6">
            <div class="w-12 h-12 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                </svg>
            </div>

            <h3 class="text-base font-bold text-slate-900">Konfirmasi Hapus Modul</h3>
            <p class="text-xs text-slate-600 mt-1">
                Apakah Anda yakin ingin menghapus modul <span id="delete-module-name" class="font-bold text-slate-900"></span> (<span id="delete-module-project" class="text-indigo-600 font-semibold"></span>)?
            </p>

            <div class="mt-3 p-3 rounded-lg bg-slate-50 border border-slate-200 text-xs text-slate-600">
                <p class="font-semibold text-slate-800 mb-0.5">Informasi Penghapusan:</p>
                <p>Tugas dan temuan bug yang terhubung dengan modul ini akan tetap tersimpan aman, namun status modulnya akan dikosongkan.</p>
            </div>

            <form id="form-delete-module" method="POST" action="" class="mt-5 flex items-center justify-end gap-3">
                @csrf
                @method('DELETE')
                <button
                    type="button"
                    class="btn-close-modal px-4 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm font-semibold text-slate-700 hover:bg-slate-50 cursor-pointer"
                >
                    Batal
                </button>
                <button
                    type="submit"
                    class="px-4 py-2 rounded-lg bg-rose-600 hover:bg-rose-700 text-white text-xs sm:text-sm font-semibold transition shadow-xs cursor-pointer focus:ring-2 focus:ring-rose-500"
                >
                    Hapus Modul
                </button>
            </form>
        </div>
    </div>
</div>

{{-- Vanilla JS untuk Kontrol Sidebar & Modal Interaktif --}}
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

        // 2. KONTROL MODAL TAMBAH MODUL
        const modalCreate = document.getElementById('modal-create-module');
        const btnOpenCreate = document.getElementById('btn-open-create-module');

        btnOpenCreate?.addEventListener('click', () => {
            modalCreate?.classList.remove('hidden');
        });

        // 3. KONTROL MODAL EDIT MODUL
        const modalEdit = document.getElementById('modal-edit-module');
        const formEdit = document.getElementById('form-edit-module');

        document.querySelectorAll('.btn-edit-module').forEach(btn => {
            btn.addEventListener('click', () => {
                const data = JSON.parse(btn.dataset.module);
                formEdit.action = `/modules/${data.id}`;

                document.getElementById('edit-project-id').value = data.project_id || '';
                document.getElementById('edit-name').value = data.name || '';
                document.getElementById('edit-description').value = data.description || '';

                modalEdit?.classList.remove('hidden');
            });
        });

        // 4. KONTROL MODAL HAPUS MODUL
        const modalDelete = document.getElementById('modal-delete-module');
        const formDelete = document.getElementById('form-delete-module');
        const deleteName = document.getElementById('delete-module-name');
        const deleteProject = document.getElementById('delete-module-project');

        document.querySelectorAll('.btn-delete-module').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                const name = btn.dataset.name;
                const project = btn.dataset.project;

                formDelete.action = `/modules/${id}`;
                deleteName.textContent = name;
                deleteProject.textContent = project;
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
