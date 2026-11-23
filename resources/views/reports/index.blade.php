<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Laporan Periodik - {{ config('app.name', 'Aplikasi Bug Tracker') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    <style>
        @media print {
            #app-sidebar,
            #sidebar-backdrop,
            header,
            .no-print {
                display: none !important;
            }
            body, main {
                background: white !important;
                color: #0f172a !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .print-header {
                display: block !important;
            }
            .card-report {
                break-inside: avoid;
                box-shadow: none !important;
                border: 1px solid #cbd5e1 !important;
            }
        }
    </style>
</head>
<body class="min-h-full flex flex-col font-sans antialiased text-slate-800 bg-slate-50">

<div class="min-h-screen flex flex-col lg:flex-row w-full">

    {{-- Backdrop Layar Mobile/Tablet saat Sidebar Terbuka --}}
    <div
        id="sidebar-backdrop"
        class="fixed inset-0 z-40 bg-slate-900/60 backdrop-blur-xs transition-opacity duration-200 hidden lg:hidden no-print"
        aria-hidden="true"
    ></div>

    {{-- Sidebar Navigasi Utama (Clean, Tegas, Tanpa Tab Bersarang & Tanpa Tombol Logout) --}}
    <aside
        id="app-sidebar"
        class="fixed inset-y-0 left-0 z-50 flex w-64 sm:w-68 -translate-x-full flex-col justify-between border-r border-slate-800 bg-slate-900 text-slate-100 transition-transform duration-200 ease-in-out lg:sticky lg:inset-auto lg:top-0 lg:h-screen lg:translate-x-0 shrink-0 shadow-lg lg:shadow-none no-print"
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

                    {{-- Fitur Laporan Periodik (Active State) --}}
                    <a
                        href="{{ route('reports.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3.5 py-2.5 text-sm font-semibold transition bg-slate-800 text-white border border-slate-700/70 shadow-xs"
                    >
                        <svg class="w-5 h-5 text-indigo-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
    <div class="main-content-wrapper flex-1 flex flex-col min-w-0 bg-slate-50 min-h-screen">

        {{-- Top App Bar --}}
        <header class="bg-white border-b border-slate-200 sticky top-0 z-30 no-print">
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
                                    Laporan Periodik
                                </h1>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-indigo-50 text-indigo-700 border border-indigo-200 shrink-0">
                                    Fitur Operasional
                                </span>
                            </div>
                            <p class="hidden sm:block text-xs text-slate-500 truncate">
                                Rekapitulasi kinerja pengembangan, progres tugas, resolusi bug, dan riwayat rilis.
                            </p>
                        </div>
                    </div>

                    {{-- Sisi Kanan: Tombol Cetak / PDF & Profil Pengguna --}}
                    <div class="flex items-center gap-2.5 shrink-0">
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

                        {{-- Tombol Cetak / PDF --}}
                        <button
                            type="button"
                            onclick="window.print()"
                            id="btn-print-report"
                            class="inline-flex items-center gap-1.5 px-3.5 py-2 rounded-lg text-xs sm:text-sm font-semibold bg-indigo-600 text-white hover:bg-indigo-700 active:bg-indigo-800 transition shadow-xs cursor-pointer"
                            title="Cetak laporan atau simpan sebagai PDF"
                        >
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.72 13.829c-.24-1.076-.641-2.128-1.18-3.134a17.47 17.47 0 00-1.89-2.738M12 3v13.5m0 0l-3.75-3.75M12 16.5l3.75-3.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="hidden sm:inline">Cetak / Simpan PDF</span>
                            <span class="sm:hidden">Cetak</span>
                        </button>
                    </div>
                </div>
            </div>
        </header>

        {{-- Header Khusus Cetak / Print Only --}}
        <div class="hidden print-header p-6 border-b border-slate-300">
            <h1 class="text-2xl font-bold text-slate-900">{{ config('app.name', 'Aplikasi Bug Tracker') }}</h1>
            <h2 class="text-base font-semibold text-slate-700 mt-1">Laporan Periodik Monitoring & Kinerja IT Developer</h2>
            <div class="flex items-center justify-between text-xs text-slate-500 mt-2">
                <span>Dicetak pada: {{ now()->isoFormat('D MMMM Y, HH:mm') }} WIB</span>
                <span>
                    Rentang Periode:
                    @if ($startDate && $endDate)
                        <strong>{{ $startDate->isoFormat('D MMM Y') }} &mdash; {{ $endDate->isoFormat('D MMM Y') }}</strong>
                    @else
                        <strong>Semua Data Terdaftar</strong>
                    @endif
                </span>
            </div>
        </div>

        {{-- Isi Konten Halaman --}}
        <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6 space-y-6">

            {{-- 1. Bilah Kontrol Periode & Filter (Responsive Desktop & Tablet, Clean, Tanpa Tab Bersarang) --}}
            <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-xs no-print">
                <form method="GET" action="{{ route('reports.index') }}" class="space-y-4">
                    {{-- Baris 1: Preset Pilihan Cepat Periode --}}
                    <div class="flex items-center gap-1.5 flex-wrap">
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider mr-2">Periode Cepat:</span>

                        @php
                            $presets = [
                                'all' => 'Semua Data',
                                '7d' => '7 Hari Terakhir',
                                '30d' => '30 Hari Terakhir',
                                'this_month' => 'Bulan Ini',
                                'this_quarter' => 'Kuartal Ini',
                                'this_year' => 'Tahun Ini',
                            ];
                        @endphp

                        @foreach ($presets as $key => $label)
                            <a
                                href="{{ route('reports.index', array_merge(request()->query(), ['preset' => $key, 'start_date' => null, 'end_date' => null])) }}"
                                class="px-3 py-1.5 rounded-lg text-xs font-semibold transition cursor-pointer {{ $preset === $key ? 'bg-indigo-600 text-white shadow-2xs' : 'bg-slate-100 text-slate-700 hover:bg-slate-200' }}"
                            >
                                {{ $label }}
                            </a>
                        @endforeach
                    </div>

                    {{-- Baris 2: Input Rentang Kustom, Filter Proyek & Filter Developer --}}
                    <div class="pt-3 border-t border-slate-100 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 items-end">
                        {{-- Tanggal Mulai --}}
                        <div>
                            <label for="start_date" class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">
                                Dari Tanggal
                            </label>
                            <input
                                type="date"
                                id="start_date"
                                name="start_date"
                                value="{{ $startDate ? $startDate->format('Y-m-d') : '' }}"
                                class="w-full rounded-lg border border-slate-300 px-3 py-1.5 text-xs text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 cursor-pointer"
                            />
                        </div>

                        {{-- Tanggal Selesai --}}
                        <div>
                            <label for="end_date" class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">
                                Sampai Tanggal
                            </label>
                            <input
                                type="date"
                                id="end_date"
                                name="end_date"
                                value="{{ $endDate ? $endDate->format('Y-m-d') : '' }}"
                                class="w-full rounded-lg border border-slate-300 px-3 py-1.5 text-xs text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 cursor-pointer"
                            />
                        </div>

                        {{-- Filter Proyek --}}
                        <div>
                            <label for="project_id" class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">
                                Proyek Terkait
                            </label>
                            <select
                                id="project_id"
                                name="project_id"
                                class="w-full rounded-lg border border-slate-300 px-3 py-1.5 text-xs text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 cursor-pointer"
                            >
                                <option value="">Semua Proyek</option>
                                @foreach ($projects as $proj)
                                    <option value="{{ $proj->id }}" {{ (string) $projectIdFilter === (string) $proj->id ? 'selected' : '' }}>
                                        [{{ $proj->code }}] {{ $proj->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Filter Developer & Tombol Submit --}}
                        <div class="flex items-center gap-2">
                            <div class="flex-1">
                                <label for="developer_id" class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">
                                    PIC Pengembang
                                </label>
                                <select
                                    id="developer_id"
                                    name="developer_id"
                                    class="w-full rounded-lg border border-slate-300 px-3 py-1.5 text-xs text-slate-800 focus:border-slate-900 focus:ring-1 focus:ring-slate-900 cursor-pointer"
                                >
                                    <option value="">Semua Developer</option>
                                    @foreach ($developers as $dev)
                                        <option value="{{ $dev->id }}" {{ (string) $developerIdFilter === (string) $dev->id ? 'selected' : '' }}>
                                            {{ $dev->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <input type="hidden" name="preset" value="custom">

                            <button
                                type="submit"
                                class="px-3.5 py-1.5 rounded-lg text-xs font-semibold bg-slate-900 text-white hover:bg-slate-800 transition cursor-pointer shrink-0 mt-5"
                            >
                                Terapkan
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Info Badge Periode Terpilih --}}
            <div class="flex items-center justify-between text-xs text-slate-500 px-1">
                <div class="flex items-center gap-2">
                    <span class="font-semibold text-slate-700">Rentang Periode Laporan:</span>
                    @if ($startDate && $endDate)
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                            {{ $startDate->isoFormat('D MMMM Y') }} &mdash; {{ $endDate->isoFormat('D MMMM Y') }}
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-0.5 rounded-md font-bold bg-slate-100 text-slate-700 border border-slate-200">
                            Semua Data Terdaftar
                        </span>
                    @endif
                </div>
                @if ($projectIdFilter || $developerIdFilter || $preset !== 'all')
                    <a href="{{ route('reports.index') }}" class="text-indigo-600 hover:text-indigo-800 hover:underline font-semibold no-print">
                        Reset Semua Filter
                    </a>
                @endif
            </div>

            {{-- 2. Kartu Metrik Ringkasan Kinerja (Bersih, Tegas, 4 Kolom) --}}
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                {{-- Metrik 1: Progres Tugas --}}
                <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-xs card-report">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Tugas Selesai</span>
                        <div class="p-2 rounded-lg bg-emerald-50 text-emerald-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $metrics['done_tasks'] }}</span>
                        <span class="text-xs text-slate-500 font-medium">/ {{ $metrics['total_tasks'] }} Tugas ({{ $metrics['task_rate'] }}%)</span>
                    </div>
                    {{-- Mini Progress Bar --}}
                    <div class="w-full bg-slate-100 rounded-full h-1.5 mt-2 overflow-hidden">
                        <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $metrics['task_rate'] }}%"></div>
                    </div>
                </div>

                {{-- Metrik 2: Resolusi Bug --}}
                <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-xs card-report">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Bug Tertangani</span>
                        <div class="p-2 rounded-lg bg-rose-50 text-rose-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.002A11.959 11.959 0 0112 2.714zm0 13.036a.75.75 0 100-1.5.75.75 0 000 1.5z" />
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $metrics['resolved_bugs'] }}</span>
                        <span class="text-xs text-slate-500 font-medium">/ {{ $metrics['total_bugs'] }} Bug ({{ $metrics['bug_rate'] }}%)</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-1.5 mt-2 overflow-hidden">
                        <div class="bg-rose-500 h-1.5 rounded-full" style="width: {{ $metrics['bug_rate'] }}%"></div>
                    </div>
                </div>

                {{-- Metrik 3: Rilis & Deployment --}}
                <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-xs card-report">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Rilis Repositori</span>
                        <div class="p-2 rounded-lg bg-indigo-50 text-indigo-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 003 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.386a10.96 10.96 0 003.882-3.882c.486-.827.313-1.908-.386-2.607L9.762 3.659A2.25 2.25 0 009.568 3z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $metrics['total_releases'] }}</span>
                        <span class="text-xs text-slate-500 font-medium">Versi Dirilis</span>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">Audit log rilis kode sistem</p>
                </div>

                {{-- Metrik 4: Tindak Lanjut Evaluasi --}}
                <div class="bg-white rounded-xl border border-slate-200 p-4 shadow-xs card-report">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Tindak Lanjut Evaluasi</span>
                        <div class="p-2 rounded-lg bg-blue-50 text-blue-600">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 6.75h12M8.25 12h12m-12 5.25h12M3.75 6.75h.007v.008H3.75V6.75zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 12h.007v.008H3.75V12zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zM3.75 17.25h.007v.008H3.75v-.008zm.375 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="mt-2 flex items-baseline gap-2">
                        <span class="text-2xl font-extrabold text-slate-900 tracking-tight">{{ $metrics['completed_action_items'] }}</span>
                        <span class="text-xs text-slate-500 font-medium">/ {{ $metrics['total_action_items'] }} Action Items Selesai</span>
                    </div>
                    <p class="text-[11px] text-slate-400 mt-1">Dari {{ $metrics['total_evaluations'] }} evaluasi periode</p>
                </div>
            </div>

            {{-- 3. Bagian 1: Rekapitulasi Kinerja Per Proyek --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs card-report">
                <div class="px-5 py-4 border-b border-slate-200">
                    <h2 class="text-sm sm:text-base font-bold text-slate-900">Rekapitulasi Kinerja Berdasarkan Proyek</h2>
                    <p class="text-xs text-slate-500 mt-0.5">Ringkasan perbandingan produktivitas tugas, penanganan bug, dan rilis per sistem.</p>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50/75 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                <th scope="col" class="py-3.5 pl-5 pr-3">Kode & Proyek</th>
                                <th scope="col" class="py-3.5 px-3">Status Proyek</th>
                                <th scope="col" class="py-3.5 px-3">Progres Tugas Selesai</th>
                                <th scope="col" class="py-3.5 px-3">Tingkat Resolusi Bug</th>
                                <th scope="col" class="py-3.5 pl-3 pr-5 text-right">Rilis</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 text-sm">
                            @forelse ($projectBreakdown as $pb)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    {{-- Kode & Nama Proyek --}}
                                    <td class="py-3.5 pl-5 pr-3 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold text-white shrink-0 shadow-2xs"
                                                style="background-color: {{ $pb['color'] }};"
                                            >
                                                [{{ $pb['code'] }}]
                                            </span>
                                            <span class="font-bold text-slate-900 text-xs sm:text-sm">
                                                {{ $pb['name'] }}
                                            </span>
                                        </div>
                                    </td>

                                    {{-- Status Proyek --}}
                                    <td class="py-3.5 px-3 whitespace-nowrap">
                                        @if ($pb['status'] === 'active')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                Aktif
                                            </span>
                                        @elseif ($pb['status'] === 'maintenance')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-700 border border-amber-200">
                                                Pemeliharaan
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-600 border border-slate-200">
                                                Diarsipkan
                                            </span>
                                        @endif
                                    </td>

                                    {{-- Progres Tugas Selesai --}}
                                    <td class="py-3.5 px-3 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="w-24 sm:w-32 bg-slate-100 rounded-full h-2 overflow-hidden shrink-0">
                                                <div class="bg-indigo-600 h-2 rounded-full" style="width: {{ $pb['tasks_rate'] }}%"></div>
                                            </div>
                                            <span class="text-xs font-semibold text-slate-700">
                                                {{ $pb['tasks_done'] }}/{{ $pb['tasks_total'] }} ({{ $pb['tasks_rate'] }}%)
                                            </span>
                                        </div>
                                    </td>

                                    {{-- Resolusi Bug --}}
                                    <td class="py-3.5 px-3 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="w-24 sm:w-32 bg-slate-100 rounded-full h-2 overflow-hidden shrink-0">
                                                <div class="bg-rose-500 h-2 rounded-full" style="width: {{ $pb['bugs_rate'] }}%"></div>
                                            </div>
                                            <span class="text-xs font-semibold text-slate-700">
                                                {{ $pb['bugs_resolved'] }}/{{ $pb['bugs_total'] }} ({{ $pb['bugs_rate'] }}%)
                                            </span>
                                        </div>
                                    </td>

                                    {{-- Total Rilis --}}
                                    <td class="py-3.5 pl-3 pr-5 text-right whitespace-nowrap font-semibold text-slate-800 text-xs sm:text-sm">
                                        {{ $pb['releases_total'] }} Rilis
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-8 text-center text-xs text-slate-400 italic">
                                        Belum ada data proyek terdaftar.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 4. Bagian 2: Rincian Tugas Pengembangan dalam Periode --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs card-report">
                <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm sm:text-base font-bold text-slate-900">Rincian Tugas Pengembangan ({{ $tasks->count() }})</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Daftar tugas yang aktif atau terselesaikan dalam periode terpilih.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50/75 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                <th scope="col" class="py-3.5 pl-5 pr-3">Proyek</th>
                                <th scope="col" class="py-3.5 px-3">Judul Tugas</th>
                                <th scope="col" class="py-3.5 px-3">Tipe</th>
                                <th scope="col" class="py-3.5 px-3">PIC Pengembang</th>
                                <th scope="col" class="py-3.5 px-3">Status</th>
                                <th scope="col" class="py-3.5 pl-3 pr-5 text-right">Target Selesai</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 text-sm">
                            @forelse ($tasks as $task)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    {{-- Proyek --}}
                                    <td class="py-3 pl-5 pr-3 whitespace-nowrap">
                                        @if ($task->project)
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold text-white shadow-2xs"
                                                style="background-color: {{ $task->project->color ?? '#4F46E5' }};"
                                            >
                                                [{{ $task->project->code }}]
                                            </span>
                                        @else
                                            <span class="text-xs text-slate-400 italic">-</span>
                                        @endif
                                    </td>

                                    {{-- Judul Tugas --}}
                                    <td class="py-3 px-3 max-w-sm">
                                        <p class="font-semibold text-slate-900 text-xs line-clamp-1" title="{{ $task->title }}">
                                            {{ $task->title }}
                                        </p>
                                    </td>

                                    {{-- Tipe --}}
                                    <td class="py-3 px-3 whitespace-nowrap">
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold uppercase bg-slate-100 text-slate-700 border border-slate-200">
                                            {{ $task->task_type }}
                                        </span>
                                    </td>

                                    {{-- PIC --}}
                                    <td class="py-3 px-3 whitespace-nowrap text-xs text-slate-600">
                                        {{ $task->assignee ? $task->assignee->name : 'Belum Ditugaskan' }}
                                    </td>

                                    {{-- Status --}}
                                    <td class="py-3 px-3 whitespace-nowrap">
                                        @php
                                            $taskStatusColors = [
                                                'todo' => 'bg-slate-100 text-slate-700 border-slate-200',
                                                'in_progress' => 'bg-blue-50 text-blue-700 border-blue-200',
                                                'review' => 'bg-purple-50 text-purple-700 border-purple-200',
                                                'done' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                            ];
                                            $taskStatusLabels = [
                                                'todo' => 'To Do',
                                                'in_progress' => 'In Progress',
                                                'review' => 'Review',
                                                'done' => 'Done',
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold border {{ $taskStatusColors[$task->status] ?? 'bg-slate-100 text-slate-700' }}">
                                            {{ $taskStatusLabels[$task->status] ?? $task->status }}
                                        </span>
                                    </td>

                                    {{-- Target Selesai --}}
                                    <td class="py-3 pl-3 pr-5 text-right whitespace-nowrap text-xs text-slate-500 font-medium">
                                        {{ $task->target_date ? $task->target_date->isoFormat('D MMM Y') : '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-xs text-slate-400 italic">
                                        Tidak ada data tugas pada periode ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- 5. Bagian 3: Rincian Temuan Masalah & Bug dalam Periode --}}
            <div class="bg-white rounded-xl border border-slate-200 overflow-hidden shadow-xs card-report">
                <div class="px-5 py-4 border-b border-slate-200 flex items-center justify-between">
                    <div>
                        <h2 class="text-sm sm:text-base font-bold text-slate-900">Rincian Temuan Masalah & Bug ({{ $bugs->count() }})</h2>
                        <p class="text-xs text-slate-500 mt-0.5">Daftar temuan bug yang dilaporkan dan status penanganannya.</p>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200 bg-slate-50/75 text-[11px] font-bold uppercase tracking-wider text-slate-500">
                                <th scope="col" class="py-3.5 pl-5 pr-3">Proyek</th>
                                <th scope="col" class="py-3.5 px-3">Judul Temuan Bug</th>
                                <th scope="col" class="py-3.5 px-3">Tingkat Bahaya</th>
                                <th scope="col" class="py-3.5 px-3">PIC Penanganan</th>
                                <th scope="col" class="py-3.5 px-3">Status Bug</th>
                                <th scope="col" class="py-3.5 pl-3 pr-5 text-right">Status Solusi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 text-sm">
                            @forelse ($bugs as $bug)
                                <tr class="hover:bg-slate-50/80 transition-colors">
                                    {{-- Proyek --}}
                                    <td class="py-3 pl-5 pr-3 whitespace-nowrap">
                                        @if ($bug->project)
                                            <span
                                                class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold text-white shadow-2xs"
                                                style="background-color: {{ $bug->project->color ?? '#4F46E5' }};"
                                            >
                                                [{{ $bug->project->code }}]
                                            </span>
                                        @else
                                            <span class="text-xs text-slate-400 italic">-</span>
                                        @endif
                                    </td>

                                    {{-- Judul Bug --}}
                                    <td class="py-3 px-3 max-w-sm">
                                        <p class="font-semibold text-slate-900 text-xs line-clamp-1" title="{{ $bug->title }}">
                                            {{ $bug->title }}
                                        </p>
                                    </td>

                                    {{-- Keparahan --}}
                                    <td class="py-3 px-3 whitespace-nowrap">
                                        @php
                                            $sevBadges = [
                                                'low' => 'bg-slate-100 text-slate-700 border-slate-200',
                                                'medium' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                'high' => 'bg-orange-50 text-orange-700 border-orange-200',
                                                'critical' => 'bg-rose-50 text-rose-700 border-rose-200',
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold uppercase border {{ $sevBadges[$bug->severity] ?? 'bg-slate-100 text-slate-700' }}">
                                            {{ $bug->severity }}
                                        </span>
                                    </td>

                                    {{-- PIC --}}
                                    <td class="py-3 px-3 whitespace-nowrap text-xs text-slate-600">
                                        {{ $bug->assignee ? $bug->assignee->name : 'Belum Ditugaskan' }}
                                    </td>

                                    {{-- Status Bug --}}
                                    <td class="py-3 px-3 whitespace-nowrap">
                                        @php
                                            $bugStatusColors = [
                                                'open' => 'bg-rose-50 text-rose-700 border-rose-200',
                                                'in_progress' => 'bg-blue-50 text-blue-700 border-blue-200',
                                                'resolved' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
                                                'closed' => 'bg-slate-100 text-slate-600 border-slate-200',
                                                'reopened' => 'bg-amber-50 text-amber-700 border-amber-200',
                                            ];
                                            $bugStatusLabels = [
                                                'open' => 'Open',
                                                'in_progress' => 'In Progress',
                                                'resolved' => 'Resolved',
                                                'closed' => 'Closed',
                                                'reopened' => 'Reopened',
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold border {{ $bugStatusColors[$bug->status] ?? 'bg-slate-100 text-slate-700' }}">
                                            {{ $bugStatusLabels[$bug->status] ?? $bug->status }}
                                        </span>
                                    </td>

                                    {{-- Status Solusi --}}
                                    <td class="py-3 pl-3 pr-5 text-right whitespace-nowrap">
                                        @if ($bug->solution_notes)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                                Sudah Ada Solusi
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-slate-50 text-slate-500 border border-slate-200">
                                                Belum Ada Solusi
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="py-8 text-center text-xs text-slate-400 italic">
                                        Tidak ada data temuan bug pada periode ini.
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

{{-- JAVASCRIPT LOGIC (Sidebar Drawer Toggle) --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
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
    });
</script>

</body>
</html>
