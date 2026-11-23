<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Riwayat Rilis & Repo Modul - {{ config('app.name', 'Aplikasi Bug Tracker') }}</title>
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

                    {{-- Fitur Riwayat Rilis & Repo Modul (Active State) --}}
                    <a
                        href="{{ route('releases.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3.5 py-2.5 text-sm font-semibold transition bg-slate-800 text-white border border-slate-700/70 shadow-xs"
                    >
                        <svg class="w-5 h-5 text-indigo-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
                <a href="{{ route('account.edit') }}" class="flex items-center gap-3 px-2 py-1.5 rounded-lg hover:bg-slate-800/60 transition group" title="Buka Pengaturan Akun">
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
                        <span>Gagal Menyimpan Riwayat Rilis:</span>
                    </div>
                    <ul class="list-disc list-inside space-y-0.5 text-xs">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- Header Halaman Riwayat Rilis & Repo Modul --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">Riwayat Rilis & Repo Modul</h1>
                    <p class="mt-1 text-sm text-slate-600">Pantau rilis versi, branch, commit Git, tautan repositori, serta cakupan modul proyek.</p>
                </div>

                {{-- Tombol Buka Modal Tambah Rilis --}}
                <button
                    type="button"
                    id="btn-open-create-release"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2.5 text-xs sm:text-sm font-semibold text-white shadow-xs hover:bg-indigo-700 transition cursor-pointer shrink-0"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    <span>Catat Rilis Baru</span>
                </button>
            </div>

            {{-- Kartu Metrik Ringkasan --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
                {{-- Total Rilis --}}
                <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-2xs">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Total Rilis</div>
                    <div class="mt-2 text-2xl font-extrabold text-slate-900">{{ $metrics['total'] }}</div>
                    <div class="mt-1 text-xs text-slate-500">Seluruh pencatatan rilis</div>
                </div>

                {{-- Rilis Ber-Tag / Versi --}}
                <div class="rounded-xl border border-emerald-200 bg-emerald-50/50 p-4 shadow-2xs">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-emerald-700">Rilis Ber-Tag</div>
                    <div class="mt-2 text-2xl font-extrabold text-emerald-900">{{ $metrics['tagged'] }}</div>
                    <div class="mt-1 text-xs text-emerald-700/80">Rilis berlabel versi resmi</div>
                </div>

                {{-- Proyek Dirilis --}}
                <div class="rounded-xl border border-indigo-200 bg-indigo-50/50 p-4 shadow-2xs">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-indigo-700">Proyek Dirilis</div>
                    <div class="mt-2 text-2xl font-extrabold text-indigo-900">{{ $metrics['projects_released'] }}</div>
                    <div class="mt-1 text-xs text-indigo-700/80">Proyek memiliki riwayat rilis</div>
                </div>

                {{-- Branch Aktif --}}
                <div class="rounded-xl border border-amber-200 bg-amber-50/50 p-4 shadow-2xs">
                    <div class="text-[11px] font-bold uppercase tracking-wider text-amber-700">Branch Terpantau</div>
                    <div class="mt-2 text-2xl font-extrabold text-amber-900">{{ $metrics['branches_count'] }}</div>
                    <div class="mt-1 text-xs text-amber-700/80">Cabang Git rilis terdaftar</div>
                </div>
            </div>

            {{-- Filter & Search Bar --}}
            <div class="rounded-xl border border-slate-200 bg-white p-4 shadow-2xs mb-6">
                <form method="GET" action="{{ route('releases.index') }}" class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3">
                    {{-- Pencarian Kata Kunci --}}
                    <div class="relative flex-1">
                        <input
                            type="text"
                            name="search"
                            value="{{ $search }}"
                            placeholder="Cari tag versi, branch, commit, proyek, atau modul..."
                            class="w-full rounded-lg border border-slate-300 py-2 pl-9 pr-3.5 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900"
                        >
                        <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                        </svg>
                    </div>

                    {{-- Dropdown Filter Proyek & Branch --}}
                    <div class="flex items-center gap-2 flex-wrap sm:flex-nowrap shrink-0">
                        {{-- Filter Proyek --}}
                        <select
                            name="project_id"
                            onchange="this.form.submit()"
                            class="rounded-lg border border-slate-300 bg-white py-2 px-3 text-xs sm:text-sm text-slate-700 focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 cursor-pointer"
                        >
                            <option value="">Semua Proyek</option>
                            @foreach ($projects as $proj)
                                <option value="{{ $proj->id }}" {{ (string) $projectIdFilter === (string) $proj->id ? 'selected' : '' }}>
                                    [{{ $proj->code }}] {{ $proj->name }}
                                </option>
                            @endforeach
                        </select>

                        {{-- Filter Branch --}}
                        <select
                            name="branch"
                            onchange="this.form.submit()"
                            class="rounded-lg border border-slate-300 bg-white py-2 px-3 text-xs sm:text-sm text-slate-700 focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 cursor-pointer"
                        >
                            <option value="">Semua Branch</option>
                            @foreach ($branches as $br)
                                <option value="{{ $br }}" {{ $branchFilter === $br ? 'selected' : '' }}>
                                    {{ $br }}
                                </option>
                            @endforeach
                        </select>

                        @if ($search || $projectIdFilter || $branchFilter)
                            <a
                                href="{{ route('releases.index') }}"
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

            {{-- Tabel Riwayat Rilis & Repo Modul (Tegas, Responsif & Ramah Desktop/Tablet) --}}
            <div class="rounded-xl border border-slate-200 bg-white shadow-2xs overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-xs sm:text-sm">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50/75 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                <th scope="col" class="py-3.5 pl-5 pr-3">Tag Rilis & Tanggal</th>
                                <th scope="col" class="py-3.5 px-3">Proyek & Repositori Git</th>
                                <th scope="col" class="py-3.5 px-3">Branch & Commit Hash</th>
                                <th scope="col" class="py-3.5 px-3 hidden md:table-cell">Modul Proyek</th>
                                <th scope="col" class="py-3.5 pl-3 pr-5">Catatan Rilis</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 font-medium text-slate-700">
                            @forelse ($releases as $rel)
                                <tr class="hover:bg-slate-50/70 transition-colors" id="row-release-{{ $rel->id }}">
                                    {{-- 1. Tag Rilis & Tanggal --}}
                                    <td class="py-3.5 pl-5 pr-3 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            @if ($rel->release_tags)
                                                <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                    <svg class="w-3 h-3 text-emerald-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.386a10.96 10.96 0 003.882-3.882c.486-.827.313-1.908-.386-2.607L9.762 3.659A2.25 2.25 0 009.568 3z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/>
                                                    </svg>
                                                    <span>{{ $rel->release_tags }}</span>
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-slate-100 text-slate-500 border border-slate-200">
                                                    Tanpa Tag
                                                </span>
                                            @endif
                                        </div>
                                        <div class="text-[11px] text-slate-400 mt-1">
                                            {{ $rel->release_date ? $rel->release_date->format('d M Y') : '-' }}
                                        </div>
                                    </td>

                                    {{-- 2. Proyek & Repositori Git --}}
                                    <td class="py-3.5 px-3 whitespace-nowrap">
                                        @if ($rel->project)
                                            <div class="flex items-center gap-2">
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-mono font-bold text-white shadow-2xs" style="background-color: {{ $rel->project->color ?? '#4F46E5' }}">
                                                    [{{ $rel->project->code }}]
                                                </span>
                                                <span class="font-semibold text-slate-900 truncate max-w-40" title="{{ $rel->project->name }}">
                                                    {{ $rel->project->name }}
                                                </span>
                                            </div>

                                            {{-- Link Repositori Git Proyek --}}
                                            @if ($rel->project->repo_url)
                                                <div class="mt-1">
                                                    <a
                                                        href="{{ $rel->project->repo_url }}"
                                                        target="_blank"
                                                        rel="noopener noreferrer"
                                                        class="inline-flex items-center gap-1 text-[11px] text-indigo-600 hover:text-indigo-800 hover:underline font-mono"
                                                        title="{{ $rel->project->repo_url }}"
                                                    >
                                                        <svg class="w-3 h-3 shrink-0" fill="currentColor" viewBox="0 0 24 24">
                                                            <path fill-rule="evenodd" clip-rule="evenodd" d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.53 1.032 1.53 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"/>
                                                        </svg>
                                                        <span>{{ Str::limit(str_replace(['https://', 'http://'], '', $rel->project->repo_url), 24) }}</span>
                                                    </a>
                                                </div>
                                            @endif
                                        @else
                                            <span class="text-xs text-slate-400 italic">Tanpa Proyek</span>
                                        @endif
                                    </td>

                                    {{-- 3. Branch & Commit Hash --}}
                                    <td class="py-3.5 px-3 whitespace-nowrap">
                                        <div class="flex items-center gap-1.5">
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-mono font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                                <svg class="w-3 h-3 text-slate-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                                                </svg>
                                                <span>{{ $rel->branch_name }}</span>
                                            </span>
                                        </div>

                                        <div class="mt-1 flex items-center gap-1">
                                            @if ($rel->project?->repo_url && str_contains($rel->project->repo_url, 'github.com'))
                                                <a
                                                    href="{{ rtrim($rel->project->repo_url, '/') }}/commit/{{ $rel->commit_hash }}"
                                                    target="_blank"
                                                    rel="noopener noreferrer"
                                                    class="inline-flex items-center gap-1 font-mono text-xs text-indigo-600 hover:text-indigo-800 hover:underline"
                                                    title="Commit: {{ $rel->commit_hash }}"
                                                >
                                                    <code>#{{ $rel->short_hash }}</code>
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 6H5.25A2.25 2.25 0 003 8.25v10.5A2.25 2.25 0 005.25 21h10.5A2.25 2.25 0 0018 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25"/>
                                                    </svg>
                                                </a>
                                            @else
                                                <span class="font-mono text-xs text-slate-600 cursor-help" title="Commit: {{ $rel->commit_hash }}">
                                                    <code>#{{ $rel->short_hash }}</code>
                                                </span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- 4. Modul Proyek Terkait --}}
                                    <td class="py-3.5 px-3 hidden md:table-cell max-w-xs">
                                        @if ($rel->project && $rel->project->modules->isNotEmpty())
                                            <div class="flex items-center gap-1 flex-wrap">
                                                @foreach ($rel->project->modules->take(2) as $mod)
                                                    <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                                        {{ $mod->name }}
                                                    </span>
                                                @endforeach
                                                @if ($rel->project->modules->count() > 2)
                                                    <span class="text-[10px] font-semibold text-slate-500">
                                                        +{{ $rel->project->modules->count() - 2 }} lainnya
                                                    </span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="text-xs text-slate-400 italic">Belum ada modul</span>
                                        @endif
                                    </td>

                                    {{-- 5. Catatan Rilis / Summary Changes --}}
                                    <td class="py-3.5 pl-3 pr-5 max-w-xs text-xs text-slate-600">
                                        <div class="line-clamp-2">
                                            {{ $rel->summary_changes ?: '-' }}
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-12 text-center">
                                        <div class="max-w-xs mx-auto text-center">
                                            <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 mx-auto mb-3">
                                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.386a10.96 10.96 0 003.882-3.882c.486-.827.313-1.908-.386-2.607L9.762 3.659A2.25 2.25 0 009.568 3z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/>
                                                </svg>
                                            </div>
                                            <p class="text-sm font-semibold text-slate-800">Tidak ada riwayat rilis ditemukan</p>
                                            <p class="text-xs text-slate-500 mt-1">Sesuaikan filter pencarian atau catat riwayat rilis baru.</p>
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

