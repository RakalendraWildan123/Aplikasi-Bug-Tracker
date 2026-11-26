<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Papan Kanban &mdash; {{ config('app.name', 'Aplikasi Bug Tracker') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @include('partials.speed-meta')
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

                    {{-- Fitur Papan Kanban (Active State) --}}
                    <a
                        href="{{ route('kanban.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3.5 py-2.5 text-sm font-semibold transition bg-slate-800 text-white border border-slate-700/70 shadow-xs"
                    >
                        <svg class="w-5 h-5 text-indigo-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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

        {{-- Konten Halaman Papan Kanban --}}
        <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto flex flex-col">

            {{-- Header Halaman & Filter Instan --}}
            <div class="mb-5 flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 pb-4 border-b border-slate-200">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">Papan Kanban</h1>
                    <p class="mt-1 text-sm text-slate-600">Visualisasi alur pengerjaan tugas tim pengembang (To Do, In Progress, Review, Done).</p>
                </div>

                {{-- Dropdown Filter Instan Per Project & Per Developer --}}
                <div class="flex flex-wrap items-center gap-2.5">
                    {{-- Filter Proyek Instan --}}
                    <div class="flex items-center gap-1.5">
                        <label for="filter-project" class="text-xs font-semibold text-slate-500 shrink-0">Proyek:</label>
                        <select
                            id="filter-project"
                            name="project_id"
                            class="rounded-lg border border-slate-300 bg-white py-2 pl-3 pr-8 text-xs sm:text-sm font-medium text-slate-700 shadow-2xs transition focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 cursor-pointer"
                        >
                            <option value="">Semua Proyek</option>
                            @foreach ($projects as $proj)
                                <option value="{{ $proj->id }}" {{ (string)$selectedProject === (string)$proj->id ? 'selected' : '' }}>
                                    [{{ $proj->code }}] {{ $proj->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filter Developer Instan --}}
                    <div class="flex items-center gap-1.5">
                        <label for="filter-developer" class="text-xs font-semibold text-slate-500 shrink-0">Developer:</label>
                        <select
                            id="filter-developer"
                            name="developer_id"
                            class="rounded-lg border border-slate-300 bg-white py-2 pl-3 pr-8 text-xs sm:text-sm font-medium text-slate-700 shadow-2xs transition focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 cursor-pointer"
                        >
                            <option value="">Semua Developer</option>
                            @foreach ($developers as $dev)
                                <option value="{{ $dev->id }}" {{ (string)($selectedDeveloper ?? '') === (string)$dev->id ? 'selected' : '' }}>
                                    {{ $dev->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Tombol Reset Filter Instan --}}
                    <button
                        type="button"
                        id="btn-reset-filters"
                        class="{{ ($selectedProject || ($selectedDeveloper ?? false)) ? '' : 'hidden' }} inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition cursor-pointer"
                    >
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <span>Reset Filter</span>
                    </button>

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

            {{-- Notifikasi Permintaan Persetujuan Serah Terima Tugas --}}
            <div class="mb-4">
                @include('partials.pending-handovers', ['routePrefix' => 'kanban.tasks'])
            </div>

            {{-- TOMBOL NAVIGASI CEPAT DI PAPAN KANBAN (Lompat / Fokus Kolom) --}}
            <div class="mb-5 flex items-center justify-between gap-3 flex-wrap bg-white border border-slate-200 rounded-xl p-3 shadow-2xs">
                <div class="flex items-center gap-2 overflow-x-auto pb-0.5 max-w-full">
                    <span class="text-xs font-bold text-slate-500 uppercase tracking-wider shrink-0 mr-1 flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5"/>
                        </svg>
                        Navigasi Cepat:
                    </span>

                    {{-- Tombol Semua Kolom --}}
                    <button
                        type="button"
                        class="btn-quick-nav inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold bg-slate-900 text-white shadow-xs transition cursor-pointer"
                        data-target="all"
                    >
                        Semua Kolom
                    </button>

                    {{-- Tombol Kolom To Do --}}
                    <button
                        type="button"
                        class="btn-quick-nav inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 transition cursor-pointer"
                        data-target="todo"
                    >
                        <span class="w-2 h-2 rounded-full bg-slate-500"></span>
                        <span>To Do</span>
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-slate-100 text-slate-700 font-bold border border-slate-200 quick-count-todo">
                            {{ $columns['todo']['tasks']->count() }}
                        </span>
                    </button>

                    {{-- Tombol Kolom In Progress --}}
                    <button
                        type="button"
                        class="btn-quick-nav inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 transition cursor-pointer"
                        data-target="in_progress"
                    >
                        <span class="w-2 h-2 rounded-full bg-sky-500"></span>
                        <span>In Progress</span>
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-sky-50 text-sky-700 font-bold border border-sky-200 quick-count-in_progress">
                            {{ $columns['in_progress']['tasks']->count() }}
                        </span>
                    </button>

                    {{-- Tombol Kolom Review --}}
                    <button
                        type="button"
                        class="btn-quick-nav inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 transition cursor-pointer"
                        data-target="review"
                    >
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        <span>Review</span>
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-amber-50 text-amber-700 font-bold border border-amber-200 quick-count-review">
                            {{ $columns['review']['tasks']->count() }}
                        </span>
                    </button>

                    {{-- Tombol Kolom Done --}}
                    <button
                        type="button"
                        class="btn-quick-nav inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold bg-white border border-slate-200 text-slate-700 hover:bg-slate-100 transition cursor-pointer"
                        data-target="done"
                    >
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>Done</span>
                        <span class="px-1.5 py-0.2 rounded-full text-[10px] bg-emerald-50 text-emerald-700 font-bold border border-emerald-200 quick-count-done">
                            {{ $columns['done']['tasks']->count() }}
                        </span>
                    </button>
                </div>

                {{-- Indikator Total Tugas --}}
                <span class="text-xs font-semibold text-slate-500">
                    Total: <strong class="text-slate-900 total-task-count">{{ $columns['todo']['tasks']->count() + $columns['in_progress']['tasks']->count() + $columns['review']['tasks']->count() + $columns['done']['tasks']->count() }}</strong> Tugas
                </span>
            </div>

            {{-- Pesan Status Berhasil --}}
            <div id="toast-message" class="hidden mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-xs sm:text-sm font-medium text-emerald-800 items-center justify-between transition-all">
                <span id="toast-text">Status tugas berhasil diperbarui.</span>
            </div>

            {{-- Papan Kanban (4 Kolom Tegas, Ramah Desktop & Tablet) --}}
            @php
                $statusBorderClasses = [
                    'todo' => 'border-l-slate-400',
                    'in_progress' => 'border-l-sky-500',
                    'review' => 'border-l-amber-500',
                    'done' => 'border-l-emerald-500',
                ];
            @endphp
            <div id="kanban-board-container" class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4.5 items-start w-full">

                @foreach ($columns as $colKey => $column)
                    <div
                        id="column-{{ $colKey }}"
                        class="kanban-column w-full flex flex-col rounded-xl border border-slate-200 bg-slate-100/70 shadow-xs overflow-hidden transition-all duration-200"
                        data-column-status="{{ $colKey }}"
                    >
                        {{-- Header Kolom --}}
                        <div class="px-4 py-3.5 bg-white border-b-2 {{ $column['header_border'] }} flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <span class="w-2.5 h-2.5 rounded-full {{ $column['dot_class'] }}"></span>
                                <h3 class="font-bold text-sm text-slate-900 tracking-tight">{{ $column['title'] }}</h3>
                            </div>
                            <span
                                class="column-count px-2.5 py-0.5 rounded-full text-xs font-bold border {{ $column['badge_class'] }}"
                            >
                                {{ $column['tasks']->count() }}
                            </span>
                        </div>

                        {{-- Dropzone Kolom Berisi Kartu-Kartu Tugas --}}
                        <div
                            class="kanban-dropzone p-3 space-y-3 min-h-[460px] flex-1 overflow-y-auto transition-colors"
                            data-status="{{ $colKey }}"
                        >
                            @forelse ($column['tasks'] as $task)
                                @php
                                    $isAssignee = (int)$task->assigned_to === (int)$user->id;
                                    $todayWib = \Carbon\Carbon::now('Asia/Jakarta')->startOfDay();
                                    $targetDay = $task->target_date ? \Carbon\Carbon::parse($task->target_date)->startOfDay() : null;
                                    $isActiveDeadline = $targetDay && $task->status !== 'done';
                                    $isOverdue = $isActiveDeadline && $targetDay->lt($todayWib);
                                    $isDueToday = $isActiveDeadline && $targetDay->equalTo($todayWib);
                                @endphp
                                <div
                                    class="kanban-card bg-white border {{ $isOverdue ? 'border-rose-300 ring-1 ring-rose-200' : 'border-slate-200' }} border-l-4 {{ $statusBorderClasses[$task->status] ?? 'border-l-slate-400' }} rounded-xl p-4 shadow-2xs hover:shadow-sm hover:border-y-slate-300 hover:border-r-slate-300 transition-all select-none {{ $isAssignee ? 'cursor-grab active:cursor-grabbing' : 'cursor-default' }} relative"
                                    draggable="{{ $isAssignee ? 'true' : 'false' }}"
                                    id="card-task-{{ $task->id }}"
                                    data-task-id="{{ $task->id }}"
                                    data-current-status="{{ $task->status }}"
                                    data-project-id="{{ $task->project_id ?? '' }}"
                                    data-assigned-to="{{ $task->assigned_to ?? '' }}"
                                    data-target-date="{{ $task->target_date ? \Carbon\Carbon::parse($task->target_date)->format('Y-m-d') : '' }}"
                                    data-can-move="{{ $isAssignee ? 'true' : 'false' }}"
                                >
                                    {{-- Baris Atas: Badge Proyek & Modul, Tipe Tugas & TOMBOL EDIT KARTU --}}
                                    <div class="flex items-center justify-between gap-2 mb-2">
                                        <div class="flex items-center gap-1.5 truncate max-w-[170px]">
                                            @if ($task->project)
                                                <span class="card-project-badge inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200 shrink-0">
                                                    <span class="w-1.5 h-1.5 rounded-full shrink-0" style="background-color: {{ $task->project->color ?? '#4F46E5' }}"></span>
                                                    <span class="truncate">{{ $task->project->code }}</span>
                                                </span>
                                            @endif
                                            @if ($task->module)
                                                <span class="card-module-name text-[10px] font-medium text-slate-500 truncate" title="{{ $task->module->name }}">
                                                    {{ $task->module->name }}
                                                </span>
                                            @endif
                                        </div>

                                        <div class="flex items-center gap-1.5 shrink-0">
                                            {{-- Badge Tipe Tugas --}}
                                            @php
                                                $typeBadges = [
                                                    'feature' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                                    'enhancement' => 'bg-violet-50 text-violet-700 border-violet-200',
                                                    'refactor' => 'bg-amber-50 text-amber-700 border-amber-200',
                                                    'maintenance' => 'bg-slate-100 text-slate-700 border-slate-200',
                                                    'bug' => 'bg-rose-50 text-rose-700 border-rose-200',
                                                ];
                                                $typeLabels = [
                                                    'feature' => 'Fitur',
                                                    'enhancement' => 'Peningkatan',
                                                    'refactor' => 'Refaktor',
                                                    'maintenance' => 'Maintenance',
                                                    'bug' => 'Bug',
                                                ];
                                            @endphp
                                            <span class="card-type-badge px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider border {{ $typeBadges[$task->task_type] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}">
                                                {{ $typeLabels[$task->task_type] ?? $task->task_type }}
                                            </span>
                                        </div>
                                    </div>

                                    {{-- Judul Tugas --}}
                                    <h4 class="card-title font-bold text-slate-900 text-sm leading-snug mb-1.5">
                                        {{ $task->title }}
                                    </h4>

                                    {{-- Deskripsi Ringkas (Satu atau Dua Kalimat Inti) --}}
                                    <p class="card-description text-xs text-slate-600 line-clamp-2 mb-3 {{ $task->description ? '' : 'hidden' }}">
                                        {{ $task->description }}
                                    </p>

                                    {{-- Catatan Blocker (Jika Ada) --}}
                                    <div class="card-blocker-container mb-3 rounded-lg border border-rose-200 bg-rose-50 p-2 text-[11px] text-rose-800 leading-tight {{ $task->blocker_notes ? '' : 'hidden' }}">
                                        <span class="font-semibold text-rose-900">Kendala:</span> <span class="card-blocker-text">{{ $task->blocker_notes }}</span>
                                    </div>

                                    {{-- Footer Kartu: Deadline & Avatar Assignee --}}
                                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                                        {{-- Target Selesai --}}
                                        <div class="flex items-center gap-1.5 flex-wrap">
                                            <svg class="card-deadline-icon w-3.5 h-3.5 {{ $isOverdue ? 'text-rose-500' : ($isDueToday ? 'text-amber-500' : 'text-slate-400') }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5"/>
                                            </svg>
                                            <span class="card-target-date font-medium {{ $isOverdue ? 'text-rose-600 font-bold' : ($isDueToday ? 'text-amber-700 font-semibold' : 'text-slate-600') }} text-[11px]">
                                                {{ $task->target_date ? \Carbon\Carbon::parse($task->target_date)->translatedFormat('d M Y') : 'Tanpa batas' }}
                                            </span>
                                            <span class="card-overdue-badge {{ ($isOverdue || $isDueToday) ? 'inline-flex' : 'hidden' }} items-center px-1.5 py-0.2 rounded text-[10px] font-bold border {{ $isOverdue ? 'bg-rose-50 text-rose-700 border-rose-200' : ($isDueToday ? 'bg-amber-50 text-amber-700 border-amber-200' : '') }}">
                                                {{ $isOverdue ? 'Terlambat' : ($isDueToday ? 'Deadline Hari Ini' : '') }}
                                            </span>
                                        </div>

                                        {{-- Assignee Pengembang --}}
                                        <div class="card-assignee-container flex items-center gap-1.5" title="Ditugaskan kepada: {{ $task->assignee?->name ?? 'Belum ditugaskan' }}">
                                            @if ($task->assignee)
                                                @if ($task->assignee->foto_profil_url)
                                                    <img src="{{ $task->assignee->foto_profil_url }}" alt="{{ $task->assignee->name }}" class="w-6 h-6 rounded-full object-cover border border-slate-200">
                                                @else
                                                    <div class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-bold text-white shadow-2xs" style="background-color: {{ $task->assignee->avatar_bg ?? '#1E293B' }}">
                                                        {{ $task->assignee->avatar ?? 'DV' }}
                                                    </div>
                                                @endif
                                                <span class="card-assignee-name text-[11px] font-semibold text-slate-700 truncate max-w-[80px]">
                                                    {{ $task->assignee->name }}
                                                </span>
                                            @else
                                                <span class="card-assignee-name text-[11px] text-slate-400 italic">Belum ditugaskan</span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Quick Move Status Selector, Tanda Panah Navigasi & Ambil Alih Tugas (Ramah Tablet & Fullscreen Desktop) --}}
                                    <div class="mt-3 pt-2.5 border-t border-slate-100 space-y-2">
                                        {{-- Baris Navigasi Status (Panah Kiri, Dropdown Status Responsif, Panah Kanan) --}}
                                        <div class="flex items-center gap-1.5 w-full">
                                            {{-- Tombol Panah Kiri: Pindah ke Kolom Sebelumnya --}}
                                            <button
                                                type="button"
                                                draggable="false"
                                                class="btn-move-prev w-8 h-8 rounded-lg border border-slate-200 transition flex items-center justify-center shrink-0 {{ (! $isAssignee || $task->status === 'todo') ? 'opacity-25 cursor-not-allowed bg-slate-100 text-slate-300' : 'bg-slate-50 cursor-pointer hover:bg-slate-100 hover:text-slate-900 text-slate-600' }}"
                                                {{ (! $isAssignee || $task->status === 'todo') ? 'disabled' : '' }}
                                                @if (! $isAssignee)
                                                    title="Hanya orang yang mengerjakan task ini ({{ $task->assignee?->name ?? 'Belum Ditugaskan' }}) yang dapat memindahkannya"
                                                    aria-label="Tidak dapat memindahkan tugas orang lain"
                                                @elseif ($task->status === 'in_progress')
                                                    data-target-status="todo" title="Pindahkan ke To Do" aria-label="Pindahkan ke To Do"
                                                @elseif ($task->status === 'review')
                                                    data-target-status="in_progress" title="Pindahkan ke In Progress" aria-label="Pindahkan ke In Progress"
                                                @elseif ($task->status === 'done')
                                                    data-target-status="review" title="Pindahkan ke Review" aria-label="Pindahkan ke Review"
                                                @else
                                                    title="Sudah di kolom pertama (To Do)" aria-label="Sudah di kolom pertama"
                                                @endif
                                            >
                                                <svg class="w-4 h-4 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5L8.25 12l7.5-7.5"/>
                                                </svg>
                                            </button>

                                            {{-- Dropdown Pilihan Status Cepat (Lebar Fleksibel) --}}
                                            <select
                                                id="move-status-{{ $task->id }}"
                                                data-task-id="{{ $task->id }}"
                                                class="task-status-select flex-1 min-w-0 text-[11px] font-semibold border border-slate-200 rounded-lg py-1.5 px-2 text-slate-700 focus:outline-none focus:ring-1 focus:ring-slate-900 {{ $isAssignee ? 'bg-slate-50 cursor-pointer' : 'bg-slate-100/80 cursor-not-allowed opacity-60 text-slate-400' }}"
                                                {{ $isAssignee ? '' : 'disabled' }}
                                                title="{{ $isAssignee ? 'Pilih status tugas secara langsung' : 'Hanya orang yang mengerjakan task ini (' . ($task->assignee?->name ?? 'Belum Ditugaskan') . ') yang dapat memindahkannya' }}"
                                            >
                                                <option value="todo" {{ $task->status === 'todo' ? 'selected' : '' }}>To Do</option>
                                                <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                                <option value="review" {{ $task->status === 'review' ? 'selected' : '' }}>Review</option>
                                                <option value="done" {{ $task->status === 'done' ? 'selected' : '' }}>Done</option>
                                            </select>

                                            {{-- Tombol Panah Kanan: Pindah ke Kolom Berikutnya --}}
                                            <button
                                                type="button"
                                                draggable="false"
                                                class="btn-move-next w-8 h-8 rounded-lg border border-slate-200 transition flex items-center justify-center shrink-0 {{ (! $isAssignee || $task->status === 'done') ? 'opacity-25 cursor-not-allowed bg-slate-100 text-slate-300' : 'bg-slate-50 cursor-pointer hover:bg-slate-100 hover:text-slate-900 text-slate-600' }}"
                                                {{ (! $isAssignee || $task->status === 'done') ? 'disabled' : '' }}
                                                @if (! $isAssignee)
                                                    title="Hanya orang yang mengerjakan task ini ({{ $task->assignee?->name ?? 'Belum Ditugaskan' }}) yang dapat memindahkannya"
                                                    aria-label="Tidak dapat memindahkan tugas orang lain"
                                                @elseif ($task->status === 'todo')
                                                    data-target-status="in_progress" title="Pindahkan ke In Progress" aria-label="Pindahkan ke In Progress"
                                                @elseif ($task->status === 'in_progress')
                                                    data-target-status="review" title="Pindahkan ke Review" aria-label="Pindahkan ke Review"
                                                @elseif ($task->status === 'review')
                                                    data-target-status="done" title="Pindahkan ke Done" aria-label="Pindahkan ke Done"
                                                @else
                                                    title="Sudah di kolom terakhir (Done)" aria-label="Sudah di kolom terakhir"
                                                @endif
                                            >
                                                <svg class="w-4 h-4 pointer-events-none" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 4.5l7.5 7.5-7.5 7.5"/>
                                                </svg>
                                            </button>
                                        </div>

                                        {{-- Tombol Ambil Alih / Persetujuan / Indikator Pemilik Tugas --}}
                                        <div class="card-takeover-action w-full">
                                            @if (! empty($task->pending_handover))
                                                @php $pending = $task->pending_handover; @endphp
                                                @if ((int)($pending['approver_id'] ?? 0) === (int)$user->id)
                                                    <div class="space-y-1.5 w-full">
                                                        <div class="text-[10px] font-semibold text-amber-800 bg-amber-50 border border-amber-200 rounded-lg px-2 py-1 text-center">
                                                            {{ ($pending['type'] ?? '') === 'transfer' ? 'Dari ' . ($pending['requested_by_name'] ?? 'Rekan') : 'Diminta oleh ' . ($pending['requested_by_name'] ?? 'Rekan') }}
                                                        </div>
                                                        <div class="grid grid-cols-2 gap-1.5">
                                                            <form method="POST" action="{{ route('kanban.tasks.handover.approve', $task->id) }}">
                                                                @csrf
                                                                <button type="submit" class="w-full py-1 px-2 text-[11px] font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-lg transition cursor-pointer">✓ Setujui</button>
                                                            </form>
                                                            <form method="POST" action="{{ route('kanban.tasks.handover.reject', $task->id) }}">
                                                                @csrf
                                                                <button type="submit" class="w-full py-1 px-2 text-[11px] font-semibold text-rose-700 bg-rose-50 hover:bg-rose-100 border border-rose-200 rounded-lg transition cursor-pointer">✕ Tolak</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                @elseif ((int)($pending['requested_by_id'] ?? 0) === (int)$user->id)
                                                    <div class="flex items-center justify-between gap-1.5 w-full">
                                                        <span class="flex-1 text-center text-[10px] font-semibold text-amber-800 bg-amber-50 border border-amber-200 rounded-lg py-1 px-2 truncate">
                                                            Menunggu {{ $pending['approver_name'] ?? 'Persetujuan' }}
                                                        </span>
                                                        <form method="POST" action="{{ route('kanban.tasks.handover.cancel', $task->id) }}">
                                                            @csrf
                                                            <button type="submit" class="px-2 py-1 text-[10px] font-semibold text-slate-600 hover:text-rose-700 bg-slate-100 hover:bg-rose-50 border border-slate-200 rounded-lg transition cursor-pointer">Batal</button>
                                                        </form>
                                                    </div>
                                                @else
                                                    <div class="w-full text-center text-[10px] font-semibold text-amber-700 bg-amber-50 border border-amber-200 rounded-lg py-1 px-2">
                                                        Menunggu Persetujuan
                                                    </div>
                                                @endif
                                            @elseif ($task->assigned_to && (int)$task->assigned_to !== (int)$user->id)
                                                <button
                                                    type="button"
                                                    class="btn-takeover-task w-full justify-center text-[11px] font-semibold text-amber-700 hover:text-amber-800 bg-amber-50 hover:bg-amber-100 border border-amber-200/80 rounded-lg px-2.5 py-1.5 transition flex items-center gap-1.5 cursor-pointer"
                                                    data-task-id="{{ $task->id }}"
                                                    data-task-title="{{ $task->title }}"
                                                    data-project-name="{{ $task->project?->name }}"
                                                    data-project-code="{{ $task->project?->code }}"
                                                    data-developer-id="{{ $task->assigned_to }}"
                                                    data-developer-name="{{ $task->assignee?->name ?? 'Belum ditugaskan' }}"
                                                    data-developer-role="{{ $task->assignee?->role_title ?? $task->assignee?->roleLabel() }}"
                                                    data-developer-avatar="{{ $task->assignee?->avatar ?? 'DV' }}"
                                                    data-developer-avatar-bg="{{ $task->assignee?->avatar_bg ?? '#1E293B' }}"
                                                    data-developer-photo="{{ $task->assignee?->foto_profil_url ?? '' }}"
                                                    title="Ambil Alih Tugas dari {{ $task->assignee?->name }}"
                                                >
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/>
                                                    </svg>
                                                    <span>Ambil Alih Tugas</span>
                                                </button>
                                            @elseif ($task->assigned_to && ((int)$task->assigned_to === (int)$user->id || $user->isAdmin()))
                                                <div class="space-y-1.5 w-full">
                                                    @if ((int)$task->assigned_to === (int)$user->id)
                                                        <div class="w-full flex items-center justify-center gap-1.5 text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg py-1 px-2">
                                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                            <span>Tugas Anda</span>
                                                        </div>
                                                    @endif
                                                    <button
                                                        type="button"
                                                        class="btn-transfer-task w-full justify-center text-[11px] font-semibold text-indigo-700 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 rounded-lg px-2.5 py-1.5 transition flex items-center gap-1.5 cursor-pointer shadow-2xs"
                                                        data-task-id="{{ $task->id }}"
                                                        data-task-title="{{ $task->title }}"
                                                        data-project-name="{{ $task->project?->name }}"
                                                        data-project-code="{{ $task->project?->code }}"
                                                        data-current-assignee-id="{{ $task->assigned_to }}"
                                                        data-current-assignee-name="{{ $task->assignee?->name ?? 'Anda' }}"
                                                        title="Alihkan Tugas ke Rekan Tim"
                                                    >
                                                        <svg class="w-3.5 h-3.5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/>
                                                        </svg>
                                                        <span>Alihkan ke Rekan</span>
                                                    </button>
                                                </div>
                                            @else
                                                <button
                                                    type="button"
                                                    class="btn-takeover-task w-full justify-center text-[11px] font-semibold text-indigo-700 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 rounded-lg px-2.5 py-1.5 transition flex items-center gap-1.5 cursor-pointer"
                                                    data-task-id="{{ $task->id }}"
                                                    data-task-title="{{ $task->title }}"
                                                    data-project-name="{{ $task->project?->name }}"
                                                    data-project-code="{{ $task->project?->code }}"
                                                    data-developer-id=""
                                                    data-developer-name="Belum Ditugaskan"
                                                    data-developer-role="Tersedia"
                                                    data-developer-avatar="--"
                                                    title="Ambil Tugas Ini"
                                                >
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                                    </svg>
                                                    <span>Ambil Tugas</span>
                                                </button>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="empty-placeholder flex flex-col items-center justify-center h-48 border-2 border-dashed border-slate-200 rounded-xl p-4 text-center text-slate-400">
                                    <svg class="w-8 h-8 mb-2 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2.25 2.25 0 01-2.25-2.25V5.25A2.25 2.25 0 017 3h10a2.25 2.25 0 012.25 2.25v13.5A2.25 2.25 0 0119 21z"/>
                                    </svg>
                                    <p class="text-xs font-medium">Belum ada tugas</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                @endforeach

            </div>

        </main>
    </div>

    {{-- MODAL POP-UP EDIT TUGAS (Tidak Pindah Halaman) --}}
    <div
        id="modal-edit-task"
        class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden"
        aria-labelledby="modal-edit-title"
        role="dialog"
        aria-modal="true"
    >
        <div class="bg-white border border-slate-200 rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden transition-all transform duration-200">
            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-slate-50">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-slate-900 text-white flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                        </svg>
                    </div>
                    <div>
                        <h3 id="modal-edit-title" class="text-base font-bold text-slate-900 leading-tight">Edit Tugas</h3>
                        <p id="modal-task-project" class="text-xs text-slate-500 font-medium">Proyek</p>
                    </div>
                </div>
                <button
                    type="button"
                    id="btn-close-edit-modal"
                    class="text-slate-400 hover:text-slate-700 p-1.5 rounded-lg hover:bg-slate-200/60 transition cursor-pointer"
                    aria-label="Tutup"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Form Edit Tugas --}}
            <form id="form-edit-task" class="p-6 space-y-4 max-h-[80vh] overflow-y-auto">
                <input type="hidden" id="edit-task-id" value="">

                {{-- Alert Error Validasi --}}
                <div id="edit-task-error" class="hidden rounded-lg border border-rose-200 bg-rose-50 px-3.5 py-2.5 text-xs text-rose-800">
                    <span id="edit-task-error-text">Terjadi kesalahan validasi.</span>
                </div>

                {{-- 1. Judul Tugas --}}
                <div>
                    <label for="edit-task-title" class="block text-xs font-semibold text-slate-800 mb-1">
                        Judul Tugas <span class="text-rose-500">*</span>
                    </label>
                    <input
                        id="edit-task-title"
                        type="text"
                        required
                        class="block w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-900 transition focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900"
                        placeholder="Masukkan judul tugas"
                    >
                </div>

                {{-- 2. Deskripsi Tugas --}}
                <div>
                    <label for="edit-task-desc" class="block text-xs font-semibold text-slate-800 mb-1">
                        Deskripsi Tugas
                    </label>
                    <textarea
                        id="edit-task-desc"
                        rows="3"
                        class="block w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-900 transition focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 resize-none"
                        placeholder="Rincian deskripsi tugas"
                    ></textarea>
                </div>

                {{-- 3. Row Tipe & Status --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="edit-task-type" class="block text-xs font-semibold text-slate-800 mb-1">
                            Tipe Tugas <span class="text-rose-500">*</span>
                        </label>
                        <select
                            id="edit-task-type"
                            required
                            class="block w-full rounded-lg border border-slate-300 py-2 pl-3 pr-8 text-sm text-slate-900 transition focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 cursor-pointer"
                        >
                            <option value="feature">Fitur (Feature)</option>
                            <option value="enhancement">Peningkatan (Enhancement)</option>
                            <option value="refactor">Refaktor (Refactor)</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="bug">Bug</option>
                        </select>
                    </div>

                    <div>
                        <label for="edit-task-status" class="block text-xs font-semibold text-slate-800 mb-1">
                            Status <span class="text-rose-500">*</span>
                        </label>
                        <select
                            id="edit-task-status"
                            required
                            class="block w-full rounded-lg border border-slate-300 py-2 pl-3 pr-8 text-sm text-slate-900 transition focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 cursor-pointer"
                        >
                            <option value="todo">To Do</option>
                            <option value="in_progress">In Progress</option>
                            <option value="review">Review</option>
                            <option value="done">Done</option>
                        </select>
                    </div>
                </div>

                {{-- 4. Row Deadline & Assignee --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="edit-task-target-date" class="block text-xs font-semibold text-slate-800 mb-1">
                            Target Selesai (Deadline)
                        </label>
                        <input
                            id="edit-task-target-date"
                            type="date"
                            class="block w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-900 transition focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 cursor-pointer"
                        >
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-800 mb-1">
                            Penanggung Jawab
                        </label>
                        <div class="rounded-lg border border-slate-200 bg-slate-50 px-3.5 py-2 text-sm text-slate-700 flex items-center justify-between">
                            <div class="flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/>
                                </svg>
                                <span id="display-kanban-assigned-name" class="font-semibold text-slate-900">Belum ditugaskan</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 5. Catatan Kendala (Blocker Notes) --}}
                <div>
                    <label for="edit-task-blocker" class="block text-xs font-semibold text-slate-800 mb-1">
                        Catatan Kendala (Blocker Notes)
                    </label>
                    <input
                        id="edit-task-blocker"
                        type="text"
                        placeholder="Contoh: Menunggu akses kredensial API"
                        class="block w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-900 transition focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900"
                    >
                </div>

                {{-- Tombol Aksi Modal --}}
                <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-200">
                    <button
                        type="button"
                        id="btn-cancel-edit-task"
                        class="px-4 py-2.5 rounded-lg border border-slate-300 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition cursor-pointer"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        id="btn-submit-edit-task"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-800 transition cursor-pointer shadow-sm"
                    >
                        <span id="submit-btn-text">Simpan Perubahan</span>
                        <svg id="submit-spinner" class="hidden w-4 h-4 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL POP-UP KONFIRMASI AMBIL ALIH TUGAS --}}
    <div
        id="modal-takeover-task"
        class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden"
        aria-labelledby="modal-takeover-title"
        role="dialog"
        aria-modal="true"
    >
        <div class="bg-white border border-slate-200 rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden transition-all transform duration-200">
            {{-- Header Modal --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-amber-50/60">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-amber-100 text-amber-700 border border-amber-200/80 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/>
                        </svg>
                    </div>
                    <div>
                        <h3 id="modal-takeover-title" class="text-base font-bold text-slate-900 leading-tight">Konfirmasi Ambil Alih Tugas</h3>
                        <p class="text-xs text-amber-800 font-medium">Informasi tugas dan pengembang yang sedang bertugas</p>
                    </div>
                </div>
                <button
                    type="button"
                    id="btn-close-takeover-modal"
                    class="text-slate-400 hover:text-slate-700 p-1.5 rounded-lg hover:bg-slate-200/60 transition cursor-pointer"
                    aria-label="Tutup"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Isi Notifikasi Rincian Tugas, Developer & Proyek --}}
            <div class="p-6 space-y-4">
                <p class="text-sm text-slate-700 leading-relaxed">
                    Anda akan mengambil alih tanggung jawab pengerjaan tugas ini dari developer yang sedang bertugas:
                </p>

                {{-- Kartu Info Notifikasi (Developer Lain & Proyek yang Sedang Dikerjakan) --}}
                <div class="rounded-xl border border-amber-200 bg-amber-50/50 p-4 space-y-3">
                    {{-- Nama Developer Lain --}}
                    <div class="flex items-start gap-3">
                        <div class="relative shrink-0 mt-0.5">
                            <img id="takeover-dev-photo" src="" alt="Foto Developer" class="w-8 h-8 rounded-full object-cover border border-slate-200 shadow-2xs hidden">
                            <div class="w-8 h-8 rounded-full bg-slate-900 text-white flex items-center justify-center text-xs font-bold shadow-2xs" id="takeover-dev-avatar">
                                DV
                            </div>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="block text-[11px] font-bold uppercase tracking-wider text-amber-900/70">Developer Saat Ini</span>
                            <span id="takeover-dev-name" class="font-bold text-slate-900 text-sm block truncate">Nama Developer</span>
                            <span id="takeover-dev-role" class="text-xs text-slate-500">Role / Spesialisasi</span>
                        </div>
                    </div>

                    {{-- Proyek yang Sedang Dikerjakan --}}
                    <div class="pt-2.5 border-t border-amber-200/60 flex items-start gap-2.5">
                        <svg class="w-4 h-4 text-amber-700 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121 12v.75m-8.625-12a1.875 1.875 0 00-1.875 1.875v.75h5.625v-.75a1.875 1.875 0 00-1.875-1.875H12.375zM3.75 12h16.5m-16.5 0a2.25 2.25 0 00-2.25 2.25v5.25A2.25 2.25 0 003.75 21h16.5a2.25 2.25 0 002.25-2.25v-5.25a2.25 2.25 0 00-2.25-2.25H3.75z"/>
                        </svg>
                        <div class="min-w-0 flex-1">
                            <span class="block text-[11px] font-bold uppercase tracking-wider text-amber-900/70">Proyek yang Dikerjakan</span>
                            <span id="takeover-project-name" class="font-semibold text-slate-900 text-xs block">[PRJ] Nama Proyek</span>
                        </div>
                    </div>

                    {{-- Tugas yang Diambil Alih --}}
                    <div class="pt-2.5 border-t border-amber-200/60 flex items-start gap-2.5">
                        <svg class="w-4 h-4 text-amber-700 shrink-0 mt-0.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25z"/>
                        </svg>
                        <div class="min-w-0 flex-1">
                            <span class="block text-[11px] font-bold uppercase tracking-wider text-amber-900/70">Judul Tugas</span>
                            <span id="takeover-task-title" class="font-semibold text-slate-900 text-xs block">Judul Tugas</span>
                        </div>
                    </div>
                </div>

                {{-- Catatan Pengambilalihan (Opsional) --}}
                <div>
                    <label for="takeover-note" class="block text-xs font-semibold text-slate-800 mb-1">
                        Catatan Handover / Alasan Pengambilalihan (Opsional)
                    </label>
                    <input
                        type="text"
                        id="takeover-note"
                        placeholder="Contoh: Mengambil alih pengerjaan tugas sprint"
                        class="block w-full rounded-lg border border-slate-300 px-3.5 py-2 text-xs sm:text-sm text-slate-900 transition focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900"
                    >
                </div>

                {{-- Tombol Aksi Modal --}}
                <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-200">
                    <button
                        type="button"
                        id="btn-cancel-takeover"
                        class="px-4 py-2.5 rounded-lg border border-slate-300 text-xs sm:text-sm font-semibold text-slate-700 hover:bg-slate-100 transition cursor-pointer"
                    >
                        Batal
                    </button>
                    <button
                        type="button"
                        id="btn-confirm-takeover"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-amber-600 hover:bg-amber-700 px-5 py-2.5 text-xs sm:text-sm font-semibold text-white transition cursor-pointer shadow-sm"
                    >
                        <span id="confirm-takeover-text">Ya, Ambil Alih Tugas</span>
                        <svg id="confirm-takeover-spinner" class="hidden w-4 h-4 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL POP-UP ALIKAN TUGAS KE REKAN TIM --}}
    <div
        id="modal-transfer-task"
        class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden"
        aria-labelledby="modal-transfer-title"
        role="dialog"
        aria-modal="true"
    >
        <div class="bg-white border border-slate-200 rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden transition-all transform duration-200">
            {{-- Header Modal --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-indigo-50/70">
                <div class="flex items-center gap-2.5">
                    <div class="w-9 h-9 rounded-xl bg-indigo-100 text-indigo-700 border border-indigo-200/80 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/>
                        </svg>
                    </div>
                    <div>
                        <h3 id="modal-transfer-title" class="text-base font-bold text-slate-900 leading-tight">Alihkan Tugas ke Rekan Tim</h3>
                        <p class="text-xs text-indigo-800 font-medium">Serahkan pengerjaan tugas kepada rekan setim</p>
                    </div>
                </div>
                <button
                    type="button"
                    id="btn-close-transfer-modal"
                    class="text-slate-400 hover:text-slate-700 p-1.5 rounded-lg hover:bg-slate-200/60 transition cursor-pointer"
                    aria-label="Tutup"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Form Isi --}}
            <div class="p-6 space-y-4">
                {{-- Info Tugas --}}
                <div class="rounded-xl border border-indigo-100 bg-indigo-50/40 p-4 space-y-2">
                    <div>
                        <span class="block text-[11px] font-bold uppercase tracking-wider text-indigo-900">Judul Tugas</span>
                        <div id="transfer-task-title" class="text-sm font-semibold text-slate-900 mt-0.5">
                            Judul Tugas
                        </div>
                    </div>
                    <div class="pt-2 border-t border-indigo-100/80 flex items-center justify-between text-xs text-slate-600">
                        <span id="transfer-project-name" class="font-medium text-slate-700">[PRJ] Proyek</span>
                        <span>PIC Saat Ini: <b id="transfer-current-assignee" class="text-slate-900 font-semibold">Anda</b></span>
                    </div>
                </div>

                {{-- Pilih Rekan Tim Penerima --}}
                <div>
                    <label for="transfer-target-developer" class="block text-xs font-semibold text-slate-800 mb-1.5">
                        Pilih Rekan Tim Penerima Tugas <span class="text-rose-500">*</span>
                    </label>
                    <select
                        id="transfer-target-developer"
                        class="w-full text-sm rounded-lg border border-slate-300 px-3.5 py-2.5 text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-600 cursor-pointer"
                    >
                        <option value="">-- Pilih Rekan Tim --</option>
                        @foreach ($developers as $dev)
                            <option value="{{ $dev->id }}">{{ $dev->name }} ({{ $dev->role_title ?? $dev->role ?? 'Developer' }})</option>
                        @endforeach
                    </select>
                </div>

                {{-- Catatan Pengalihan --}}
                <div>
                    <label for="transfer-note" class="block text-xs font-semibold text-slate-800 mb-1.5">
                        Catatan Serah Terima / Alasan Pengalihan (Opsional)
                    </label>
                    <textarea
                        id="transfer-note"
                        rows="2"
                        class="w-full text-xs rounded-lg border border-slate-300 px-3 py-2 text-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-600 placeholder:text-slate-400"
                        placeholder="Contoh: Mengalihkan tugas modul ini kepada rekan karena fokus pada task prioritas..."
                    ></textarea>
                </div>
            </div>

            {{-- Footer Modal --}}
            <div class="flex items-center justify-end gap-3 px-6 py-4 bg-slate-50 border-t border-slate-200">
                <button
                    type="button"
                    id="btn-cancel-transfer"
                    class="px-4 py-2.5 rounded-lg border border-slate-300 text-xs sm:text-sm font-semibold text-slate-700 hover:bg-slate-100 transition cursor-pointer"
                >
                    Batal
                </button>
                <button
                    type="button"
                    id="btn-confirm-transfer"
                    class="px-4 py-2.5 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-xs sm:text-sm font-semibold transition flex items-center gap-2 cursor-pointer shadow-xs"
                >
                    <svg id="confirm-transfer-spinner" class="animate-spin -ml-1 mr-1 h-4 w-4 text-white hidden" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span id="confirm-transfer-text">Konfirmasi Alihkan Tugas</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Script Interaktivitas Papan Kanban, Navigasi Cepat, Edit Modal & Drag-Drop --}}
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

            // Toast Feedback
            const toast = document.getElementById('toast-message');
            const toastText = document.getElementById('toast-text');
            let toastTimer;

            const showToast = (message, isError = false) => {
                if (!toast) return;
                clearTimeout(toastTimer);
                toastText.textContent = message;
                toast.className = isError
                    ? 'mb-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-2.5 text-xs sm:text-sm font-medium text-rose-800 flex items-center justify-between transition-all'
                    : 'mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-xs sm:text-sm font-medium text-emerald-800 flex items-center justify-between transition-all';
                toast.classList.remove('hidden');
                toastTimer = setTimeout(() => {
                    toast.classList.add('hidden');
                }, 3500);
            };

            // TOMBOL NAVIGASI CEPAT (Lompat / Fokus Kolom)
            const quickNavButtons = document.querySelectorAll('.btn-quick-nav');
            const columns = document.querySelectorAll('.kanban-column');

            quickNavButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const target = btn.getAttribute('data-target');

                    // Update styling tombol navigasi cepat
                    quickNavButtons.forEach(b => {
                        b.classList.remove('bg-slate-900', 'text-white', 'shadow-xs');
                        b.classList.add('bg-white', 'text-slate-700', 'border', 'border-slate-200');
                    });
                    btn.classList.add('bg-slate-900', 'text-white', 'shadow-xs');
                    btn.classList.remove('bg-white', 'text-slate-700', 'border-slate-200');

                    if (target === 'all') {
                        // Tampilkan semua kolom
                        columns.forEach(col => {
                            col.classList.remove('hidden', 'ring-4', 'ring-indigo-500/50');
                        });
                        document.getElementById('kanban-board-container')?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                    } else {
                        const targetCol = document.getElementById(`column-${target}`);
                        if (targetCol) {
                            // Scroll halus ke kolom tujuan
                            targetCol.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });

                            // Efek highlight kolom sesaat
                            columns.forEach(col => col.classList.remove('ring-4', 'ring-indigo-500/50'));
                            targetCol.classList.add('ring-4', 'ring-indigo-500/50');
                            setTimeout(() => {
                                targetCol.classList.remove('ring-4', 'ring-indigo-500/50');
                            }, 1800);
                        }
                    }
                });
            });

            // Filter Instan (Project & Developer)
            const filterProject = document.getElementById('filter-project');
            const filterDeveloper = document.getElementById('filter-developer');
            const btnResetFilters = document.getElementById('btn-reset-filters');

            // Refresh Counter Kolom & Navigasi Cepat (Hanya Menghitung Kartu yang Terlihat)
            const refreshColumnCounters = () => {
                let totalVisible = 0;
                document.querySelectorAll('.kanban-dropzone').forEach(dropzone => {
                    const status = dropzone.getAttribute('data-status');
                    const visibleCards = dropzone.querySelectorAll('.kanban-card:not(.hidden)');
                    const cardsCount = visibleCards.length;
                    totalVisible += cardsCount;

                    // Counter di header kolom
                    const columnWrapper = dropzone.closest('[data-column-status]');
                    if (columnWrapper) {
                        const counter = columnWrapper.querySelector('.column-count');
                        if (counter) counter.textContent = cardsCount;

                        // Placeholder kosong
                        let placeholder = dropzone.querySelector('.empty-placeholder');
                        if (cardsCount === 0) {
                            if (!placeholder) {
                                const emptyEl = document.createElement('div');
                                emptyEl.className = 'empty-placeholder flex flex-col items-center justify-center h-48 border-2 border-dashed border-slate-200 rounded-xl p-4 text-center text-slate-400';
                                emptyEl.innerHTML = `
                                    <svg class="w-8 h-8 mb-2 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2.25 2.25 0 01-2.25-2.25V5.25A2.25 2.25 0 017 3h10a2.25 2.25 0 012.25 2.25v13.5A2.25 2.25 0 0119 21z"/>
                                    </svg>
                                    <p class="text-xs font-medium">Tidak ada tugas</p>
                                `;
                                dropzone.appendChild(emptyEl);
                            }
                        } else if (placeholder) {
                            placeholder.remove();
                        }
                    }

                    // Counter di tombol navigasi cepat
                    const quickCounter = document.querySelector(`.quick-count-${status}`);
                    if (quickCounter) quickCounter.textContent = cardsCount;
                });

                // Update total
                const totalEl = document.querySelector('.total-task-count');
                if (totalEl) totalEl.textContent = totalVisible;
            };

            // Terapkan Filter Instan Client-side (< 10ms)
            const applyInstantFilters = () => {
                const selectedProj = filterProject ? filterProject.value : '';
                const selectedDev = filterDeveloper ? filterDeveloper.value : '';

                if (btnResetFilters) {
                    if (selectedProj || selectedDev) {
                        btnResetFilters.classList.remove('hidden');
                    } else {
                        btnResetFilters.classList.add('hidden');
                    }
                }

                document.querySelectorAll('.kanban-card').forEach(card => {
                    const cardProjId = card.getAttribute('data-project-id') || '';
                    const cardDevId = card.getAttribute('data-assigned-to') || '';

                    const matchProj = !selectedProj || cardProjId === selectedProj;
                    const matchDev = !selectedDev || cardDevId === selectedDev;

                    if (matchProj && matchDev) {
                        card.classList.remove('hidden');
                    } else {
                        card.classList.add('hidden');
                    }
                });

                refreshColumnCounters();
            };

            filterProject?.addEventListener('change', applyInstantFilters);
            filterDeveloper?.addEventListener('change', applyInstantFilters);

            btnResetFilters?.addEventListener('click', () => {
                if (filterProject) filterProject.value = '';
                if (filterDeveloper) filterDeveloper.value = '';
                applyInstantFilters();
            });

            // Jalankan filter saat halaman dimuat jika ada nilai awal
            applyInstantFilters();

            // MODAL EDIT TUGAS
            const modalEdit = document.getElementById('modal-edit-task');
            const btnCloseEditModal = document.getElementById('btn-close-edit-modal');
            const btnCancelEditTask = document.getElementById('btn-cancel-edit-task');
            const formEditTask = document.getElementById('form-edit-task');

            const editTaskId = document.getElementById('edit-task-id');
            const editTaskTitle = document.getElementById('edit-task-title');
            const editTaskDesc = document.getElementById('edit-task-desc');
            const editTaskType = document.getElementById('edit-task-type');
            const editTaskStatus = document.getElementById('edit-task-status');
            const editTaskTargetDate = document.getElementById('edit-task-target-date');
            const displayKanbanAssignedName = document.getElementById('display-kanban-assigned-name');
            const editTaskBlocker = document.getElementById('edit-task-blocker');
            const modalTaskProject = document.getElementById('modal-task-project');

            const editTaskError = document.getElementById('edit-task-error');
            const editTaskErrorText = document.getElementById('edit-task-error-text');
            const btnSubmitEditTask = document.getElementById('btn-submit-edit-task');
            const submitBtnText = document.getElementById('submit-btn-text');
            const submitSpinner = document.getElementById('submit-spinner');

            const closeEditModal = () => {
                modalEdit?.classList.add('hidden');
                editTaskError?.classList.add('hidden');
            };

            btnCloseEditModal?.addEventListener('click', closeEditModal);
            btnCancelEditTask?.addEventListener('click', closeEditModal);

            modalEdit?.addEventListener('click', (e) => {
                if (e.target === modalEdit) {
                    closeEditModal();
                }
            });

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    if (modalEdit && !modalEdit.classList.contains('hidden')) {
                        closeEditModal();
                    } else if (sidebar && !sidebar.classList.contains('-translate-x-full')) {
                        closeSidebar();
                    }
                }
            });

            // Klik Tombol Edit pada Kartu
            document.addEventListener('click', (e) => {
                const btnEdit = e.target.closest('.btn-edit-task');
                if (!btnEdit) return;

                const taskId = btnEdit.getAttribute('data-task-id');
                const title = btnEdit.getAttribute('data-title') || '';
                const desc = btnEdit.getAttribute('data-description') || '';
                const taskType = btnEdit.getAttribute('data-task-type') || 'feature';
                const status = btnEdit.getAttribute('data-status') || 'todo';
                const targetDate = btnEdit.getAttribute('data-target-date') || '';
                const blocker = btnEdit.getAttribute('data-blocker-notes') || '';
                const assignedTo = btnEdit.getAttribute('data-assigned-to') || '';
                const projCode = btnEdit.getAttribute('data-project-code') || '';
                const projName = btnEdit.getAttribute('data-project-name') || '';

                const devName = btnEdit.getAttribute('data-developer-name') || 'Belum ditugaskan';

                editTaskId.value = taskId;
                editTaskTitle.value = title;
                editTaskDesc.value = desc;
                editTaskType.value = taskType;
                editTaskStatus.value = status;
                editTaskTargetDate.value = targetDate;
                if (displayKanbanAssignedName) displayKanbanAssignedName.textContent = devName;
                editTaskBlocker.value = blocker;
                modalTaskProject.textContent = projCode ? `[${projCode}] ${projName}` : 'Tugas';

                editTaskError.classList.add('hidden');
                modalEdit.classList.remove('hidden');
                setTimeout(() => editTaskTitle.focus(), 100);
            });

            // Submit Form Edit Tugas via AJAX
            formEditTask?.addEventListener('submit', async (e) => {
                e.preventDefault();

                const taskId = editTaskId.value;
                if (!taskId) return;

                const payload = {
                    title: editTaskTitle.value.trim(),
                    description: editTaskDesc.value.trim() || null,
                    task_type: editTaskType.value,
                    status: editTaskStatus.value,
                    target_date: editTaskTargetDate.value || null,
                    blocker_notes: editTaskBlocker.value.trim() || null,
                };

                if (!payload.title) {
                    editTaskError.classList.remove('hidden');
                    editTaskErrorText.textContent = 'Judul tugas wajib diisi.';
                    editTaskTitle.focus();
                    return;
                }

                btnSubmitEditTask.disabled = true;
                submitBtnText.textContent = 'Menyimpan...';
                submitSpinner.classList.remove('hidden');
                editTaskError.classList.add('hidden');

                try {
                    const response = await fetch(`/kanban/tasks/${taskId}`, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        closeEditModal();
                        showToast(data.message || 'Data tugas berhasil diperbarui.');

                        const card = document.getElementById(`card-task-${taskId}`);
                        if (card) {
                            // Update Judul
                            const titleEl = card.querySelector('.card-title');
                            if (titleEl) titleEl.textContent = payload.title;

                            // Update Deskripsi
                            const descEl = card.querySelector('.card-description');
                            if (descEl) {
                                if (payload.description) {
                                    descEl.textContent = payload.description;
                                    descEl.classList.remove('hidden');
                                } else {
                                    descEl.textContent = '';
                                    descEl.classList.add('hidden');
                                }
                            }

                            // Update Tipe Badge
                            const typeBadge = card.querySelector('.card-type-badge');
                            if (typeBadge) {
                                const typeLabels = { feature: 'Fitur', enhancement: 'Peningkatan', refactor: 'Refaktor', maintenance: 'Maintenance', bug: 'Bug' };
                                const typeBadges = {
                                    feature: 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                    enhancement: 'bg-violet-50 text-violet-700 border-violet-200',
                                    refactor: 'bg-amber-50 text-amber-700 border-amber-200',
                                    maintenance: 'bg-slate-100 text-slate-700 border-slate-200',
                                    bug: 'bg-rose-50 text-rose-700 border-rose-200'
                                };
                                typeBadge.className = `card-type-badge px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider border ${typeBadges[payload.task_type] || 'bg-slate-100 text-slate-700 border-slate-200'}`;
                                typeBadge.textContent = typeLabels[payload.task_type] || payload.task_type;
                            }

                            // Update Blocker Notes
                            const blockerContainer = card.querySelector('.card-blocker-container');
                            const blockerText = card.querySelector('.card-blocker-text');
                            if (blockerContainer && blockerText) {
                                if (payload.blocker_notes) {
                                    blockerText.textContent = payload.blocker_notes;
                                    blockerContainer.classList.remove('hidden');
                                } else {
                                    blockerContainer.classList.add('hidden');
                                }
                            }

                            // Update Target Date & atribut kartu agar sinkron dengan kalkulasi deadline
                            card.setAttribute('data-target-date', payload.target_date || '');
                            const targetDateEl = card.querySelector('.card-target-date');
                            if (targetDateEl) {
                                if (payload.target_date) {
                                    const [y, m, d] = payload.target_date.split('-').map(Number);
                                    const months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                                    targetDateEl.textContent = (y && m && d)
                                        ? `${String(d).padStart(2, '0')} ${months[m - 1] || ''} ${y}`
                                        : payload.target_date;
                                } else {
                                    targetDateEl.textContent = 'Tanpa batas';
                                }
                            }

                            // Update Data Attributes pada tombol edit
                            const btnEdit = card.querySelector('.btn-edit-task');
                            if (btnEdit) {
                                btnEdit.setAttribute('data-title', payload.title);
                                btnEdit.setAttribute('data-description', payload.description || '');
                                btnEdit.setAttribute('data-task-type', payload.task_type);
                                btnEdit.setAttribute('data-status', payload.status);
                                btnEdit.setAttribute('data-target-date', payload.target_date || '');
                                btnEdit.setAttribute('data-blocker-notes', payload.blocker_notes || '');
                            }

                            // Perbarui elemen status & indikator overdue
                            updateCardStatusElements(card, payload.status);

                            // Pindahkan kartu jika status berubah
                            const currentStatus = card.getAttribute('data-current-status');
                            if (payload.status !== currentStatus) {
                                const targetDropzone = document.querySelector(`.kanban-dropzone[data-status="${payload.status}"]`);
                                if (targetDropzone) {
                                    const placeholder = targetDropzone.querySelector('.empty-placeholder');
                                    if (placeholder) placeholder.remove();

                                    targetDropzone.insertBefore(card, targetDropzone.firstChild);
                                }
                            }

                            // Terapkan filter & hitung ulang counter
                            applyInstantFilters();
                        }
                    } else {
                        editTaskError.classList.remove('hidden');
                        editTaskErrorText.textContent = data.message || 'Gagal menyimpan perubahan tugas.';
                    }
                } catch (error) {
                    editTaskError.classList.remove('hidden');
                    editTaskErrorText.textContent = 'Terjadi kesalahan koneksi saat menyimpan tugas.';
                } finally {
                    btnSubmitEditTask.disabled = false;
                    submitBtnText.textContent = 'Simpan Perubahan';
                    submitSpinner.classList.add('hidden');
                }
            });

            // Definisi alur status kolom & garis warna kartu tugas
            const statusFlow = ['todo', 'in_progress', 'review', 'done'];
            const statusBorderClasses = {
                'todo': 'border-l-slate-400',
                'in_progress': 'border-l-sky-500',
                'review': 'border-l-amber-500',
                'done': 'border-l-emerald-500'
            };
            const statusLabels = {
                'todo': 'To Do',
                'in_progress': 'In Progress',
                'review': 'Review',
                'done': 'Done'
            };

            // Sinkronisasi status elemen kartu: Garis warna status, Select, dan Tombol Panah
            const updateCardStatusElements = (cardElement, newStatus) => {
                if (!cardElement || !newStatus) return;

                // 1. Data Attribute
                cardElement.setAttribute('data-current-status', newStatus);

                // 2. Garis warna border sisi kiri sesuai status
                Object.values(statusBorderClasses).forEach(cls => cardElement.classList.remove(cls));
                if (statusBorderClasses[newStatus]) {
                    cardElement.classList.add(statusBorderClasses[newStatus]);
                }

                // Sinkronisasi status Deadline (Terlambat / Deadline Hari Ini / Masih Lama)
                const overdueBadge = cardElement.querySelector('.card-overdue-badge');
                const targetDateEl = cardElement.querySelector('.card-target-date');
                const deadlineIconEl = cardElement.querySelector('.card-deadline-icon');
                const targetDate = cardElement.getAttribute('data-target-date') || '';

                let isOverdue = false;
                let isDueToday = false;
                if (targetDate && newStatus !== 'done') {
                    const parts = targetDate.split('-').map(Number);
                    if (parts.length === 3 && parts[0] && parts[1] && parts[2]) {
                        const today = new Date();
                        today.setHours(0, 0, 0, 0);
                        const deadline = new Date(parts[0], parts[1] - 1, parts[2]);
                        deadline.setHours(0, 0, 0, 0);

                        isOverdue = deadline.getTime() < today.getTime();
                        isDueToday = deadline.getTime() === today.getTime();
                    }
                }

                if (overdueBadge) {
                    overdueBadge.classList.remove(
                        'hidden', 'inline-flex',
                        'bg-rose-50', 'text-rose-700', 'border-rose-200',
                        'bg-amber-50', 'text-amber-700', 'border-amber-200'
                    );
                    cardElement.classList.remove('border-rose-300', 'ring-1', 'ring-rose-200');
                    cardElement.classList.add('border-slate-200');
                    if (targetDateEl) {
                        targetDateEl.classList.remove('text-rose-600', 'font-bold', 'text-amber-700', 'font-semibold', 'text-slate-600');
                    }
                    if (deadlineIconEl) {
                        deadlineIconEl.classList.remove('text-rose-500', 'text-amber-500', 'text-slate-400');
                    }

                    if (isOverdue) {
                        overdueBadge.textContent = 'Terlambat';
                        overdueBadge.classList.add('inline-flex', 'bg-rose-50', 'text-rose-700', 'border-rose-200');
                        cardElement.classList.remove('border-slate-200');
                        cardElement.classList.add('border-rose-300', 'ring-1', 'ring-rose-200');
                        if (targetDateEl) targetDateEl.classList.add('text-rose-600', 'font-bold');
                        if (deadlineIconEl) deadlineIconEl.classList.add('text-rose-500');
                    } else if (isDueToday) {
                        overdueBadge.textContent = 'Deadline Hari Ini';
                        overdueBadge.classList.add('inline-flex', 'bg-amber-50', 'text-amber-700', 'border-amber-200');
                        if (targetDateEl) targetDateEl.classList.add('text-amber-700', 'font-semibold');
                        if (deadlineIconEl) deadlineIconEl.classList.add('text-amber-500');
                    } else {
                        overdueBadge.textContent = '';
                        overdueBadge.classList.add('hidden');
                        if (targetDateEl) targetDateEl.classList.add('text-slate-600');
                        if (deadlineIconEl) deadlineIconEl.classList.add('text-slate-400');
                    }
                }

                // 3. Dropdown pilihan status
                const select = cardElement.querySelector('.task-status-select');
                if (select) select.value = newStatus;

                // 4. Data-status pada tombol edit modal
                const btnEdit = cardElement.querySelector('.btn-edit-task');
                if (btnEdit) btnEdit.setAttribute('data-status', newStatus);

                // 5. Otorisasi memindahkan tugas: hanya orang yang mengerjakan task tersebut
                const canMove = cardElement.getAttribute('data-can-move') !== 'false';
                const btnPrev = cardElement.querySelector('.btn-move-prev');
                const btnNext = cardElement.querySelector('.btn-move-next');

                if (!canMove) {
                    if (btnPrev) {
                        btnPrev.disabled = true;
                        btnPrev.removeAttribute('data-target-status');
                        btnPrev.classList.add('opacity-25', 'cursor-not-allowed', 'bg-slate-100', 'text-slate-300');
                        btnPrev.classList.remove('cursor-pointer', 'hover:bg-slate-100', 'hover:text-slate-900', 'text-slate-600', 'bg-slate-50');
                    }
                    if (btnNext) {
                        btnNext.disabled = true;
                        btnNext.removeAttribute('data-target-status');
                        btnNext.classList.add('opacity-25', 'cursor-not-allowed', 'bg-slate-100', 'text-slate-300');
                        btnNext.classList.remove('cursor-pointer', 'hover:bg-slate-100', 'hover:text-slate-900', 'text-slate-600', 'bg-slate-50');
                    }
                    if (select) {
                        select.disabled = true;
                        select.classList.add('cursor-not-allowed', 'opacity-60', 'text-slate-400', 'bg-slate-100/80');
                        select.classList.remove('cursor-pointer', 'bg-slate-50');
                    }
                    return;
                }

                // Pulihkan kontrol interaktif jika berhak memindahkan
                if (select) {
                    select.disabled = false;
                    select.classList.remove('cursor-not-allowed', 'opacity-60', 'text-slate-400', 'bg-slate-100/80');
                    select.classList.add('cursor-pointer', 'bg-slate-50', 'text-slate-700');
                }

                // 6. Tombol Panah Kiri (Prev) & Kanan (Next) bagi pemilik tugas
                const currentIndex = statusFlow.indexOf(newStatus);
                const prevStatus = currentIndex > 0 ? statusFlow[currentIndex - 1] : null;
                const nextStatus = (currentIndex >= 0 && currentIndex < statusFlow.length - 1) ? statusFlow[currentIndex + 1] : null;

                if (btnPrev) {
                    if (prevStatus) {
                        btnPrev.disabled = false;
                        btnPrev.setAttribute('data-target-status', prevStatus);
                        btnPrev.title = `Pindahkan ke ${statusLabels[prevStatus]}`;
                        btnPrev.setAttribute('aria-label', `Pindahkan ke ${statusLabels[prevStatus]}`);
                        btnPrev.classList.remove('opacity-25', 'cursor-not-allowed', 'bg-slate-100', 'text-slate-300');
                        btnPrev.classList.add('cursor-pointer', 'bg-slate-50', 'hover:bg-slate-100', 'hover:text-slate-900', 'text-slate-600');
                    } else {
                        btnPrev.disabled = true;
                        btnPrev.removeAttribute('data-target-status');
                        btnPrev.title = 'Sudah di kolom pertama (To Do)';
                        btnPrev.setAttribute('aria-label', 'Sudah di kolom pertama');
                        btnPrev.classList.add('opacity-25', 'cursor-not-allowed', 'bg-slate-100', 'text-slate-300');
                        btnPrev.classList.remove('cursor-pointer', 'hover:bg-slate-100', 'hover:text-slate-900', 'text-slate-600');
                    }
                }

                if (btnNext) {
                    if (nextStatus) {
                        btnNext.disabled = false;
                        btnNext.setAttribute('data-target-status', nextStatus);
                        btnNext.title = `Pindahkan ke ${statusLabels[nextStatus]}`;
                        btnNext.setAttribute('aria-label', `Pindahkan ke ${statusLabels[nextStatus]}`);
                        btnNext.classList.remove('opacity-25', 'cursor-not-allowed', 'bg-slate-100', 'text-slate-300');
                        btnNext.classList.add('cursor-pointer', 'bg-slate-50', 'hover:bg-slate-100', 'hover:text-slate-900', 'text-slate-600');
                    } else {
                        btnNext.disabled = true;
                        btnNext.removeAttribute('data-target-status');
                        btnNext.title = 'Sudah di kolom terakhir (Done)';
                        btnNext.setAttribute('aria-label', 'Sudah di kolom terakhir');
                        btnNext.classList.add('opacity-25', 'cursor-not-allowed', 'bg-slate-100', 'text-slate-300');
                        btnNext.classList.remove('cursor-pointer', 'hover:bg-slate-100', 'hover:text-slate-900', 'text-slate-600');
                    }
                }
            };

            // Update status kartu secara instan (< 0.8 detik, optimistik) & persistensi asinkron ke server
            const updateTaskStatus = async (taskId, newStatus, cardElement) => {
                if (!taskId || !newStatus) return;
                if (!cardElement) {
                    cardElement = document.getElementById(`card-task-${taskId}`);
                }
                if (!cardElement) return;

                // Validasi sisi klien: hanya orang yang mengerjakan task yang dapat memindahkan kartu
                if (cardElement.getAttribute('data-can-move') === 'false') {
                    showToast('Hanya orang yang mengerjakan task ini yang dapat memindahkannya.', true);
                    return;
                }

                const oldStatus = cardElement.getAttribute('data-current-status');
                if (oldStatus === newStatus) return;

                const sourceDropzone = cardElement.closest('.kanban-dropzone');
                const targetDropzone = document.querySelector(`.kanban-dropzone[data-status="${newStatus}"]`);
                if (!targetDropzone) return;

                // 1. UPDATE UI SECARA INSTAN (< 10ms - jauh di bawah 0.8 detik)
                const targetPlaceholder = targetDropzone.querySelector('.empty-placeholder');
                if (targetPlaceholder) targetPlaceholder.remove();

                // Pindahkan elemen kartu ke kolom tujuan secara instan
                targetDropzone.insertBefore(cardElement, targetDropzone.firstChild);

                // Perbarui status atribut, garis warna status, select & tombol panah pada kartu
                updateCardStatusElements(cardElement, newStatus);

                // Efek visual halus untuk konfirmasi perpindahan instan
                cardElement.classList.add('ring-2', 'ring-emerald-400', 'bg-emerald-50/20');
                setTimeout(() => {
                    cardElement.classList.remove('ring-2', 'ring-emerald-400', 'bg-emerald-50/20');
                }, 600);

                // Hitung ulang jumlah kartu di setiap kolom secara langsung
                refreshColumnCounters();

                // 2. KIRIM PERUBAHAN KE SERVER DI LATAR BELAKANG (ASINKRON)
                try {
                    const response = await fetch(`/kanban/tasks/${taskId}/status`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ status: newStatus })
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        showToast(data.message || 'Status tugas berhasil diperbarui.');
                    } else {
                        // Rollback jika server mengembalikan error (misal 403 Forbidden)
                        rollbackTaskMove(cardElement, oldStatus, sourceDropzone);
                        showToast(data.message || 'Gagal memperbarui status tugas di server.', true);
                    }
                } catch (error) {
                    // Rollback jika koneksi jaringan terputus
                    rollbackTaskMove(cardElement, oldStatus, sourceDropzone);
                    showToast('Koneksi terputus, pemindahan status dibatalkan.', true);
                }
            };

            // Fungsi Rollback jika AJAX gagal
            const rollbackTaskMove = (cardElement, previousStatus, originDropzone) => {
                if (!cardElement || !originDropzone) return;
                originDropzone.appendChild(cardElement);
                updateCardStatusElements(cardElement, previousStatus);
                refreshColumnCounters();
            };

            // Event listener ganti status via select
            document.addEventListener('change', (e) => {
                if (e.target && e.target.classList.contains('task-status-select')) {
                    const select = e.target;
                    const card = select.closest('.kanban-card');
                    if (card && card.getAttribute('data-can-move') === 'false') {
                        showToast('Hanya orang yang mengerjakan task ini yang dapat memindahkannya.', true);
                        select.value = card.getAttribute('data-current-status');
                        return;
                    }
                    const taskId = select.getAttribute('data-task-id');
                    const newStatus = select.value;
                    if (taskId && newStatus && card) {
                        updateTaskStatus(taskId, newStatus, card);
                    }
                }
            });

            // Event listener ganti status via klik tombol panah (Prev / Next)
            document.addEventListener('click', (e) => {
                const btnMove = e.target.closest('.btn-move-prev, .btn-move-next');
                if (!btnMove) return;

                const card = btnMove.closest('.kanban-card');
                if (card && card.getAttribute('data-can-move') === 'false') {
                    e.preventDefault();
                    e.stopPropagation();
                    showToast('Hanya orang yang mengerjakan task ini yang dapat memindahkannya.', true);
                    return;
                }

                if (btnMove.disabled) return;

                e.preventDefault();
                e.stopPropagation();

                const targetStatus = btnMove.getAttribute('data-target-status');
                if (!card || !targetStatus) return;

                const taskId = card.getAttribute('data-task-id');
                if (taskId) {
                    updateTaskStatus(taskId, targetStatus, card);
                }
            });

            // Cegah mousedown pada kontrol kartu memicu dragstart HTML5 pada parent card
            document.addEventListener('mousedown', (e) => {
                if (e.target.closest('.btn-move-prev, .btn-move-next, .task-status-select, .btn-edit-task, .btn-takeover-task')) {
                    e.stopPropagation();
                }
            });

            // Native HTML5 Drag and Drop
            let draggedCard = null;

            document.querySelectorAll('.kanban-card').forEach(card => {
                card.addEventListener('dragstart', (e) => {
                    if (card.getAttribute('data-can-move') === 'false' || card.getAttribute('draggable') === 'false') {
                        e.preventDefault();
                        showToast('Hanya orang yang mengerjakan task ini yang dapat memindahkannya.', true);
                        return false;
                    }
                    draggedCard = card;
                    card.classList.add('opacity-50', 'ring-2', 'ring-slate-900');
                    e.dataTransfer.effectAllowed = 'move';
                    e.dataTransfer.setData('text/plain', card.getAttribute('data-task-id'));
                });

                card.addEventListener('dragend', () => {
                    card.classList.remove('opacity-50', 'ring-2', 'ring-slate-900');
                    draggedCard = null;
                    document.querySelectorAll('.kanban-dropzone').forEach(dz => {
                        dz.classList.remove('bg-indigo-50/70', 'border-indigo-300');
                    });
                });
            });

            document.querySelectorAll('.kanban-dropzone').forEach(dropzone => {
                dropzone.addEventListener('dragover', (e) => {
                    e.preventDefault();
                    e.dataTransfer.dropEffect = 'move';
                    dropzone.classList.add('bg-indigo-50/70', 'border-indigo-300');
                });

                dropzone.addEventListener('dragleave', (e) => {
                    if (e.relatedTarget && !dropzone.contains(e.relatedTarget)) {
                        dropzone.classList.remove('bg-indigo-50/70', 'border-indigo-300');
                    }
                });

                dropzone.addEventListener('drop', (e) => {
                    e.preventDefault();
                    dropzone.classList.remove('bg-indigo-50/70', 'border-indigo-300');
                    const targetStatus = dropzone.getAttribute('data-status');
                    if (draggedCard) {
                        if (draggedCard.getAttribute('data-can-move') === 'false') {
                            showToast('Hanya orang yang mengerjakan task ini yang dapat memindahkannya.', true);
                            draggedCard = null;
                            return;
                        }
                        const taskId = draggedCard.getAttribute('data-task-id');
                        const currentStatus = draggedCard.getAttribute('data-current-status');
                        if (targetStatus && targetStatus !== currentStatus) {
                            updateTaskStatus(taskId, targetStatus, draggedCard);
                        }
                    }
                });
            });

            // MODAL AMBIL ALIH TUGAS (POP-UP NOTIFIKASI TUGAS DEVELOPER LAIN)
            const modalTakeover = document.getElementById('modal-takeover-task');
            const btnCloseTakeover = document.getElementById('btn-close-takeover-modal');
            const btnCancelTakeover = document.getElementById('btn-cancel-takeover');
            const btnConfirmTakeover = document.getElementById('btn-confirm-takeover');
            const takeoverText = document.getElementById('confirm-takeover-text');
            const takeoverSpinner = document.getElementById('confirm-takeover-spinner');

            const takeoverDevName = document.getElementById('takeover-dev-name');
            const takeoverDevRole = document.getElementById('takeover-dev-role');
            const takeoverDevAvatar = document.getElementById('takeover-dev-avatar');
            const takeoverProjectName = document.getElementById('takeover-project-name');
            const takeoverTaskTitle = document.getElementById('takeover-task-title');
            const takeoverNote = document.getElementById('takeover-note');

            let activeTakeoverTaskId = null;

            const closeTakeoverModal = () => {
                modalTakeover?.classList.add('hidden');
                activeTakeoverTaskId = null;
                if (takeoverNote) takeoverNote.value = '';
            };

            btnCloseTakeover?.addEventListener('click', closeTakeoverModal);
            btnCancelTakeover?.addEventListener('click', closeTakeoverModal);

            modalTakeover?.addEventListener('click', (e) => {
                if (e.target === modalTakeover) closeTakeoverModal();
            });

            // Klik tombol Ambil Alih Tugas pada kartu
            document.addEventListener('click', (e) => {
                const btn = e.target.closest('.btn-takeover-task');
                if (!btn) return;

                activeTakeoverTaskId = btn.getAttribute('data-task-id');
                const title = btn.getAttribute('data-task-title') || '';
                const projName = btn.getAttribute('data-project-name') || '';
                const projCode = btn.getAttribute('data-project-code') || '';
                const devName = btn.getAttribute('data-developer-name') || 'Belum ditugaskan';
                const devRole = btn.getAttribute('data-developer-role') || 'Pengembang';
                const devAvatar = btn.getAttribute('data-developer-avatar') || 'DV';
                const devPhoto = btn.getAttribute('data-developer-photo') || '';
                const devAvatarBg = btn.getAttribute('data-developer-avatar-bg') || '#1E293B';
                const takeoverDevPhoto = document.getElementById('takeover-dev-photo');

                takeoverTaskTitle.textContent = title;
                takeoverProjectName.textContent = projCode ? `[${projCode}] ${projName}` : projName;
                takeoverDevName.textContent = devName;
                takeoverDevRole.textContent = devRole;

                if (devPhoto && takeoverDevPhoto) {
                    takeoverDevPhoto.src = devPhoto;
                    takeoverDevPhoto.classList.remove('hidden');
                    takeoverDevAvatar.classList.add('hidden');
                } else {
                    if (takeoverDevPhoto) {
                        takeoverDevPhoto.src = '';
                        takeoverDevPhoto.classList.add('hidden');
                    }
                    takeoverDevAvatar.classList.remove('hidden');
                    takeoverDevAvatar.textContent = devAvatar;
                    takeoverDevAvatar.style.backgroundColor = devAvatarBg;
                }

                modalTakeover?.classList.remove('hidden');
            });

            // Konfirmasi Ambil Alih Tugas via AJAX
            btnConfirmTakeover?.addEventListener('click', async () => {
                if (!activeTakeoverTaskId) return;

                btnConfirmTakeover.disabled = true;
                takeoverText.textContent = 'Memproses...';
                takeoverSpinner?.classList.remove('hidden');

                try {
                    const response = await fetch(`/tasks/${activeTakeoverTaskId}/takeover`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            note: takeoverNote ? takeoverNote.value.trim() : null
                        })
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        closeTakeoverModal();
                        showToast(data.message || 'Permintaan ambil alih tugas berhasil dikirim.');

                        if (data.pending) {
                            setTimeout(() => window.location.reload(), 600);
                            return;
                        }

                        // Update tampilan kartu di papan Kanban seketika (jika tugas terbuka langsung diambil)
                        const card = document.getElementById(`card-task-${activeTakeoverTaskId}`);
                        if (card) {
                            card.setAttribute('data-assigned-to', data.user.id);

                            const assigneeContainer = card.querySelector('.card-assignee-container');
                            if (assigneeContainer) {
                                assigneeContainer.title = `Ditugaskan kepada: ${data.user.name}`;
                                assigneeContainer.innerHTML = `
                                    <div class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-bold text-white shadow-2xs" style="background-color: ${data.user.avatar_bg || '#1E293B'}">
                                        ${data.user.avatar || 'AK'}
                                    </div>
                                    <span class="card-assignee-name text-[11px] font-semibold text-slate-700 truncate max-w-[80px]">
                                        ${data.user.name}
                                    </span>
                                `;
                            }

                            const takeoverAction = card.querySelector('.card-takeover-action');
                            if (takeoverAction) {
                                takeoverAction.innerHTML = `
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded px-1.5 py-0.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        <span>Tugas Anda</span>
                                    </span>
                                `;
                            }

                            const btnEdit = card.querySelector('.btn-edit-task');
                            if (btnEdit) {
                                btnEdit.setAttribute('data-assigned-to', data.user.id);
                                btnEdit.setAttribute('data-developer-name', data.user.name);
                            }

                            // Aktifkan izin memindahkan kartu tugas untuk pengguna yang baru mengambil alih
                            card.setAttribute('data-can-move', 'true');
                            card.setAttribute('draggable', 'true');
                            card.classList.remove('cursor-default');
                            card.classList.add('cursor-grab', 'active:cursor-grabbing');

                            // Sinkronkan status kontrol tombol panah dan dropdown
                            const currentStatus = card.getAttribute('data-current-status');
                            updateCardStatusElements(card, currentStatus);

                            card.classList.add('ring-2', 'ring-emerald-400');
                            setTimeout(() => card.classList.remove('ring-2', 'ring-emerald-400'), 800);

                            applyInstantFilters();
                        }

                        // Otomatis refresh halaman agar seluruh data langsung berubah tanpa refresh manual
                        setTimeout(() => window.location.reload(), 500);
                    } else {
                        showToast(data.message || 'Gagal mengambil alih tugas.', true);
                    }
                } catch (err) {
                    showToast('Terjadi kesalahan koneksi saat mengambil alih tugas.', true);
                } finally {
                    btnConfirmTakeover.disabled = false;
                    takeoverText.textContent = 'Ya, Ambil Alih Tugas';
                    takeoverSpinner?.classList.add('hidden');
                }
            });

            // MODAL ALIKAN TUGAS KE REKAN TIM
            const modalTransfer = document.getElementById('modal-transfer-task');
            const btnCloseTransfer = document.getElementById('btn-close-transfer-modal');
            const btnCancelTransfer = document.getElementById('btn-cancel-transfer');
            const btnConfirmTransfer = document.getElementById('btn-confirm-transfer');
            const transferText = document.getElementById('confirm-transfer-text');
            const transferSpinner = document.getElementById('confirm-transfer-spinner');

            const transferTaskTitle = document.getElementById('transfer-task-title');
            const transferProjectName = document.getElementById('transfer-project-name');
            const transferCurrentAssignee = document.getElementById('transfer-current-assignee');
            const transferTargetDev = document.getElementById('transfer-target-developer');
            const transferNote = document.getElementById('transfer-note');

            let activeTransferTaskId = null;

            const closeTransferModal = () => {
                modalTransfer?.classList.add('hidden');
                activeTransferTaskId = null;
                if (transferTargetDev) transferTargetDev.value = '';
                if (transferNote) transferNote.value = '';
            };

            btnCloseTransfer?.addEventListener('click', closeTransferModal);
            btnCancelTransfer?.addEventListener('click', closeTransferModal);
            modalTransfer?.addEventListener('click', (e) => {
                if (e.target === modalTransfer) closeTransferModal();
            });

            // Klik tombol Alihkan Tugas pada kartu
            document.addEventListener('click', (e) => {
                const btn = e.target.closest('.btn-transfer-task');
                if (!btn) return;

                activeTransferTaskId = btn.getAttribute('data-task-id');
                const title = btn.getAttribute('data-task-title') || '';
                const projName = btn.getAttribute('data-project-name') || '';
                const projCode = btn.getAttribute('data-project-code') || '';
                const assigneeName = btn.getAttribute('data-current-assignee-name') || 'Anda';
                const assigneeId = btn.getAttribute('data-current-assignee-id');

                if (transferTaskTitle) transferTaskTitle.textContent = title;
                if (transferProjectName) transferProjectName.textContent = projCode ? `[${projCode}] ${projName}` : projName;
                if (transferCurrentAssignee) transferCurrentAssignee.textContent = assigneeName;

                // Disable opsi developer yang sama dengan assignee saat ini
                if (transferTargetDev) {
                    Array.from(transferTargetDev.options).forEach(opt => {
                        if (opt.value && opt.value === String(assigneeId)) {
                            opt.disabled = true;
                            opt.textContent = opt.textContent.replace(' (PIC Saat Ini)', '') + ' (PIC Saat Ini)';
                        } else {
                            opt.disabled = false;
                            opt.textContent = opt.textContent.replace(' (PIC Saat Ini)', '');
                        }
                    });
                    transferTargetDev.value = '';
                }

                modalTransfer?.classList.remove('hidden');
            });

            // Konfirmasi Alihkan Tugas via AJAX
            btnConfirmTransfer?.addEventListener('click', async () => {
                if (!activeTransferTaskId) return;

                const targetDevId = transferTargetDev ? transferTargetDev.value : null;
                if (!targetDevId) {
                    alert('Silakan pilih rekan tim penerima tugas terlebih dahulu.');
                    transferTargetDev?.focus();
                    return;
                }

                btnConfirmTransfer.disabled = true;
                if (transferText) transferText.textContent = 'Memproses...';
                transferSpinner?.classList.remove('hidden');

                try {
                    const response = await fetch(`/tasks/${activeTransferTaskId}/transfer`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            target_developer_id: targetDevId,
                            note: transferNote ? transferNote.value.trim() : null
                        })
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        closeTransferModal();
                        showToast(data.message || 'Tugas berhasil dialihkan.');
                        setTimeout(() => window.location.reload(), 500);
                    } else {
                        showToast(data.message || 'Gagal mengalihkan tugas.', true);
                    }
                } catch (err) {
                    showToast('Terjadi kesalahan koneksi saat mengalihkan tugas.', true);
                } finally {
                    btnConfirmTransfer.disabled = false;
                    if (transferText) transferText.textContent = 'Konfirmasi Alihkan Tugas';
                    transferSpinner?.classList.add('hidden');
                }
            });

            // Escape key handler
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    if (modalTransfer && !modalTransfer.classList.contains('hidden')) {
                        closeTransferModal();
                    }
                }
            });
        });
    </script>

</body>
</html>
