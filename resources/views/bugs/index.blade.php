<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Pelacak Bug &mdash; {{ config('app.name', 'Aplikasi Bug Tracker') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.speed-meta')
    <style>
        /* Tampilan Responsif Pelacak Bug: Kartu (Setengah Layar & Mobile < 1024px) vs Tabel (Fullscreen Desktop >= 1024px) */
        @media (max-width: 1023px) {
            .bug-card-view-mobile {
                display: block !important;
            }
            .bug-table-view-desktop {
                display: none !important;
            }
        }
        @media (min-width: 1024px) {
            .bug-card-view-mobile {
                display: none !important;
            }
            .bug-table-view-desktop {
                display: block !important;
            }
        }
    </style>
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
        class="fixed inset-y-0 left-0 z-50 flex w-64 sm:w-68 -translate-x-full flex-col justify-between border-r border-slate-800 bg-slate-900 text-slate-100 transition-transform duration-200 ease-in-out lg:sticky lg:inset-auto lg:top-0 lg:h-screen lg:translate-x-0 shrink-0 shadow-lg lg:shadow-none"
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

                    {{-- Fitur Pelacak Bug (Active State) --}}
                    <a
                        href="{{ route('bugs.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3.5 py-2.5 text-sm font-semibold transition bg-slate-800 text-white border border-slate-700/70 shadow-xs"
                    >
                        <svg class="w-5 h-5 text-rose-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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

        {{-- Konten Halaman Pelacak Bug --}}
        <main class="flex-1 p-4 sm:p-5 lg:p-6 max-w-full xl:max-w-7xl w-full mx-auto flex flex-col">

            {{-- Header Halaman & Tombol Aksi Laporkan Bug --}}
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">Pelacak Bug</h1>
                    <p class="mt-1 text-sm text-slate-600">Pantau temuan masalah, analisis akar masalah, serta kelola solusi penyelesaian bug secara terpisah dan terstruktur.</p>
                </div>

                <div class="flex items-center gap-3 shrink-0">
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

                    {{-- Tombol Buka Pop-up Tambah Bug --}}
                    <button
                        type="button"
                        id="btn-open-create-bug"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 focus:ring-2 focus:ring-slate-900 focus:ring-offset-2 shrink-0 cursor-pointer shadow-sm"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                        </svg>
                        <span>Laporkan Bug</span>
                    </button>
                </div>
            </div>

            {{-- Ringkasan Metrik Cepat (Bersih & Tegas, Ramah Desktop & Tablet) --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3.5 mb-6">
                {{-- Total Bug --}}
                <div class="bg-white border border-slate-200 rounded-xl p-3.5 shadow-2xs">
                    <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Total Bug</span>
                    <span class="mt-1 block text-2xl font-extrabold text-slate-900">{{ $metrics['total'] }}</span>
                </div>

                {{-- Open --}}
                <div class="bg-white border border-rose-200/80 rounded-xl p-3.5 shadow-2xs bg-rose-50/20">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-rose-500"></span>
                        <span class="text-xs font-bold text-rose-700 uppercase tracking-wider">Open</span>
                    </div>
                    <span class="mt-1 block text-2xl font-extrabold text-rose-800">{{ $metrics['open'] }}</span>
                </div>

                {{-- In Progress --}}
                <div class="bg-white border border-sky-200/80 rounded-xl p-3.5 shadow-2xs bg-sky-50/20">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-sky-500"></span>
                        <span class="text-xs font-bold text-sky-700 uppercase tracking-wider">In Progress</span>
                    </div>
                    <span class="mt-1 block text-2xl font-extrabold text-sky-800">{{ $metrics['in_progress'] }}</span>
                </div>

                {{-- Resolved --}}
                <div class="bg-white border border-emerald-200/80 rounded-xl p-3.5 shadow-2xs bg-emerald-50/20">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Resolved</span>
                    </div>
                    <span class="mt-1 block text-2xl font-extrabold text-emerald-800">{{ $metrics['resolved'] }}</span>
                </div>

                {{-- Kritis / Critical --}}
                <div class="bg-white border border-purple-200/80 rounded-xl p-3.5 shadow-2xs bg-purple-50/20 col-span-2 sm:col-span-1">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-rose-600"></span>
                        <span class="text-xs font-bold text-purple-700 uppercase tracking-wider">Kritis</span>
                    </div>
                    <span class="mt-1 block text-2xl font-extrabold text-purple-800">{{ $metrics['critical'] }}</span>
                </div>
            </div>

            {{-- Pesan Sukses / Notifikasi Flash --}}
            @if (session('success_message'))
                <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 flex items-center justify-between">
                    <span>{{ session('success_message') }}</span>
                </div>
            @endif

            <div id="toast-message" class="hidden mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-xs sm:text-sm font-medium text-emerald-800 items-center justify-between transition-all">
                <span id="toast-text">Data bug berhasil diperbarui.</span>
            </div>

            {{-- Filter & Pencarian Sederhana (Tanpa Tab Bersarang) --}}
            <div class="bg-white border border-slate-200 rounded-xl p-4 mb-6 shadow-2xs">
                <form method="GET" action="{{ route('bugs.index') }}" class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3 flex-wrap">
                    {{-- Pencarian Kata Kunci --}}
                    <div class="flex-1 min-w-[220px]">
                        <label for="search-bug" class="sr-only">Cari Bug</label>
                        <div class="relative">
                            <input
                                type="text"
                                id="search-bug"
                                name="search"
                                value="{{ $selectedSearch }}"
                                placeholder="Cari judul, pelapor, penyebab, solusi..."
                                class="w-full rounded-lg border border-slate-300 py-2 pl-9 pr-3.5 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900"
                            >
                            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                            </svg>
                        </div>
                    </div>

                    {{-- Dropdown Filter Proyek --}}
                    <div>
                        <label for="filter-bug-project" class="sr-only">Filter Proyek</label>
                        <select
                            id="filter-bug-project"
                            name="project_id"
                            class="w-full md:w-auto rounded-lg border border-slate-300 bg-white py-2 pl-3 pr-8 text-xs sm:text-sm font-medium text-slate-700 shadow-2xs focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 cursor-pointer"
                        >
                            <option value="">Semua Proyek</option>
                            @foreach ($projects as $proj)
                                <option value="{{ $proj->id }}" {{ (string)$selectedProject === (string)$proj->id ? 'selected' : '' }}>
                                    [{{ $proj->code }}] {{ $proj->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Dropdown Filter Tingkat Keparahan --}}
                    <div>
                        <label for="filter-bug-severity" class="sr-only">Filter Keparahan</label>
                        <select
                            id="filter-bug-severity"
                            name="severity"
                            class="w-full md:w-auto rounded-lg border border-slate-300 bg-white py-2 pl-3 pr-8 text-xs sm:text-sm font-medium text-slate-700 shadow-2xs focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 cursor-pointer"
                        >
                            <option value="">Semua Keparahan</option>
                            <option value="critical" {{ $selectedSeverity === 'critical' ? 'selected' : '' }}>Kritis (Critical)</option>
                            <option value="high" {{ $selectedSeverity === 'high' ? 'selected' : '' }}>Tinggi (High)</option>
                            <option value="medium" {{ $selectedSeverity === 'medium' ? 'selected' : '' }}>Sedang (Medium)</option>
                            <option value="low" {{ $selectedSeverity === 'low' ? 'selected' : '' }}>Rendah (Low)</option>
                        </select>
                    </div>

                    {{-- Dropdown Filter Status --}}
                    <div>
                        <label for="filter-bug-status" class="sr-only">Filter Status</label>
                        <select
                            id="filter-bug-status"
                            name="status"
                            class="w-full md:w-auto rounded-lg border border-slate-300 bg-white py-2 pl-3 pr-8 text-xs sm:text-sm font-medium text-slate-700 shadow-2xs focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 cursor-pointer"
                        >
                            <option value="">Semua Status</option>
                            <option value="open" {{ $selectedStatus === 'open' ? 'selected' : '' }}>Open</option>
                            <option value="in_progress" {{ $selectedStatus === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="resolved" {{ $selectedStatus === 'resolved' ? 'selected' : '' }}>Resolved</option>
                        </select>
                    </div>

                    {{-- Dropdown Filter Penanggung Jawab --}}
                    <div>
                        <label for="filter-bug-dev" class="sr-only">Filter Penanggung Jawab</label>
                        <select
                            id="filter-bug-dev"
                            name="assigned_to"
                            class="w-full md:w-auto rounded-lg border border-slate-300 bg-white py-2 pl-3 pr-8 text-xs sm:text-sm font-medium text-slate-700 shadow-2xs focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 cursor-pointer"
                        >
                            <option value="">Semua Penanggung Jawab</option>
                            @foreach ($developers as $dev)
                                <option value="{{ $dev->id }}" {{ (string)$selectedDeveloper === (string)$dev->id ? 'selected' : '' }}>
                                    {{ $dev->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tombol Terapkan & Reset --}}
                    <div class="flex items-center gap-2">
                        <button
                            type="submit"
                            class="px-3.5 py-2 rounded-lg bg-slate-900 text-white text-xs sm:text-sm font-semibold hover:bg-slate-800 transition cursor-pointer shadow-xs"
                        >
                            Filter
                        </button>
                        @if ($selectedSearch || $selectedProject || $selectedSeverity || $selectedStatus || $selectedDeveloper)
                            <a
                                href="{{ route('bugs.index') }}"
                                class="px-3 py-2 rounded-lg border border-slate-200 bg-white text-slate-600 text-xs sm:text-sm font-semibold hover:bg-slate-100 hover:text-slate-900 transition"
                            >
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Tabel & Kartu Pelacak Bug (Ramah Desktop, Tablet, & Mode Setengah Layar / Mobile) --}}
            <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-2xs">
                @php
                    $severityBadges = [
                        'critical' => 'bg-rose-100 text-rose-800 border-rose-300 font-bold',
                        'high' => 'bg-orange-100 text-orange-800 border-orange-300 font-semibold',
                        'medium' => 'bg-amber-100 text-amber-800 border-amber-300 font-medium',
                        'low' => 'bg-slate-100 text-slate-700 border-slate-300 font-medium',
                    ];
                    $severityLabels = [
                        'critical' => 'Kritis',
                        'high' => 'Tinggi',
                        'medium' => 'Sedang',
                        'low' => 'Rendah',
                    ];
                    $statusBadges = [
                        'open' => 'bg-rose-50 text-rose-700 border-rose-200',
                        'in_progress' => 'bg-sky-50 text-sky-700 border-sky-200',
                        'resolved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                    ];
                    $statusDots = [
                        'open' => 'bg-rose-500',
                        'in_progress' => 'bg-sky-500',
                        'resolved' => 'bg-emerald-500',
                    ];
                    $statusLabels = [
                        'open' => 'Open',
                        'in_progress' => 'In Progress',
                        'resolved' => 'Resolved',
                    ];
                @endphp

                {{-- TAMPILAN 1: MODE MOBILE & SETENGAH LAYAR (KARTU RESPONSIF, TANPA SCROLL SAMPING, AKSI BERSATU) --}}
                <div class="bug-card-view-mobile lg:hidden divide-y divide-slate-100 bg-white">
                    @forelse ($bugs as $bug)
                        @php
                            $bugData = [
                                'id' => $bug->id,
                                'title' => $bug->title,
                                'project_id' => $bug->project_id,
                                'project_code' => $bug->project?->code ?? 'UMUM',
                                'project_name' => $bug->project?->name ?? 'Proyek Tanpa Nama',
                                'project_color' => $bug->project?->color ?? '#4F46E5',
                                'module_id' => $bug->module_id,
                                'module_name' => $bug->module?->name ?? 'Umum / Tanpa Modul',
                                'assigned_to' => $bug->assigned_to,
                                'assignee_name' => $bug->assignee?->name ?? 'Belum Ditugaskan',
                                'assignee_avatar' => $bug->assignee?->avatar ?? 'AK',
                                'assignee_avatar_bg' => $bug->assignee?->avatar_bg ?? '#1E293B',
                                'creator_name' => $bug->creator?->name ?? 'Sistem',
                                'severity' => $bug->severity,
                                'severity_label' => $severityLabels[$bug->severity] ?? $bug->severity,
                                'status' => $bug->status,
                                'status_label' => $statusLabels[$bug->status] ?? $bug->status,
                                'reported_by' => $bug->reported_by ?: '-',
                                'reported_date' => $bug->reported_date ? $bug->reported_date->format('d M Y') : '-',
                                'reported_date_raw' => $bug->reported_date ? $bug->reported_date->format('Y-m-d') : '',
                                'description' => $bug->description,
                                'root_cause' => $bug->root_cause,
                                'solution_notes' => $bug->solution_notes,
                                'resolved_at' => $bug->resolved_at ? $bug->resolved_at->format('d M Y, H:i') : null,
                                'created_at' => $bug->created_at ? $bug->created_at->format('d M Y, H:i') : '-',
                                'handover_logs' => $bug->handover_logs ?? [],
                            ];
                        @endphp
                        <div class="p-4 hover:bg-slate-50/60 transition-colors" id="card-bug-{{ $bug->id }}">
                            {{-- Header Kartu: Judul Bug & Badge Status/Keparahan --}}
                            <div class="flex items-start justify-between gap-2.5">
                                <div class="min-w-0 flex-1">
                                    <button
                                        type="button"
                                        class="btn-detail-bug text-left font-bold text-sm text-slate-900 hover:text-indigo-600 transition cursor-pointer leading-snug group flex items-start gap-1"
                                        data-bug="{{ json_encode($bugData) }}"
                                        title="Klik untuk melihat dan mengedit detail bug"
                                    >
                                        <span class="break-words">{{ $bug->title }}</span>
                                        <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-indigo-600 shrink-0 mt-0.5 transition-colors" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                        </svg>
                                    </button>

                                    {{-- Proyek & Modul --}}
                                    <div class="mt-1.5 flex flex-wrap items-center gap-1.5">
                                        @if ($bug->project)
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-800 border border-slate-200">
                                                <span class="w-1.5 h-1.5 rounded-full shrink-0" style="background-color: {{ $bug->project->color ?? '#4F46E5' }}"></span>
                                                <span>{{ $bug->project->code }}</span>
                                            </span>
                                        @endif
                                        @if ($bug->module)
                                            <span class="text-xs text-slate-600 font-medium">
                                                {{ $bug->module->name }}
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Keparahan & Status --}}
                                <div class="flex flex-col items-end gap-1 shrink-0">
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider border {{ $severityBadges[$bug->severity] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}">
                                        {{ $severityLabels[$bug->severity] ?? $bug->severity }}
                                    </span>
                                    <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-semibold border {{ $statusBadges[$bug->status] ?? 'bg-slate-50 text-slate-700 border-slate-200' }}">
                                        <span class="w-1.5 h-1.5 rounded-full {{ $statusDots[$bug->status] ?? 'bg-slate-400' }}"></span>
                                        <span>{{ $statusLabels[$bug->status] ?? $bug->status }}</span>
                                    </span>
                                </div>
                            </div>

                            {{-- Deskripsi --}}
                            @if ($bug->description)
                                <p class="text-xs text-slate-500 mt-2 line-clamp-2 leading-relaxed">
                                    {{ \Illuminate\Support\Str::limit($bug->description, 130) }}
                                </p>
                            @endif

                            {{-- Badge Solusi & Akar Masalah --}}
                            @if ($bug->solution_notes || $bug->root_cause)
                                <div class="mt-2 flex flex-wrap gap-1.5">
                                    @if ($bug->solution_notes)
                                        <div class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                            <svg class="w-3 h-3 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                            </svg>
                                            <span>Solusi:</span>
                                            <span class="truncate max-w-[200px]" title="{{ $bug->solution_notes }}">{{ $bug->solution_notes }}</span>
                                        </div>
                                    @endif
                                    @if ($bug->root_cause)
                                        <div class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                            <span>Akar Masalah:</span>
                                            <span class="truncate max-w-[200px]" title="{{ $bug->root_cause }}">{{ $bug->root_cause }}</span>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            {{-- Info Pelapor & Penanggung Jawab --}}
                            <div class="mt-3 pt-2.5 border-t border-slate-100 flex flex-wrap items-center justify-between gap-2 text-xs">
                                <div class="flex items-center gap-1.5 text-slate-600">
                                    <span class="text-[11px] text-slate-400">Ditugaskan:</span>
                                    @if ($bug->assignee)
                                        <div class="flex items-center gap-1.5 font-semibold text-slate-800">
                                            @if ($bug->assignee->foto_profil_url)
                                                <img src="{{ $bug->assignee->foto_profil_url }}" alt="{{ $bug->assignee->name }}" class="w-5 h-5 rounded-full object-cover border border-slate-200">
                                            @else
                                                <div class="w-5 h-5 rounded-full flex items-center justify-center text-[9px] font-bold text-white shadow-2xs" style="background-color: {{ $bug->assignee->avatar_bg ?? '#1E293B' }}">
                                                    {{ $bug->assignee->avatar ?? 'AK' }}
                                                </div>
                                            @endif
                                            <span>{{ $bug->assignee->name }}</span>
                                            @if ((int)$bug->assigned_to === (int)$user->id)
                                                <span class="text-[10px] font-bold text-indigo-600">(Anda)</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400 italic">Belum Ditugaskan</span>
                                    @endif
                                </div>
                                <div class="text-[11px] text-slate-400">
                                    {{ $bug->reported_by ?: '-' }} • {{ $bug->reported_date ? $bug->reported_date->format('d M Y') : '-' }}
                                </div>
                            </div>

                            {{-- Tombol Aksi di Kartu: Bersatu, Tanpa Terpisah Garis, Langsung Terlihat --}}
                            <div class="mt-3 pt-2 flex flex-wrap items-center justify-end gap-1.5">
                                {{-- Tombol Detail / Edit --}}
                                <button
                                    type="button"
                                    class="btn-detail-bug inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg border border-slate-300 bg-white text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition cursor-pointer shadow-2xs shrink-0"
                                    data-bug="{{ json_encode($bugData) }}"
                                    title="Lihat riwayat dan edit detail bug"
                                >
                                    <svg class="w-3.5 h-3.5 text-slate-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                    </svg>
                                    <span>Detail</span>
                                </button>

                                {{-- Tombol Solusi --}}
                                <button
                                    type="button"
                                    class="btn-solution-bug inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg border {{ $bug->solution_notes ? 'border-emerald-300 bg-emerald-50 text-emerald-800 hover:bg-emerald-100' : 'border-amber-300 bg-amber-50 text-amber-800 hover:bg-amber-100' }} text-xs font-semibold transition cursor-pointer shadow-2xs shrink-0"
                                    data-bug="{{ json_encode($bugData) }}"
                                    title="Kelola dan lihat solusi penyelesaian bug"
                                >
                                    <svg class="w-3.5 h-3.5 {{ $bug->solution_notes ? 'text-emerald-600' : 'text-amber-600' }} shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <span>Solusi</span>
                                </button>

                                {{-- Tombol Hapus Bug --}}
                                <button
                                    type="button"
                                    class="btn-delete-bug p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer shrink-0"
                                    data-bug-id="{{ $bug->id }}"
                                    data-bug-title="{{ $bug->title }}"
                                    data-delete-url="{{ route('bugs.destroy', $bug) }}"
                                    title="Hapus laporan bug"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center px-4">
                            <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400 mb-3">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.002A11.959 11.959 0 0112 2.714zm0 13.036a.75.75 0 100-1.5.75.75 0 000 1.5z" />
                                </svg>
                            </div>
                            <h3 class="text-sm font-bold text-slate-900">Belum Ada Laporan Bug</h3>
                            <p class="mt-1 text-xs text-slate-500 max-w-sm mx-auto">
                                Tidak ada data bug yang sesuai dengan kriteria filter saat ini. Silakan buat laporan bug baru atau sesuaikan filter.
                            </p>
                        </div>
                    @endforelse
                </div>

                {{-- TAMPILAN 2: MODE DESKTOP LENGKAP (TABEL DENGAN AKSI BERSATU, TANPA GARIS PEMISAH MENCOLOK) --}}
                <div class="bug-table-view-desktop hidden lg:block overflow-x-auto w-full relative">
                    <table class="w-full text-left text-sm border-separate border-spacing-0">
                        <thead class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 select-none">
                            <tr>
                                <th scope="col" class="py-3 pl-4 pr-2.5 border-b border-slate-200">Bug &amp; Temuan</th>
                                <th scope="col" class="py-3 px-2 border-b border-slate-200">Proyek &amp; Modul</th>
                                <th scope="col" class="py-3 px-1.5 border-b border-slate-200">Keparahan</th>
                                <th scope="col" class="py-3 px-1.5 border-b border-slate-200">Status</th>
                                <th scope="col" class="py-3 px-2 border-b border-slate-200">Pelapor &amp; Tanggal</th>
                                <th scope="col" class="py-3 px-2 border-b border-slate-200">Ditugaskan</th>
                                <th scope="col" class="py-3 pl-2.5 pr-4 text-right sticky right-0 bg-slate-50 z-20 border-b border-slate-200">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            @forelse ($bugs as $bug)
                                @php
                                    $bugData = [
                                        'id' => $bug->id,
                                        'title' => $bug->title,
                                        'project_id' => $bug->project_id,
                                        'project_code' => $bug->project?->code ?? 'UMUM',
                                        'project_name' => $bug->project?->name ?? 'Proyek Tanpa Nama',
                                        'project_color' => $bug->project?->color ?? '#4F46E5',
                                        'module_id' => $bug->module_id,
                                        'module_name' => $bug->module?->name ?? 'Umum / Tanpa Modul',
                                        'assigned_to' => $bug->assigned_to,
                                        'assignee_name' => $bug->assignee?->name ?? 'Belum Ditugaskan',
                                        'assignee_avatar' => $bug->assignee?->avatar ?? 'AK',
                                        'assignee_avatar_bg' => $bug->assignee?->avatar_bg ?? '#1E293B',
                                        'creator_name' => $bug->creator?->name ?? 'Sistem',
                                        'severity' => $bug->severity,
                                        'severity_label' => $severityLabels[$bug->severity] ?? $bug->severity,
                                        'status' => $bug->status,
                                        'status_label' => $statusLabels[$bug->status] ?? $bug->status,
                                        'reported_by' => $bug->reported_by ?: '-',
                                        'reported_date' => $bug->reported_date ? $bug->reported_date->format('d M Y') : '-',
                                        'reported_date_raw' => $bug->reported_date ? $bug->reported_date->format('Y-m-d') : '',
                                        'description' => $bug->description,
                                        'root_cause' => $bug->root_cause,
                                        'solution_notes' => $bug->solution_notes,
                                        'resolved_at' => $bug->resolved_at ? $bug->resolved_at->format('d M Y, H:i') : null,
                                        'created_at' => $bug->created_at ? $bug->created_at->format('d M Y, H:i') : '-',
                                        'handover_logs' => $bug->handover_logs ?? [],
                                    ];
                                @endphp
                                <tr class="hover:bg-slate-50/70 transition-colors group" id="row-bug-{{ $bug->id }}">
                                    {{-- 1. Judul, Deskripsi & Solusi Bug --}}
                                    <td class="py-3 pl-4 pr-2.5 max-w-[200px] lg:max-w-[240px] xl:max-w-xs border-b border-slate-100">
                                        <button
                                            type="button"
                                            class="btn-detail-bug text-left font-bold text-slate-900 hover:text-indigo-600 transition cursor-pointer leading-snug group/btn flex items-start gap-1"
                                            data-bug="{{ json_encode($bugData) }}"
                                            title="Klik untuk melihat dan mengedit detail bug"
                                        >
                                            <span class="break-words">{{ $bug->title }}</span>
                                            <svg class="w-3.5 h-3.5 text-slate-400 group-hover/btn:text-indigo-600 shrink-0 mt-0.5 opacity-0 group-hover/btn:opacity-100 transition-opacity" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
                                            </svg>
                                        </button>
                                        @if ($bug->description)
                                            <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed" title="{{ $bug->description }}">
                                                {{ \Illuminate\Support\Str::limit($bug->description, 110) }}
                                            </p>
                                        @else
                                            <p class="text-xs text-slate-400 italic mt-0.5">Tanpa deskripsi</p>
                                        @endif

                                        {{-- Badge Solusi & Akar Masalah --}}
                                        <div class="mt-1.5 flex flex-wrap gap-1.5">
                                            @if ($bug->solution_notes)
                                                <div class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                                    <svg class="w-3 h-3 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                                    </svg>
                                                    <span>Solusi:</span>
                                                    <span class="truncate max-w-[170px]" title="{{ $bug->solution_notes }}">{{ $bug->solution_notes }}</span>
                                                </div>
                                            @endif
                                            @if ($bug->root_cause)
                                                <div class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                                    <span>Akar Masalah:</span>
                                                    <span class="truncate max-w-[170px]" title="{{ $bug->root_cause }}">{{ $bug->root_cause }}</span>
                                                </div>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- 2. Proyek & Modul (Disatukan) --}}
                                    <td class="py-3 px-2 whitespace-nowrap border-b border-slate-100">
                                        <div class="flex flex-col items-start gap-1">
                                            @if ($bug->project)
                                                <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-xs font-semibold bg-slate-100 text-slate-800 border border-slate-200">
                                                    <span class="w-2 h-2 rounded-full shrink-0" style="background-color: {{ $bug->project->color ?? '#4F46E5' }}"></span>
                                                    <span>{{ $bug->project->code }}</span>
                                                </span>
                                            @endif
                                            @if ($bug->module)
                                                <span class="text-xs text-slate-600 font-medium truncate max-w-[130px]" title="{{ $bug->module->name }}">
                                                    {{ $bug->module->name }}
                                                </span>
                                            @elseif (!$bug->project)
                                                <span class="text-xs text-slate-400 italic">-</span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- 3. Tingkat Keparahan --}}
                                    <td class="py-3 px-1.5 whitespace-nowrap border-b border-slate-100">
                                        <span class="px-2 py-0.5 rounded text-[11px] uppercase tracking-wider border {{ $severityBadges[$bug->severity] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}">
                                            {{ $severityLabels[$bug->severity] ?? $bug->severity }}
                                        </span>
                                    </td>

                                    {{-- 4. Status --}}
                                    <td class="py-3 px-1.5 whitespace-nowrap border-b border-slate-100">
                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-xs font-semibold border {{ $statusBadges[$bug->status] ?? 'bg-slate-50 text-slate-700 border-slate-200' }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $statusDots[$bug->status] ?? 'bg-slate-400' }}"></span>
                                            <span>{{ $statusLabels[$bug->status] ?? $bug->status }}</span>
                                        </span>
                                    </td>

                                    {{-- 5. Pelapor & Tanggal --}}
                                    <td class="py-3 px-2 whitespace-nowrap text-xs text-slate-600 border-b border-slate-100">
                                        <div class="font-medium text-slate-800 truncate max-w-[130px]" title="{{ $bug->reported_by }}">{{ $bug->reported_by ?: '-' }}</div>
                                        <div class="text-[11px] text-slate-400 mt-0.5">
                                            {{ $bug->reported_date ? $bug->reported_date->format('d M Y') : '-' }}
                                        </div>
                                    </td>

                                    {{-- 6. Ditugaskan Kepada --}}
                                    <td class="py-3 px-2 whitespace-nowrap border-b border-slate-100">
                                        <div class="flex items-center gap-1.5">
                                            @if ($bug->assignee)
                                                @if ($bug->assignee->foto_profil_url)
                                                    <img src="{{ $bug->assignee->foto_profil_url }}" alt="{{ $bug->assignee->name }}" class="w-6 h-6 rounded-full object-cover border border-slate-200 shrink-0 shadow-2xs">
                                                @else
                                                    <div class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-bold text-white shrink-0 shadow-2xs" style="background-color: {{ $bug->assignee->avatar_bg ?? '#1E293B' }}">
                                                        {{ $bug->assignee->avatar ?? 'AK' }}
                                                    </div>
                                                @endif
                                                <span class="text-xs font-semibold text-slate-800 truncate max-w-[110px]" title="{{ $bug->assignee->name }}">
                                                    {{ $bug->assignee->name }}
                                                </span>
                                                @if ((int)$bug->assigned_to === (int)$user->id)
                                                    <span class="text-[10px] font-bold text-indigo-600">(Anda)</span>
                                                @endif
                                            @else
                                                <span class="text-xs text-slate-400 italic">Belum Ditugaskan</span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- 7. Aksi (Bersatu Tanpa Garis Pemisah: Detail, Solusi, Hapus) --}}
                                    <td class="py-3 pl-2.5 pr-4 text-right whitespace-nowrap sticky right-0 bg-white group-hover:bg-slate-50 transition-colors z-10 border-b border-slate-100">
                                        <div class="flex items-center justify-end gap-1.5 shrink-0">
                                            {{-- 1. Tombol Detail (Bisa Melihat & Mengedit Detail Bug) --}}
                                            <button
                                                type="button"
                                                class="btn-detail-bug inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg border border-slate-300 bg-white text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition cursor-pointer shadow-2xs shrink-0"
                                                data-bug="{{ json_encode($bugData) }}"
                                                title="Lihat riwayat dan edit detail bug"
                                            >
                                                <svg class="w-3.5 h-3.5 text-slate-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                                                </svg>
                                                <span>Detail</span>
                                            </button>

                                            {{-- 2. Tombol Solusi (Khusus Kelola & Catat Solusi) --}}
                                            <button
                                                type="button"
                                                class="btn-solution-bug inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded-lg border {{ $bug->solution_notes ? 'border-emerald-300 bg-emerald-50 text-emerald-800 hover:bg-emerald-100' : 'border-amber-300 bg-amber-50 text-amber-800 hover:bg-amber-100' }} text-xs font-semibold transition cursor-pointer shadow-2xs shrink-0"
                                                data-bug="{{ json_encode($bugData) }}"
                                                title="Kelola dan lihat solusi penyelesaian bug"
                                            >
                                                <svg class="w-3.5 h-3.5 {{ $bug->solution_notes ? 'text-emerald-600' : 'text-amber-600' }} shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                <span>Solusi</span>
                                            </button>

                                            {{-- 3. Tombol Hapus Bug --}}
                                            <button
                                                type="button"
                                                class="btn-delete-bug p-1.5 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition cursor-pointer shrink-0"
                                                data-bug-id="{{ $bug->id }}"
                                                data-bug-title="{{ $bug->title }}"
                                                data-delete-url="{{ route('bugs.destroy', $bug) }}"
                                                title="Hapus laporan bug"
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
                                    <td colspan="7" class="py-12 text-center border-b border-slate-100">
                                        <div class="mx-auto flex h-12 w-12 items-center justify-center rounded-full bg-slate-100 text-slate-400 mb-3">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.002A11.959 11.959 0 0112 2.714zm0 13.036a.75.75 0 100-1.5.75.75 0 000 1.5z" />
                                            </svg>
                                        </div>
                                        <h3 class="text-sm font-bold text-slate-900">Belum Ada Laporan Bug</h3>
                                        <p class="mt-1 text-xs text-slate-500 max-w-sm mx-auto">
                                            Tidak ada data bug yang sesuai dengan kriteria filter saat ini. Silakan buat laporan bug baru atau sesuaikan filter.
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginasi Halaman --}}
                @if ($bugs->hasPages())
                    <div class="px-5 py-4 border-t border-slate-200 bg-slate-50/50">
                        {{ $bugs->links() }}
                    </div>
                @endif
            </div>

        </main>
    </div>

    {{-- ========================================================================= --}}
    {{-- MODAL DETAIL BUG (Bisa Dilihat & Diedit Langsung + Riwayat Aktivitas)      --}}
    {{-- ========================================================================= --}}
    <div id="modal-detail-bug" class="fixed inset-0 z-50 hidden bg-slate-950/60 backdrop-blur-xs overflow-y-auto p-4 sm:p-6 flex items-center justify-center">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xl max-w-2xl w-full p-6 relative max-h-[90vh] flex flex-col">
            {{-- Header Modal --}}
            <div class="flex items-start justify-between pb-4 border-b border-slate-200 shrink-0">
                <div>
                    <h3 class="text-lg sm:text-xl font-bold text-slate-900">Detail &amp; Edit Bug</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Informasi lengkap, riwayat penanganan, serta formulir pembaruan data bug.</p>
                </div>
                <button type="button" class="btn-close-modal p-1.5 rounded-lg text-slate-400 hover:text-slate-900 hover:bg-slate-100 cursor-pointer shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Form Edit Detail Bug --}}
            <form method="POST" id="form-detail-bug" class="flex-1 overflow-y-auto py-5 space-y-4 pr-1 text-sm">
                @csrf
                @method('PUT')

                {{-- Judul Bug --}}
                <div>
                    <label for="detail-edit-title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Judul Bug <span class="text-rose-500">*</span></label>
                    <input type="text" id="detail-edit-title" name="title" required class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900">
                </div>

                {{-- Proyek & Modul --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label for="detail-edit-project" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Proyek <span class="text-rose-500">*</span></label>
                        <select id="detail-edit-project" name="project_id" required class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 cursor-pointer">
                            @foreach ($projects as $proj)
                                <option value="{{ $proj->id }}">[{{ $proj->code }}] {{ $proj->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="detail-edit-module" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Modul</label>
                        <select id="detail-edit-module" name="module_id" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 cursor-pointer">
                            <option value="">Tanpa Modul / Umum</option>
                            @foreach ($modules as $mod)
                                <option value="{{ $mod->id }}" data-project-id="{{ $mod->project_id }}">{{ $mod->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Keparahan & Status --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label for="detail-edit-severity" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Keparahan <span class="text-rose-500">*</span></label>
                        <select id="detail-edit-severity" name="severity" required class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 cursor-pointer">
                            <option value="critical">Kritis (Critical)</option>
                            <option value="high">Tinggi (High)</option>
                            <option value="medium">Sedang (Medium)</option>
                            <option value="low">Rendah (Low)</option>
                        </select>
                    </div>

                    <div>
                        <label for="detail-edit-status" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Status <span class="text-rose-500">*</span></label>
                        <select id="detail-edit-status" name="status" required class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 cursor-pointer">
                            <option value="open">Open</option>
                            <option value="in_progress">In Progress</option>
                            <option value="resolved">Resolved</option>
                        </select>
                    </div>
                </div>

                {{-- Pelapor & Tanggal --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label for="detail-edit-reported-by" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Pelapor</label>
                        <input type="text" id="detail-edit-reported-by" name="reported_by" class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900">
                    </div>

                    <div>
                        <label for="detail-edit-reported-date" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tanggal Laporan</label>
                        <input type="date" id="detail-edit-reported-date" name="reported_date" class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900">
                    </div>
                </div>

                {{-- Penanggung Jawab (Otomatis Sesuai Pelapor/Pembuat Bug) --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Penanggung Jawab</label>
                    <div class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3.5 py-2 text-sm font-semibold text-slate-800 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-slate-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                            </svg>
                            <span id="detail-assigned-name">Belum Ditugaskan</span>
                        </div>
                        <span class="text-[11px] font-medium text-slate-500 bg-slate-200/70 border border-slate-300 px-2 py-0.5 rounded-full">Pelapor Bug</span>
                    </div>
                </div>

                {{-- Deskripsi Permasalahan --}}
                <div>
                    <label for="detail-edit-description" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Deskripsi Permasalahan</label>
                    <textarea id="detail-edit-description" name="description" rows="3" class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900" placeholder="Detail langkah reproduksi bug..."></textarea>
                </div>

                {{-- Riwayat Aktivitas & Handover (Timeline) --}}
                <div class="pt-3 border-t border-slate-200">
                    <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3 flex items-center gap-1.5">
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Riwayat Aktivitas &amp; Pengalihan (Handover)</span>
                    </h4>

                    <div id="detail-timeline-container" class="space-y-3 relative pl-4 before:content-[''] before:absolute before:left-1.5 before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-200">
                        {{-- Diisi secara dinamis oleh JavaScript --}}
                    </div>
                </div>

                {{-- Tombol Aksi di Bawah Form Detail --}}
                <div class="flex items-center justify-between pt-4 border-t border-slate-200 shrink-0">
                    <button
                        type="button"
                        class="btn-close-modal px-4 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm font-semibold text-slate-700 hover:bg-slate-50 cursor-pointer"
                    >
                        Tutup
                    </button>

                    <button
                        type="submit"
                        class="inline-flex items-center gap-1.5 px-5 py-2 rounded-lg bg-slate-900 text-white text-xs sm:text-sm font-semibold hover:bg-slate-800 transition cursor-pointer shadow-xs"
                    >
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                        <span>Simpan Perubahan Detail</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ========================================================================= --}}
    {{-- MODAL SOLUSI BUG (Khusus Melihat & Mengelola Solusi Penyelesaian Bug)     --}}
    {{-- ========================================================================= --}}
    <div id="modal-solution-bug" class="fixed inset-0 z-50 hidden bg-slate-950/60 backdrop-blur-xs overflow-y-auto p-4 sm:p-6 flex items-center justify-center">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xl max-w-lg w-full p-6 relative">
            {{-- Header Modal Solusi --}}
            <div class="flex items-center justify-between pb-4 border-b border-slate-200 mb-4">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900">Solusi Penyelesaian Bug</h3>
                        <p class="text-xs text-slate-500">Kelola akar masalah teknis, catatan solusi perbaikan, dan status penyelesaian.</p>
                    </div>
                </div>
                <button type="button" class="btn-close-modal p-1.5 rounded-lg text-slate-400 hover:text-slate-900 hover:bg-slate-100 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Info Ringkas Bug Terpilih --}}
            <div class="p-3 bg-slate-50 border border-slate-200 rounded-xl mb-4 text-xs space-y-1">
                <div class="flex items-center gap-2">
                    <span id="solution-project-badge" class="font-semibold text-slate-800"></span>
                    <span id="solution-module-badge" class="text-slate-500"></span>
                </div>
                <div id="solution-bug-title" class="font-bold text-slate-900 text-sm leading-snug"></div>
            </div>

            {{-- Form Solusi Bug --}}
            <form method="POST" id="form-solution-bug" class="space-y-4">
                @csrf
                @method('PUT')

                {{-- Status Penyelesaian --}}
                <div>
                    <label for="solution-status" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Status Bug <span class="text-rose-500">*</span></label>
                    <select id="solution-status" name="status" required class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-800 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 cursor-pointer">
                        <option value="resolved">Resolved (Telah Diselesaikan)</option>
                        <option value="in_progress">In Progress (Sedang Dikerjakan)</option>
                        <option value="open">Open (Belum Diselesaikan)</option>
                    </select>
                </div>

                {{-- Akar Masalah (Root Cause) --}}
                <div>
                    <label for="solution-root-cause" class="block text-xs font-bold text-amber-800 uppercase tracking-wider mb-1 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                        </svg>
                        <span>Akar Masalah (Root Cause)</span>
                    </label>
                    <textarea id="solution-root-cause" name="root_cause" rows="2" placeholder="Catatan analisa mengapa bug ini terjadi..." class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-800 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600"></textarea>
                </div>

                {{-- Solusi Penyelesaian (Solution Notes) --}}
                <div class="p-3.5 bg-emerald-50/60 border border-emerald-200 rounded-xl space-y-1.5">
                    <div class="flex items-center justify-between">
                        <label for="solution-notes" class="block text-xs font-bold text-emerald-900 uppercase tracking-wider flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>Dokumentasi Solusi Perbaikan</span>
                        </label>
                        <span id="solution-resolved-at" class="text-[11px] font-semibold text-emerald-700"></span>
                    </div>
                    <textarea id="solution-notes" name="solution_notes" rows="4" placeholder="Tuliskan dokumentasi solusi teknis, kode patch, atau penanganan bug yang diterapkan..." class="w-full rounded-lg border border-emerald-300 bg-white px-3.5 py-2 text-sm text-slate-800 focus:border-emerald-600 focus:ring-1 focus:ring-emerald-600 font-medium"></textarea>
                    <p class="text-[11px] text-emerald-700">Menyimpan dengan status <strong>Resolved</strong> akan mencatat stempel waktu penyelesaian bug.</p>
                </div>

                {{-- Tombol Aksi Modal Solusi --}}
                <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-200">
                    <button type="button" class="btn-close-modal px-4 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm font-semibold text-slate-700 hover:bg-slate-50 cursor-pointer">Batal</button>
                    <button type="submit" class="inline-flex items-center gap-1.5 px-5 py-2 rounded-lg bg-emerald-600 text-xs sm:text-sm font-semibold text-white hover:bg-emerald-700 transition cursor-pointer shadow-xs">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                        </svg>
                        <span>Simpan Solusi</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ========================================================================= --}}
    {{-- MODAL TAMBAH BUG (Clean, Assertive & Simple)                              --}}
    {{-- ========================================================================= --}}
    <div id="modal-create-bug" class="fixed inset-0 z-50 hidden bg-slate-950/60 backdrop-blur-xs overflow-y-auto p-4 sm:p-6 flex items-center justify-center">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xl max-w-lg w-full p-6 relative">
            <div class="flex items-center justify-between pb-4 border-b border-slate-200 mb-5">
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Laporkan Bug Baru</h3>
                    <p class="text-xs text-slate-500 mt-0.5">Catat temuan bug atau kendala teknis pada sistem.</p>
                </div>
                <button type="button" class="btn-close-modal p-1.5 rounded-lg text-slate-400 hover:text-slate-900 hover:bg-slate-100 cursor-pointer">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <form method="POST" action="{{ route('bugs.store') }}" id="form-create-bug" class="space-y-4">
                @csrf
                <div>
                    <label for="create-bug-title" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Judul Bug <span class="text-rose-500">*</span></label>
                    <input type="text" id="create-bug-title" name="title" required placeholder="Contoh: Timeout 504 saat ekspor PDF" class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label for="create-bug-project" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Proyek <span class="text-rose-500">*</span></label>
                        <select id="create-bug-project" name="project_id" required class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 cursor-pointer">
                            <option value="">Pilih Proyek</option>
                            @foreach ($projects as $proj)
                                <option value="{{ $proj->id }}">[{{ $proj->code }}] {{ $proj->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="create-bug-module" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Modul</label>
                        <select id="create-bug-module" name="module_id" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 cursor-pointer">
                            <option value="">Tanpa Modul / Umum</option>
                            @foreach ($modules as $mod)
                                <option value="{{ $mod->id }}" data-project-id="{{ $mod->project_id }}">{{ $mod->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label for="create-bug-severity" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Keparahan <span class="text-rose-500">*</span></label>
                        <select id="create-bug-severity" name="severity" required class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 cursor-pointer">
                            <option value="critical">Kritis (Critical)</option>
                            <option value="high">Tinggi (High)</option>
                            <option value="medium" selected>Sedang (Medium)</option>
                            <option value="low">Rendah (Low)</option>
                        </select>
                    </div>

                    <div>
                        <label for="create-bug-status" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Status <span class="text-rose-500">*</span></label>
                        <select id="create-bug-status" name="status" required class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-sm text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 cursor-pointer">
                            <option value="open" selected>Open</option>
                            <option value="in_progress">In Progress</option>
                            <option value="resolved">Resolved</option>
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label for="create-bug-reported-by" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Pelapor</label>
                        <input type="text" id="create-bug-reported-by" name="reported_by" placeholder="Contoh: QA - Sari" class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900">
                    </div>

                    <div>
                        <label for="create-bug-reported-date" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Tanggal Laporan</label>
                        <input type="date" id="create-bug-reported-date" name="reported_date" value="{{ date('Y-m-d') }}" class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900">
                    </div>
                </div>

                {{-- Penanggung Jawab Otomatis (Ditugaskan ke Pelapor / Open jika Admin) --}}
                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Penanggung Jawab</label>
                    <div class="w-full rounded-lg border border-slate-200 bg-slate-50 px-3.5 py-2 text-sm text-slate-700 flex items-center justify-between">
                        @if ($user->isAdmin())
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-amber-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                                </svg>
                                <span class="font-semibold text-slate-900">Belum Ditugaskan</span>
                                <span class="text-xs text-slate-500 font-normal">(Terbuka untuk Developer)</span>
                            </div>
                            <span class="text-[11px] font-semibold text-amber-700 bg-amber-50 border border-amber-200 px-2 py-0.5 rounded-full">Open</span>
                        @else
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                </svg>
                                <span class="font-semibold text-slate-900">{{ $user->name }}</span>
                                <span class="text-xs text-slate-500 font-normal">(Anda)</span>
                            </div>
                        @endif
                    </div>
                </div>

                <div>
                    <label for="create-bug-description" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Deskripsi &amp; Langkah Reproduksi</label>
                    <textarea id="create-bug-description" name="description" rows="2" placeholder="Uraikan detail masalah atau langkah reproduksi bug..." class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900"></textarea>
                </div>

                <div>
                    <label for="create-bug-root-cause" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">Akar Masalah (Root Cause)</label>
                    <textarea id="create-bug-root-cause" name="root_cause" rows="2" placeholder="Catatan analisa penyebab bug jika telah ditemukan..." class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900"></textarea>
                </div>

                <div>
                    <label for="create-bug-solution-notes" class="block text-xs font-bold text-emerald-800 uppercase tracking-wider mb-1">Solusi Penyelesaian Bug (Opsional)</label>
                    <textarea id="create-bug-solution-notes" name="solution_notes" rows="2" placeholder="Catatan perbaikan teknis jika bug langsung diselesaikan..." class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900"></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 pt-4 border-t border-slate-200">
                    <button type="button" class="btn-close-modal px-4 py-2 rounded-lg border border-slate-300 text-sm font-semibold text-slate-700 hover:bg-slate-50 cursor-pointer">Batal</button>
                    <button type="submit" class="px-5 py-2 rounded-lg bg-slate-900 text-sm font-semibold text-white hover:bg-slate-800 transition cursor-pointer shadow-xs">Simpan Bug</button>
                </div>
            </form>
        </div>
    </div>

    {{-- ========================================================================= --}}
    {{-- MODAL HAPUS BUG                                                           --}}
    {{-- ========================================================================= --}}
    <div id="modal-delete-bug" class="fixed inset-0 z-50 hidden bg-slate-950/60 backdrop-blur-xs p-4 sm:p-6 flex items-center justify-center">
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xl max-w-md w-full p-6 relative">
            <div class="flex items-center gap-3.5 mb-4">
                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-rose-100 text-rose-600 shrink-0">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-base font-bold text-slate-900">Konfirmasi Hapus Bug</h3>
                    <p class="text-xs text-slate-500">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
            </div>

            <p class="text-sm text-slate-600 mb-6 leading-relaxed">
                Apakah Anda yakin ingin menghapus laporan bug <span id="delete-bug-title" class="font-bold text-slate-900"></span>?
            </p>

            <form method="POST" id="form-delete-bug" class="flex items-center justify-end gap-3">
                @csrf
                @method('DELETE')
                <button type="button" class="btn-close-modal px-4 py-2 rounded-lg border border-slate-300 text-sm font-semibold text-slate-700 hover:bg-slate-50 cursor-pointer">Batal</button>
                <button type="submit" class="px-5 py-2 rounded-lg bg-rose-600 text-sm font-semibold text-white hover:bg-rose-700 transition cursor-pointer shadow-xs">Ya, Hapus</button>
            </form>
        </div>
    </div>

    {{-- Script Interaksi Bug Tracker --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Kontrol Sidebar Responsif
            const sidebar = document.getElementById('app-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            const btnOpen = document.getElementById('btn-sidebar-open');
            const btnClose = document.getElementById('btn-sidebar-close');

            const toggleSidebar = (open) => {
                if (open) {
                    sidebar.classList.remove('-translate-x-full');
                    backdrop.classList.remove('hidden');
                } else {
                    sidebar.classList.add('-translate-x-full');
                    backdrop.classList.add('hidden');
                }
            };

            btnOpen?.addEventListener('click', () => toggleSidebar(true));
            btnClose?.addEventListener('click', () => toggleSidebar(false));
            backdrop?.addEventListener('click', () => toggleSidebar(false));

            // Kontrol Modal Tambah Bug
            const modalCreate = document.getElementById('modal-create-bug');
            const btnOpenCreate = document.getElementById('btn-open-create-bug');
            btnOpenCreate?.addEventListener('click', () => {
                modalCreate.classList.remove('hidden');
            });

            // 1. KONTROL MODAL DETAIL BUG (Lihat & Edit Detail)
            const modalDetail = document.getElementById('modal-detail-bug');
            const formDetail = document.getElementById('form-detail-bug');

            const openDetailModal = (data) => {
                formDetail.action = `/bugs/${data.id}`;
                document.getElementById('detail-edit-title').value = data.title || '';
                document.getElementById('detail-edit-project').value = data.project_id || '';
                document.getElementById('detail-edit-module').value = data.module_id || '';
                document.getElementById('detail-edit-severity').value = data.severity || 'medium';
                document.getElementById('detail-edit-status').value = data.status || 'open';
                document.getElementById('detail-edit-reported-by').value = data.reported_by && data.reported_by !== '-' ? data.reported_by : '';
                document.getElementById('detail-edit-reported-date').value = data.reported_date_raw || '';
                document.getElementById('detail-assigned-name').textContent = data.assignee_name || 'Belum Ditugaskan';
                document.getElementById('detail-edit-description').value = data.description || '';

                // Render Timeline Riwayat & Handover
                const timelineContainer = document.getElementById('detail-timeline-container');
                timelineContainer.innerHTML = '';

                // Event 1: Pelaporan
                const reportEvent = document.createElement('div');
                reportEvent.className = 'relative flex items-start gap-3 text-xs';
                reportEvent.innerHTML = `
                    <div class="w-3 h-3 rounded-full bg-slate-400 border-2 border-white shrink-0 mt-0.5 -ml-[19px]"></div>
                    <div class="flex-1 bg-slate-50 border border-slate-200 rounded-lg p-2.5">
                        <div class="flex items-center justify-between font-semibold text-slate-800">
                            <span>Laporan Bug Dibuat</span>
                            <span class="text-[10px] text-slate-400 font-normal">${data.created_at}</span>
                        </div>
                        <p class="text-slate-600 mt-1">Dilaporkan oleh <strong>${data.reported_by}</strong> (${data.reported_date}).</p>
                    </div>
                `;
                timelineContainer.appendChild(reportEvent);

                // Event 2: Handover Logs
                if (Array.isArray(data.handover_logs) && data.handover_logs.length > 0) {
                    data.handover_logs.forEach((log) => {
                        const logDate = log.handed_over_at ? new Date(log.handed_over_at).toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' }) : 'Waktu Handover';
                        const handoverEvent = document.createElement('div');
                        handoverEvent.className = 'relative flex items-start gap-3 text-xs';
                        handoverEvent.innerHTML = `
                            <div class="w-3 h-3 rounded-full bg-indigo-500 border-2 border-white shrink-0 mt-0.5 -ml-[19px]"></div>
                            <div class="flex-1 bg-indigo-50/50 border border-indigo-200/80 rounded-lg p-2.5">
                                <div class="flex items-center justify-between font-semibold text-indigo-950">
                                    <span>Pengalihan Penanganan (Handover)</span>
                                    <span class="text-[10px] text-indigo-600 font-normal">${logDate}</span>
                                </div>
                                <p class="text-indigo-900 mt-1">Dialihkan dari <strong>${log.from_developer_name || 'Sebelumnya'}</strong> ke <strong>${log.to_developer_name || 'Pengembang Baru'}</strong>.</p>
                                ${log.note ? `<p class="text-indigo-700 italic mt-0.5 text-[11px]">Catatan: "${log.note}"</p>` : ''}
                            </div>
                        `;
                        timelineContainer.appendChild(handoverEvent);
                    });
                }

                // Event 3: Resolved
                if (data.status === 'resolved' || data.resolved_at) {
                    const resolvedEvent = document.createElement('div');
                    resolvedEvent.className = 'relative flex items-start gap-3 text-xs';
                    resolvedEvent.innerHTML = `
                        <div class="w-3 h-3 rounded-full bg-emerald-500 border-2 border-white shrink-0 mt-0.5 -ml-[19px]"></div>
                        <div class="flex-1 bg-emerald-50 border border-emerald-200 rounded-lg p-2.5">
                            <div class="flex items-center justify-between font-semibold text-emerald-950">
                                <span>Bug Diselesaikan (Resolved)</span>
                                <span class="text-[10px] text-emerald-700 font-normal">${data.resolved_at || '-'}</span>
                            </div>
                            <p class="text-emerald-900 mt-1">${data.solution_notes || 'Bug telah diselesaikan dan diverifikasi.'}</p>
                        </div>
                    `;
                    timelineContainer.appendChild(resolvedEvent);
                }

                modalDetail.classList.remove('hidden');
            };

            document.querySelectorAll('.btn-detail-bug').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const data = JSON.parse(btn.dataset.bug);
                    openDetailModal(data);
                });
            });

            // 2. KONTROL MODAL SOLUSI BUG (Khusus Kelola & Lihat Solusi)
            const modalSolution = document.getElementById('modal-solution-bug');
            const formSolution = document.getElementById('form-solution-bug');

            const openSolutionModal = (data) => {
                formSolution.action = `/bugs/${data.id}`;
                document.getElementById('solution-project-badge').textContent = `[${data.project_code}] ${data.project_name}`;
                document.getElementById('solution-module-badge').textContent = data.module_name ? `• ${data.module_name}` : '';
                document.getElementById('solution-bug-title').textContent = data.title;
                document.getElementById('solution-status').value = data.status || 'resolved';
                document.getElementById('solution-root-cause').value = data.root_cause || '';
                document.getElementById('solution-notes').value = data.solution_notes || '';

                const resAtEl = document.getElementById('solution-resolved-at');
                if (data.resolved_at) {
                    resAtEl.textContent = `Telah diselesaikan pada: ${data.resolved_at}`;
                } else {
                    resAtEl.textContent = '';
                }

                modalSolution.classList.remove('hidden');
            };

            document.querySelectorAll('.btn-solution-bug').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const data = JSON.parse(btn.dataset.bug);
                    openSolutionModal(data);
                });
            });

            // 3. KONTROL MODAL HAPUS BUG
            const modalDelete = document.getElementById('modal-delete-bug');
            const formDelete = document.getElementById('form-delete-bug');
            const deleteTitle = document.getElementById('delete-bug-title');
            document.querySelectorAll('.btn-delete-bug').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    formDelete.action = btn.dataset.deleteUrl;
                    deleteTitle.textContent = btn.dataset.bugTitle;
                    modalDelete.classList.remove('hidden');
                });
            });

            // Tombol Tutup Semua Modal
            document.querySelectorAll('.btn-close-modal').forEach(btn => {
                btn.addEventListener('click', () => {
                    modalDetail?.classList.add('hidden');
                    modalSolution?.classList.add('hidden');
                    modalCreate?.classList.add('hidden');
                    modalDelete?.classList.add('hidden');
                });
            });
        });
    </script>
</body>
</html>