{{-- MODAL 1: CATAT RILIS BARU (Single Screen, Clean & Tegas) --}}
<div
    id="modal-create-release"
    class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-slate-900/60 backdrop-blur-xs hidden"
    aria-modal="true"
    role="dialog"
>
    <div class="bg-white rounded-2xl shadow-xl border border-slate-200 w-full max-w-lg overflow-hidden">
        {{-- Modal Header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200">
            <div>
                <h3 class="text-base font-bold text-slate-900">Catat Riwayat Rilis Baru</h3>
                <p class="text-xs text-slate-500 mt-0.5">Dokumentasikan rilis versi dan commit repositori proyek.</p>
            </div>
            <button type="button" class="btn-close-modal p-1.5 rounded-lg text-slate-400 hover:text-slate-600 hover:bg-slate-100 transition cursor-pointer">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Form Tambah Rilis --}}
        <form method="POST" action="{{ route('releases.store') }}" class="p-6 space-y-4">
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

            {{-- Tanggal Rilis & Tag Versi --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="create-release-date" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Tanggal Rilis <span class="text-rose-600">*</span>
                    </label>
                    <input
                        type="date"
                        id="create-release-date"
                        name="release_date"
                        value="{{ date('Y-m-d') }}"
                        required
                        class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900"
                    >
                </div>

                <div>
                    <label for="create-release-tags" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Tag Versi (Opsional)
                    </label>
                    <input
                        type="text"
                        id="create-release-tags"
                        name="release_tags"
                        maxlength="50"
                        placeholder="Contoh: v1.0.0"
                        class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900"
                    >
                </div>
            </div>

            {{-- Branch & Commit Hash --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <div>
                    <label for="create-branch-name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Nama Branch <span class="text-rose-600">*</span>
                    </label>
                    <input
                        type="text"
                        id="create-branch-name"
                        name="branch_name"
                        required
                        maxlength="100"
                        placeholder="Contoh: main atau release/1.0"
                        class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900"
                    >
                </div>

                <div>
                    <label for="create-commit-hash" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                        Commit Hash (SHA) <span class="text-rose-600">*</span>
                    </label>
                    <input
                        type="text"
                        id="create-commit-hash"
                        name="commit_hash"
                        required
                        minlength="7"
                        maxlength="40"
                        placeholder="Contoh: a1b2c3d4..."
                        class="w-full font-mono rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900"
                    >
                </div>
            </div>

            {{-- Ringkasan Perubahan / Changelog --}}
            <div>
                <label for="create-summary-changes" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1">
                    Ringkasan Perubahan / Catatan Rilis (Opsional)
                </label>
                <textarea
                    id="create-summary-changes"
                    name="summary_changes"
                    rows="3"
                    placeholder="Tuliskan poin perbaikan, penambahan fitur modul, atau catatan rilis..."
                    class="w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900"
                ></textarea>
            </div>

            {{-- Tombol Aksi Modal --}}
            <div class="flex items-center justify-end gap-3 pt-3 border-t border-slate-200">
                <button type="button" class="btn-close-modal px-4 py-2 rounded-lg border border-slate-300 text-xs sm:text-sm font-semibold text-slate-700 hover:bg-slate-50 cursor-pointer">
                    Batal
                </button>
                <button type="submit" class="px-5 py-2 rounded-lg bg-indigo-600 text-xs sm:text-sm font-semibold text-white hover:bg-indigo-700 transition cursor-pointer shadow-xs">
                    Simpan Rilis
                </button>
            </div>
        </form>
    </div>
</div>

{{-- Vanilla JS untuk Kontrol Sidebar & Modal Tambah Rilis --}}
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

        // 2. KONTROL MODAL TAMBAH RILIS (Append-only)
        const modalCreate = document.getElementById('modal-create-release');
        const btnOpenCreate = document.getElementById('btn-open-create-release');

        btnOpenCreate?.addEventListener('click', () => {
            modalCreate?.classList.remove('hidden');
        });

        // 3. TUTUP MODAL
        document.querySelectorAll('.btn-close-modal').forEach(btn => {
            btn.addEventListener('click', () => {
                modalCreate?.classList.add('hidden');
            });
        });

        // Tutup modal jika klik di luar box modal
        modalCreate?.addEventListener('click', (e) => {
            if (e.target === modalCreate) {
                modalCreate.classList.add('hidden');
            }
        });
    });
</script>

</body>
</html>
