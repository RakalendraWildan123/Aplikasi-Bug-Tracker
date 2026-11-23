<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Master Akun - {{ config('app.name', 'Aplikasi Bug Tracker') }}</title>
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
                        {{-- Fitur Master Akun (Active State) --}}
                        <a
                            href="{{ route('admin.accounts.index') }}"
                            class="flex items-center gap-3 rounded-lg px-3.5 py-2.5 text-sm font-semibold transition bg-slate-800 text-white border border-slate-700/70 shadow-xs"
                        >
                            <svg class="w-5 h-5 text-indigo-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
                        {{ $user->avatar ?? 'AD' }}
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
                            <p class="font-bold">Terjadi kendala dalam pemrosesan data akun:</p>
                            <ul class="list-disc list-inside mt-1 font-normal text-xs sm:text-sm">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Header Halaman & Tombol Tambah Akun --}}
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">Master Akun Pengguna</h1>
                    <p class="mt-1 text-sm text-slate-600">Kelola akun atasan dan developer, hak akses, peranan teknis, dan status aktif login.</p>
                </div>

                {{-- Tombol Buka Pop-up Tambah Akun --}}
                <div class="flex items-center gap-2">
                    <button
                        type="button"
                        id="btn-open-create-account"
                        class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs sm:text-sm font-semibold transition shadow-xs cursor-pointer focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2"
                    >
                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.375 12.375 0 014 19.235z"/>
                        </svg>
                        <span>Tambah Akun Baru</span>
                    </button>
                </div>
            </div>

            {{-- Ringkasan Metrik Statistik Akun (Bersih & Tegas) --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
                <div class="p-4 rounded-xl border border-slate-200 bg-white shadow-2xs">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Total Akun</div>
                    <div class="mt-1 text-2xl font-black tracking-tight text-slate-900">{{ $metrics['total'] }}</div>
                    <div class="mt-1 text-[11px] text-slate-500">Terdaftar dalam sistem</div>
                </div>

                <div class="p-4 rounded-xl border border-purple-200 bg-purple-50/50 shadow-2xs">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-purple-700">Atasan / Admin</div>
                    <div class="mt-1 text-2xl font-black tracking-tight text-purple-900">{{ $metrics['admin'] }}</div>
                    <div class="mt-1 text-[11px] text-purple-600">Akses manajerial penuh</div>
                </div>

                <div class="p-4 rounded-xl border border-blue-200 bg-blue-50/50 shadow-2xs">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-blue-700">Developer</div>
                    <div class="mt-1 text-2xl font-black tracking-tight text-blue-900">{{ $metrics['developer'] }}</div>
                    <div class="mt-1 text-[11px] text-blue-600">Tim pengembang aktif</div>
                </div>

                <div class="p-4 rounded-xl border border-emerald-200 bg-emerald-50/50 shadow-2xs">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-emerald-700">Akun Aktif</div>
                    <div class="mt-1 text-2xl font-black tracking-tight text-emerald-900">{{ $metrics['active'] }}</div>
                    <div class="mt-1 text-[11px] text-emerald-600">Dapat melakukan login</div>
                </div>
            </div>

            {{-- Bar Filter & Pencarian (Ramah Desktop & Tablet) --}}
            <div class="mb-6 p-4 rounded-xl border border-slate-200 bg-white shadow-2xs">
                <form method="GET" action="{{ route('admin.accounts.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    {{-- Input Pencarian --}}
                    <div class="sm:col-span-2">
                        <label for="search-input" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Cari Akun</label>
                        <div class="relative">
                            <input
                                type="text"
                                id="search-input"
                                name="search"
                                value="{{ $search }}"
                                placeholder="Cari nama, email, atau spesialisasi..."
                                class="w-full pl-9 pr-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                            />
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-4 h-4 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    {{-- Filter Peran --}}
                    <div>
                        <label for="role-filter" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Peran</label>
                        <select
                            id="role-filter"
                            name="role"
                            onchange="this.form.submit()"
                            class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition cursor-pointer"
                        >
                            <option value="">Semua Peran</option>
                            <option value="admin" @selected($roleFilter === 'admin')>Atasan / Admin</option>
                            <option value="developer" @selected($roleFilter === 'developer')>Developer</option>
                        </select>
                    </div>

                    {{-- Filter Status --}}
                    <div>
                        <label for="status-filter" class="block text-[11px] font-bold uppercase tracking-wider text-slate-500 mb-1">Status Akses</label>
                        <div class="flex items-center gap-2">
                            <select
                                id="status-filter"
                                name="status"
                                onchange="this.form.submit()"
                                class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition cursor-pointer"
                            >
                                <option value="">Semua Status</option>
                                <option value="active" @selected($statusFilter === 'active')>Aktif</option>
                                <option value="inactive" @selected($statusFilter === 'inactive')>Non-Aktif</option>
                            </select>

                            @if ($search || $roleFilter || $statusFilter)
                                <a
                                    href="{{ route('admin.accounts.index') }}"
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

            {{-- Tabel Master Akun (Tegas, Responsif & Ramah Desktop/Tablet) --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-2xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs sm:text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50/75 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                <th scope="col" class="py-3.5 pl-5 pr-3">Pengguna</th>
                                <th scope="col" class="py-3.5 px-3">Peran & Spesialisasi</th>
                                <th scope="col" class="py-3.5 px-3">Status Akses</th>
                                <th scope="col" class="py-3.5 px-3 hidden md:table-cell">Bergabung</th>
                                <th scope="col" class="py-3.5 pl-3 pr-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            @forelse ($accounts as $account)
                                @php
                                    $accountData = [
                                        'id' => $account->id,
                                        'name' => $account->name,
                                        'email' => $account->email,
                                        'role' => $account->role,
                                        'role_title' => $account->role_title,
                                        'avatar' => $account->avatar,
                                        'avatar_bg' => $account->avatar_bg ?? '#4F46E5',
                                        'foto_profil_url' => $account->foto_profil_url,
                                        'is_active' => (bool) $account->is_active,
                                        'created_at' => $account->created_at?->format('d M Y') ?? '-',
                                        'is_self' => $account->id === $user->id,
                                    ];
                                @endphp
                                <tr class="hover:bg-slate-50/60 transition-colors">
                                    {{-- 1. Pengguna (Avatar / Foto, Nama, Email) --}}
                                    <td class="py-3.5 pl-5 pr-3">
                                        <div class="flex items-center gap-3">
                                            @if ($account->foto_profil_url)
                                                <img src="{{ $account->foto_profil_url }}" alt="{{ $account->name }}" class="w-10 h-10 rounded-full object-cover border border-slate-200 shrink-0 shadow-2xs">
                                            @else
                                                <div
                                                    class="w-10 h-10 rounded-full flex items-center justify-center font-bold text-white shrink-0 shadow-2xs text-xs"
                                                    style="background-color: {{ $account->avatar_bg ?? '#4F46E5' }}"
                                                >
                                                    {{ $account->avatar ?? 'DV' }}
                                                </div>
                                            @endif
                                            <div class="min-w-0">
                                                <div class="flex items-center gap-1.5">
                                                    <span class="font-bold text-slate-900 truncate">{{ $account->name }}</span>
                                                    @if ($account->id === $user->id)
                                                        <span class="px-1.5 py-0.5 rounded text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">Anda</span>
                                                    @endif
                                                </div>
                                                <span class="block text-xs text-slate-500 truncate">{{ $account->email }}</span>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- 2. Peran & Spesialisasi --}}
                                    <td class="py-3.5 px-3 whitespace-nowrap">
                                        @if ($account->isAdmin())
                                            <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-purple-50 text-purple-700 border border-purple-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-purple-600"></span>
                                                Atasan / Admin
                                            </span>
                                        @else
                                            <div class="flex flex-col gap-1 items-start">
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-50 text-blue-700 border border-blue-200">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-600"></span>
                                                    Developer
                                                </span>
                                                @if ($account->role_title)
                                                    <span class="text-[11px] font-medium text-slate-500 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                                                        {{ $account->role_title }}
                                                    </span>
                                                @endif
                                            </div>
                                        @endif
                                    </td>

                                    {{-- 3. Status Akses Login --}}
                                    <td class="py-3.5 px-3 whitespace-nowrap">
                                        @if ($account->is_active)
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                Aktif
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-xs font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                                <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                                Non-Aktif
                                            </span>
                                        @endif
                                    </td>

                                    {{-- 4. Tanggal Dibuat --}}
                                    <td class="py-3.5 px-3 whitespace-nowrap hidden md:table-cell text-xs text-slate-500">
                                        {{ $account->created_at?->format('d M Y') ?? '-' }}
                                    </td>

                                    {{-- 5. Aksi: Edit, Toggle Status, Hapus --}}
                                    <td class="py-3.5 pl-3 pr-5 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-1.5">
                                            {{-- Tombol Edit Akun --}}
                                            <button
                                                type="button"
                                                class="btn-edit-account inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg border border-slate-300 bg-white text-xs font-semibold text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition cursor-pointer shadow-2xs"
                                                data-account="{{ json_encode($accountData) }}"
                                                title="Edit data akun"
                                            >
                                                <svg class="w-3.5 h-3.5 text-slate-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                                </svg>
                                                <span>Edit</span>
                                            </button>

                                            {{-- Tombol Toggle Status (Aktif / Non-Aktif) via Pop-up Modal --}}
                                            @if ($account->id !== $user->id)
                                                @if ($account->is_active)
                                                    <button
                                                        type="button"
                                                        class="btn-toggle-account inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg border border-amber-300 bg-amber-50 text-xs font-semibold text-amber-800 hover:bg-amber-100 transition cursor-pointer shadow-2xs"
                                                        title="Nonaktifkan akses akun"
                                                        data-id="{{ $account->id }}"
                                                        data-name="{{ $account->name }}"
                                                        data-email="{{ $account->email }}"
                                                        data-action="deactivate"
                                                        data-url="{{ route('admin.accounts.toggle-active', $account) }}"
                                                    >
                                                        <svg class="w-3.5 h-3.5 text-amber-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                        </svg>
                                                        <span>Nonaktifkan</span>
                                                    </button>
                                                @else
                                                    <button
                                                        type="button"
                                                        class="btn-toggle-account inline-flex items-center gap-1 px-2.5 py-1.5 rounded-lg border border-emerald-300 bg-emerald-50 text-xs font-semibold text-emerald-800 hover:bg-emerald-100 transition cursor-pointer shadow-2xs"
                                                        title="Aktifkan kembali akses akun"
                                                        data-id="{{ $account->id }}"
                                                        data-name="{{ $account->name }}"
                                                        data-email="{{ $account->email }}"
                                                        data-action="activate"
                                                        data-url="{{ route('admin.accounts.toggle-active', $account) }}"
                                                    >
                                                        <svg class="w-3.5 h-3.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                        </svg>
                                                        <span>Aktifkan</span>
                                                    </button>
                                                @endif
                                            @endif

                                            {{-- Tombol Hapus Akun (Tidak Bisa Menghapus Akun Sendiri) --}}
                                            @if ($account->id !== $user->id)
                                                <button
                                                    type="button"
                                                    class="btn-delete-account p-1.5 rounded-lg border border-rose-200 bg-rose-50 text-rose-700 hover:bg-rose-100 hover:text-rose-900 transition cursor-pointer shadow-2xs"
                                                    data-id="{{ $account->id }}"
                                                    data-name="{{ $account->name }}"
                                                    data-email="{{ $account->email }}"
                                                    title="Hapus akun pengguna"
                                                >
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center">
                                        <div class="flex flex-col items-center justify-center text-slate-400">
                                            <svg class="w-12 h-12 stroke-1 text-slate-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z"/>
                                            </svg>
                                            <p class="text-sm font-semibold text-slate-600">Tidak ada akun ditemukan</p>
                                            <p class="text-xs text-slate-400 mt-1">Gunakan kata kunci atau filter status/peran yang berbeda.</p>
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
{{-- 1. POP-UP MODAL: TAMBAH AKUN BARU                                         --}}
{{-- ========================================================================= --}}
<div
    id="modal-create-account"
    class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4 overflow-y-auto hidden"
    aria-hidden="true"
>
    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden my-8">
        {{-- Header Modal --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-slate-50/75">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Tambah Akun Pengguna</h3>
                <p class="text-xs text-slate-500 mt-0.5">Lengkapi data akun dan pilih peranan dalam tim.</p>
            </div>
            <button type="button" class="btn-close-modal p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-200 cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Form Tambah Akun --}}
        <form method="POST" action="{{ route('admin.accounts.store') }}" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf

            {{-- Nama Lengkap --}}
            <div>
                <label for="create-name" class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input
                    type="text"
                    id="create-name"
                    name="name"
                    required
                    placeholder="Masukkan nama lengkap pengguna"
                    class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                />
            </div>

            {{-- Email --}}
            <div>
                <label for="create-email" class="block text-xs font-bold text-slate-700 mb-1">Alamat Email <span class="text-rose-500">*</span></label>
                <input
                    type="email"
                    id="create-email"
                    name="email"
                    required
                    placeholder="contoh: farhan@bugtracker.test"
                    class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                />
            </div>

            {{-- Peran & Hak Akses --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="create-role" class="block text-xs font-bold text-slate-700 mb-1">Peran Akses <span class="text-rose-500">*</span></label>
                    <select
                        id="create-role"
                        name="role"
                        required
                        class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition cursor-pointer"
                    >
                        <option value="developer" selected>Developer</option>
                        <option value="admin">Atasan / Admin</option>
                    </select>
                </div>

                {{-- Spesialisasi Developer (Kondisional saat role developer) --}}
                <div id="create-role-title-container">
                    <label for="create-role-title" class="block text-xs font-bold text-slate-700 mb-1">Spesialisasi <span class="text-rose-500">*</span></label>
                    <select
                        id="create-role-title"
                        name="role_title"
                        class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition cursor-pointer"
                    >
                        @foreach ($developerRoles as $roleOption)
                            <option value="{{ $roleOption }}">{{ $roleOption }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Avatar & Warna Profil --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="create-avatar" class="block text-xs font-bold text-slate-700 mb-1">Inisial Avatar <span class="text-slate-400 font-normal">(Opsional)</span></label>
                    <input
                        type="text"
                        id="create-avatar"
                        name="avatar"
                        maxlength="4"
                        placeholder="Otomatis dari nama jika kosong"
                        class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition uppercase"
                    />
                </div>

                <div>
                    <label for="create-avatar-bg" class="block text-xs font-bold text-slate-700 mb-1">Warna Latar Avatar</label>
                    <div class="flex items-center gap-2">
                        <input
                            type="color"
                            id="create-avatar-bg"
                            name="avatar_bg"
                            value="#4F46E5"
                            class="h-9 w-12 rounded border border-slate-300 p-0.5 cursor-pointer"
                        />
                        <span class="text-xs text-slate-500">Pilih warna inisial</span>
                    </div>
                </div>
            </div>

            {{-- Upload Foto Profil --}}
            <div>
                <label for="create-photo" class="block text-xs font-bold text-slate-700 mb-1">Foto Profil <span class="text-slate-400 font-normal">(Opsional, max 2MB)</span></label>
                <input
                    type="file"
                    id="create-photo"
                    name="foto_profil"
                    accept="image/png,image/jpeg,image/webp"
                    class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer"
                />
            </div>

            {{-- Password & Konfirmasi --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="create-password" class="block text-xs font-bold text-slate-700 mb-1">Password <span class="text-rose-500">*</span></label>
                    <input
                        type="password"
                        id="create-password"
                        name="password"
                        required
                        minlength="8"
                        placeholder="Minimal 8 karakter"
                        class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                    />
                </div>
                <div>
                    <label for="create-password-confirmation" class="block text-xs font-bold text-slate-700 mb-1">Konfirmasi Password <span class="text-rose-500">*</span></label>
                    <input
                        type="password"
                        id="create-password-confirmation"
                        name="password_confirmation"
                        required
                        minlength="8"
                        placeholder="Ulangi password"
                        class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                    />
                </div>
            </div>

            {{-- Status Aktif Langsung --}}
            <div class="pt-2">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="is_active" value="1" checked class="w-4 h-4 rounded text-indigo-600 border-slate-300 focus:ring-indigo-500">
                    <span class="text-xs font-semibold text-slate-700">Aktifkan akun ini segera agar dapat langsung login</span>
                </label>
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
                    Simpan Akun
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ========================================================================= --}}
{{-- 2. POP-UP MODAL: EDIT AKUN                                                --}}
{{-- ========================================================================= --}}
<div
    id="modal-edit-account"
    class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4 overflow-y-auto hidden"
    aria-hidden="true"
>
    <div class="relative w-full max-w-lg bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden my-8">
        {{-- Header Modal --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-slate-50/75">
            <div>
                <h3 class="text-lg font-bold text-slate-900">Edit Data Akun</h3>
                <p class="text-xs text-slate-500 mt-0.5">Perbarui profil, peranan, atau reset password akun.</p>
            </div>
            <button type="button" class="btn-close-modal p-1.5 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-200 cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Form Edit Akun --}}
        <form id="form-edit-account" method="POST" action="" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            @method('PUT')

            {{-- Nama Lengkap --}}
            <div>
                <label for="edit-name" class="block text-xs font-bold text-slate-700 mb-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                <input
                    type="text"
                    id="edit-name"
                    name="name"
                    required
                    class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                />
            </div>

            {{-- Email --}}
            <div>
                <label for="edit-email" class="block text-xs font-bold text-slate-700 mb-1">Alamat Email <span class="text-rose-500">*</span></label>
                <input
                    type="email"
                    id="edit-email"
                    name="email"
                    required
                    class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                />
            </div>

            {{-- Peran & Spesialisasi --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="edit-role" class="block text-xs font-bold text-slate-700 mb-1">Peran Akses <span class="text-rose-500">*</span></label>
                    <select
                        id="edit-role"
                        name="role"
                        required
                        class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition cursor-pointer"
                    >
                        <option value="developer">Developer</option>
                        <option value="admin">Atasan / Admin</option>
                    </select>
                </div>

                {{-- Spesialisasi --}}
                <div id="edit-role-title-container">
                    <label for="edit-role-title" class="block text-xs font-bold text-slate-700 mb-1">Spesialisasi</label>
                    <select
                        id="edit-role-title"
                        name="role_title"
                        class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-800 bg-white focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition cursor-pointer"
                    >
                        @foreach ($developerRoles as $roleOption)
                            <option value="{{ $roleOption }}">{{ $roleOption }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Avatar & Warna --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="edit-avatar" class="block text-xs font-bold text-slate-700 mb-1">Inisial Avatar</label>
                    <input
                        type="text"
                        id="edit-avatar"
                        name="avatar"
                        maxlength="4"
                        class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-800 uppercase focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                    />
                </div>

                <div>
                    <label for="edit-avatar-bg" class="block text-xs font-bold text-slate-700 mb-1">Warna Latar Avatar</label>
                    <div class="flex items-center gap-2">
                        <input
                            type="color"
                            id="edit-avatar-bg"
                            name="avatar_bg"
                            value="#4F46E5"
                            class="h-9 w-12 rounded border border-slate-300 p-0.5 cursor-pointer"
                        />
                        <span class="text-xs text-slate-500">Ubah warna inisial</span>
                    </div>
                </div>
            </div>

            {{-- Foto Profil Saat Ini & Ganti Foto --}}
            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Foto Profil</label>
                <div class="flex items-center gap-3 mb-2" id="edit-photo-preview-container">
                    <img id="edit-photo-preview" src="" alt="Foto" class="w-10 h-10 rounded-full object-cover border border-slate-200 hidden">
                    <label class="flex items-center gap-1.5 text-xs text-rose-600 hover:text-rose-800 cursor-pointer" id="edit-photo-delete-label">
                        <input type="checkbox" name="hapus_foto_profil" value="1" class="w-3.5 h-3.5 text-rose-600 rounded">
                        <span>Hapus foto profil saat ini</span>
                    </label>
                </div>
                <input
                    type="file"
                    id="edit-photo"
                    name="foto_profil"
                    accept="image/png,image/jpeg,image/webp"
                    class="w-full text-xs text-slate-500 file:mr-3 file:py-1.5 file:px-3 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-slate-100 file:text-slate-700 hover:file:bg-slate-200 cursor-pointer"
                />
            </div>

            {{-- Status Aktif --}}
            <div class="pt-1">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" id="edit-is-active" name="is_active" value="1" class="w-4 h-4 rounded text-indigo-600 border-slate-300 focus:ring-indigo-500">
                    <span class="text-xs font-semibold text-slate-700">Status Akun Aktif (Dapat Login)</span>
                </label>
            </div>

            {{-- Reset / Ganti Password Opsional --}}
            <div class="pt-3 border-t border-slate-200">
                <p class="text-xs font-bold text-slate-800 mb-1">Ganti Password <span class="text-slate-400 font-normal">(Kosongkan jika tidak ingin mengubah)</span></p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mt-2">
                    <div>
                        <input
                            type="password"
                            id="edit-password"
                            name="password"
                            minlength="8"
                            placeholder="Password baru (opsional)"
                            class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                        />
                    </div>
                    <div>
                        <input
                            type="password"
                            id="edit-password-confirmation"
                            name="password_confirmation"
                            minlength="8"
                            placeholder="Ulangi password baru"
                            class="w-full px-3 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                        />
                    </div>
                </div>
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
{{-- 2.5. POP-UP MODAL: TOGGLE STATUS (NONAKTIFKAN / AKTIFKAN AKUN)            --}}
{{-- ========================================================================= --}}
<div
    id="modal-toggle-account"
    class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/50 backdrop-blur-xs p-4 overflow-y-auto hidden"
    aria-hidden="true"
    role="dialog"
    aria-modal="true"
>
    <div class="relative w-full max-w-md bg-white rounded-2xl shadow-2xl border border-slate-200 overflow-hidden my-8 transition-all">
        <div class="p-6">
            <div id="toggle-modal-header" class="flex items-center gap-3 mb-3 text-amber-600">
                <div id="toggle-modal-icon-bg" class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center shrink-0">
                    <svg id="toggle-modal-icon-warn" class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                    </svg>
                    <svg id="toggle-modal-icon-check" class="w-6 h-6 text-emerald-600 hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 id="toggle-modal-title" class="text-lg font-bold text-slate-900">Nonaktifkan Akses Akun?</h3>
            </div>

            <p class="text-sm text-slate-600 mb-2">
                <span id="toggle-modal-prompt">Apakah Anda yakin ingin menonaktifkan akses login untuk akun</span>
                <span id="toggle-account-name" class="font-bold text-slate-900"></span> (<span id="toggle-account-email" class="font-semibold text-slate-700"></span>)?
            </p>
            <p id="toggle-modal-desc" class="text-xs text-slate-500 bg-slate-50 p-2.5 rounded-lg border border-slate-200">
                Pengguna dengan akun ini tidak akan dapat login ke dalam sistem hingga statusnya diaktifkan kembali oleh Admin.
            </p>

            <form id="form-toggle-account" method="POST" action="" class="mt-6 flex items-center justify-end gap-3">
                @csrf
                @method('PATCH')
                <button
                    type="button"
                    class="btn-close-modal px-4 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm font-semibold text-slate-700 hover:bg-slate-50 transition cursor-pointer"
                >
                    Batal
                </button>
                <button
                    id="toggle-modal-submit-btn"
                    type="submit"
                    class="px-4 py-2 rounded-lg bg-amber-600 hover:bg-amber-700 text-white text-xs sm:text-sm font-semibold transition shadow-xs cursor-pointer focus:ring-2 focus:ring-amber-500"
                >
                    Nonaktifkan Akun
                </button>
            </form>
        </div>
    </div>
</div>

{{-- ========================================================================= --}}
{{-- 3. POP-UP MODAL: HAPUS AKUN                                               --}}
{{-- ========================================================================= --}}
<div
    id="modal-delete-account"
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
                <h3 class="text-lg font-bold text-slate-900">Hapus Akun Pengguna?</h3>
            </div>

            <p class="text-sm text-slate-600 mb-2">
                Apakah Anda yakin ingin menghapus akun <span id="delete-account-name" class="font-bold text-slate-900"></span> (<span id="delete-account-email" class="font-semibold text-slate-700"></span>)?
            </p>
            <p class="text-xs text-slate-500 bg-slate-50 p-2.5 rounded-lg border border-slate-200">
                Riwayat tugas dan temuan bug yang pernah dibuat atau ditugaskan ke akun ini akan tetap tersimpan aman di sistem dengan status penugasan dikosongkan.
            </p>

            <form id="form-delete-account" method="POST" action="" class="mt-6 flex items-center justify-end gap-3">
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
                    Hapus Akun
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

        // 2. KONTROL MODAL TAMBAH AKUN
        const modalCreate = document.getElementById('modal-create-account');
        const btnOpenCreate = document.getElementById('btn-open-create-account');
        const createRoleSelect = document.getElementById('create-role');
        const createRoleTitleContainer = document.getElementById('create-role-title-container');

        btnOpenCreate?.addEventListener('click', () => {
            modalCreate?.classList.remove('hidden');
        });

        const toggleCreateSpecialization = () => {
            if (createRoleSelect?.value === 'developer') {
                createRoleTitleContainer?.classList.remove('hidden');
            } else {
                createRoleTitleContainer?.classList.add('hidden');
            }
        };

        createRoleSelect?.addEventListener('change', toggleCreateSpecialization);
        toggleCreateSpecialization();

        // 3. KONTROL MODAL EDIT AKUN
        const modalEdit = document.getElementById('modal-edit-account');
        const formEdit = document.getElementById('form-edit-account');
        const editRoleSelect = document.getElementById('edit-role');
        const editRoleTitleContainer = document.getElementById('edit-role-title-container');
        const editPhotoPreview = document.getElementById('edit-photo-preview');
        const editPhotoDeleteLabel = document.getElementById('edit-photo-delete-label');

        const toggleEditSpecialization = () => {
            if (editRoleSelect?.value === 'developer') {
                editRoleTitleContainer?.classList.remove('hidden');
            } else {
                editRoleTitleContainer?.classList.add('hidden');
            }
        };

        editRoleSelect?.addEventListener('change', toggleEditSpecialization);

        document.querySelectorAll('.btn-edit-account').forEach(btn => {
            btn.addEventListener('click', () => {
                const data = JSON.parse(btn.dataset.account);
                formEdit.action = `/admin/accounts/${data.id}`;

                document.getElementById('edit-name').value = data.name || '';
                document.getElementById('edit-email').value = data.email || '';
                document.getElementById('edit-role').value = data.role || 'developer';
                document.getElementById('edit-role-title').value = data.role_title || 'Backend';
                document.getElementById('edit-avatar').value = data.avatar || '';
                document.getElementById('edit-avatar-bg').value = data.avatar_bg || '#4F46E5';
                document.getElementById('edit-is-active').checked = !!data.is_active;

                if (data.foto_profil_url) {
                    editPhotoPreview.src = data.foto_profil_url;
                    editPhotoPreview.classList.remove('hidden');
                    editPhotoDeleteLabel.classList.remove('hidden');
                } else {
                    editPhotoPreview.src = '';
                    editPhotoPreview.classList.add('hidden');
                    editPhotoDeleteLabel.classList.add('hidden');
                }

                toggleEditSpecialization();
                modalEdit?.classList.remove('hidden');
            });
        });

        // 3.5. KONTROL MODAL TOGGLE STATUS (NONAKTIFKAN / AKTIFKAN AKUN)
        const modalToggle = document.getElementById('modal-toggle-account');
        const formToggle = document.getElementById('form-toggle-account');
        const toggleName = document.getElementById('toggle-account-name');
        const toggleEmail = document.getElementById('toggle-account-email');
        const toggleTitle = document.getElementById('toggle-modal-title');
        const togglePrompt = document.getElementById('toggle-modal-prompt');
        const toggleDesc = document.getElementById('toggle-modal-desc');
        const toggleSubmitBtn = document.getElementById('toggle-modal-submit-btn');
        const toggleIconBg = document.getElementById('toggle-modal-icon-bg');
        const toggleIconWarn = document.getElementById('toggle-modal-icon-warn');
        const toggleIconCheck = document.getElementById('toggle-modal-icon-check');

        document.querySelectorAll('.btn-toggle-account').forEach(btn => {
            btn.addEventListener('click', () => {
                const name = btn.dataset.name;
                const email = btn.dataset.email;
                const action = btn.dataset.action;
                const url = btn.dataset.url;

                formToggle.action = url;
                toggleName.textContent = name;
                toggleEmail.textContent = email;

                if (action === 'deactivate') {
                    toggleTitle.textContent = 'Nonaktifkan Akses Akun?';
                    togglePrompt.textContent = 'Apakah Anda yakin ingin menonaktifkan akses login untuk akun ';
                    toggleDesc.textContent = 'Pengguna dengan akun ini tidak akan dapat login ke dalam sistem hingga statusnya diaktifkan kembali oleh Admin.';
                    toggleSubmitBtn.textContent = 'Nonaktifkan Akun';
                    toggleSubmitBtn.className = 'px-4 py-2 rounded-lg bg-amber-600 hover:bg-amber-700 text-white text-xs sm:text-sm font-semibold transition shadow-xs cursor-pointer focus:ring-2 focus:ring-amber-500';
                    toggleIconBg.className = 'w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center shrink-0';
                    toggleIconWarn.classList.remove('hidden');
                    toggleIconCheck.classList.add('hidden');
                } else {
                    toggleTitle.textContent = 'Aktifkan Akses Akun?';
                    togglePrompt.textContent = 'Apakah Anda yakin ingin mengaktifkan kembali akses login untuk akun ';
                    toggleDesc.textContent = 'Pengguna akan dapat langsung login kembali ke dalam sistem menggunakan kredensial akun miliknya.';
                    toggleSubmitBtn.textContent = 'Aktifkan Akun';
                    toggleSubmitBtn.className = 'px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs sm:text-sm font-semibold transition shadow-xs cursor-pointer focus:ring-2 focus:ring-emerald-500';
                    toggleIconBg.className = 'w-10 h-10 rounded-full bg-emerald-100 flex items-center justify-center shrink-0';
                    toggleIconWarn.classList.add('hidden');
                    toggleIconCheck.classList.remove('hidden');
                }

                modalToggle?.classList.remove('hidden');
            });
        });

        // 4. KONTROL MODAL HAPUS AKUN
        const modalDelete = document.getElementById('modal-delete-account');
        const formDelete = document.getElementById('form-delete-account');
        const deleteName = document.getElementById('delete-account-name');
        const deleteEmail = document.getElementById('delete-account-email');

        document.querySelectorAll('.btn-delete-account').forEach(btn => {
            btn.addEventListener('click', () => {
                const id = btn.dataset.id;
                const name = btn.dataset.name;
                const email = btn.dataset.email;

                formDelete.action = `/admin/accounts/${id}`;
                deleteName.textContent = name;
                deleteEmail.textContent = email;
                modalDelete?.classList.remove('hidden');
            });
        });

        // 5. TUTUP SEMUA MODAL
        document.querySelectorAll('.btn-close-modal').forEach(btn => {
            btn.addEventListener('click', () => {
                modalCreate?.classList.add('hidden');
                modalEdit?.classList.add('hidden');
                modalToggle?.classList.add('hidden');
                modalDelete?.classList.add('hidden');
            });
        });

        // Auto buka modal dari URL parameter jika diarahkan dari create / edit
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('action') === 'create') {
            modalCreate?.classList.remove('hidden');
        }
        const editId = urlParams.get('edit');
        if (editId) {
            const editBtn = document.querySelector(`.btn-edit-account[data-account*='"id":${editId}']`) || document.querySelector(`.btn-edit-account[data-account*='"id": ${editId}']`);
            if (editBtn) {
                editBtn.click();
            }
        }

        // Tutup modal jika klik di luar box modal
        [modalCreate, modalEdit, modalToggle, modalDelete].forEach(modal => {
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
