<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Tugas &mdash; {{ config('app.name', 'Aplikasi Bug Tracker') }}</title>
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

                    {{-- Fitur Daftar Tugas (Active State) --}}
                    <a
                        href="{{ route('tasks.index') }}"
                        class="flex items-center gap-3 rounded-lg px-3.5 py-2.5 text-sm font-semibold transition bg-slate-800 text-white border border-slate-700/70 shadow-xs"
                    >
                        <svg class="w-5 h-5 text-indigo-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
                        {{ $user->avatar ?? 'AK' }}
                    </div>
                @endif
            </div>
        </header>

        {{-- Konten Halaman Daftar Tugas --}}
        <main class="flex-1 p-4 sm:p-6 lg:p-8 max-w-7xl w-full mx-auto flex flex-col">

            {{-- Header Halaman & Tombol Aksi Tambah --}}
            <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pb-4 border-b border-slate-200">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">Daftar Tugas</h1>
                    <p class="mt-1 text-sm text-slate-600">Kelola, pantau, dan atur penugasan tugas tim pengembang secara terstruktur.</p>
                </div>

                {{-- Tombol Buka Pop-up Tambah Tugas --}}
                <button
                    type="button"
                    id="btn-open-create-task"
                    class="inline-flex items-center justify-center gap-2 rounded-lg bg-slate-900 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800 focus:ring-2 focus:ring-slate-900 focus:ring-offset-2 shrink-0 cursor-pointer shadow-sm"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    <span>Tambah Tugas</span>
                </button>
            </div>

            {{-- Ringkasan Metrik Cepat (Bersih & Tegas, Ramah Desktop & Tablet) --}}
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3.5 mb-6">
                {{-- Total Tugas --}}
                <div class="bg-white border border-slate-200 rounded-xl p-3.5 shadow-2xs">
                    <span class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Total Tugas</span>
                    <span class="mt-1 block text-2xl font-extrabold text-slate-900">{{ $metrics['total'] }}</span>
                </div>

                {{-- To Do --}}
                <div class="bg-white border border-slate-200 rounded-xl p-3.5 shadow-2xs">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-slate-500"></span>
                        <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">To Do</span>
                    </div>
                    <span class="mt-1 block text-2xl font-extrabold text-slate-700">{{ $metrics['todo'] }}</span>
                </div>

                {{-- In Progress --}}
                <div class="bg-white border border-sky-200/80 rounded-xl p-3.5 shadow-2xs bg-sky-50/20">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-sky-500"></span>
                        <span class="text-xs font-bold text-sky-700 uppercase tracking-wider">In Progress</span>
                    </div>
                    <span class="mt-1 block text-2xl font-extrabold text-sky-800">{{ $metrics['in_progress'] }}</span>
                </div>

                {{-- Review --}}
                <div class="bg-white border border-amber-200/80 rounded-xl p-3.5 shadow-2xs bg-amber-50/20">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                        <span class="text-xs font-bold text-amber-700 uppercase tracking-wider">Review</span>
                    </div>
                    <span class="mt-1 block text-2xl font-extrabold text-amber-800">{{ $metrics['review'] }}</span>
                </div>

                {{-- Done --}}
                <div class="bg-white border border-emerald-200/80 rounded-xl p-3.5 shadow-2xs bg-emerald-50/20 col-span-2 sm:col-span-1">
                    <div class="flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Done</span>
                    </div>
                    <span class="mt-1 block text-2xl font-extrabold text-emerald-800">{{ $metrics['done'] }}</span>
                </div>
            </div>

            {{-- Pesan Sukses / Notifikasi Flash --}}
            @if (session('success_message'))
                <div class="mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-800 flex items-center justify-between">
                    <span>{{ session('success_message') }}</span>
                </div>
            @endif

            <div id="toast-message" class="hidden mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-xs sm:text-sm font-medium text-emerald-800 items-center justify-between transition-all">
                <span id="toast-text">Tugas berhasil diperbarui.</span>
            </div>

            {{-- Filter & Pencarian Sederhana (Tanpa Tab Bersarang) --}}
            <div class="bg-white border border-slate-200 rounded-xl p-4 mb-6 shadow-2xs">
                <form method="GET" action="{{ route('tasks.index') }}" class="flex flex-col md:flex-row items-stretch md:items-center justify-between gap-3 flex-wrap">
                    {{-- Pencarian Kata Kunci --}}
                    <div class="flex-1 min-w-[220px]">
                        <label for="search-task" class="sr-only">Cari Tugas</label>
                        <div class="relative">
                            <input
                                type="text"
                                id="search-task"
                                name="search"
                                value="{{ $selectedSearch }}"
                                placeholder="Cari judul, deskripsi, atau kendala..."
                                class="w-full rounded-lg border border-slate-300 py-2 pl-9 pr-3.5 text-xs sm:text-sm text-slate-800 placeholder-slate-400 focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900"
                            >
                            <svg class="w-4 h-4 text-slate-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
                            </svg>
                        </div>
                    </div>

                    {{-- Dropdown Filter Proyek --}}
                    <div>
                        <label for="filter-task-project" class="sr-only">Filter Proyek</label>
                        <select
                            id="filter-task-project"
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

                    {{-- Dropdown Filter Status --}}
                    <div>
                        <label for="filter-task-status" class="sr-only">Filter Status</label>
                        <select
                            id="filter-task-status"
                            name="status"
                            class="w-full md:w-auto rounded-lg border border-slate-300 bg-white py-2 pl-3 pr-8 text-xs sm:text-sm font-medium text-slate-700 shadow-2xs focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 cursor-pointer"
                        >
                            <option value="">Semua Status</option>
                            <option value="todo" {{ $selectedStatus === 'todo' ? 'selected' : '' }}>To Do</option>
                            <option value="in_progress" {{ $selectedStatus === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="review" {{ $selectedStatus === 'review' ? 'selected' : '' }}>Review</option>
                            <option value="done" {{ $selectedStatus === 'done' ? 'selected' : '' }}>Done</option>
                        </select>
                    </div>

                    {{-- Dropdown Filter Tipe --}}
                    <div>
                        <label for="filter-task-type" class="sr-only">Filter Tipe Tugas</label>
                        <select
                            id="filter-task-type"
                            name="task_type"
                            class="w-full md:w-auto rounded-lg border border-slate-300 bg-white py-2 pl-3 pr-8 text-xs sm:text-sm font-medium text-slate-700 shadow-2xs focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 cursor-pointer"
                        >
                            <option value="">Semua Tipe</option>
                            <option value="feature" {{ $selectedType === 'feature' ? 'selected' : '' }}>Fitur (Feature)</option>
                            <option value="enhancement" {{ $selectedType === 'enhancement' ? 'selected' : '' }}>Peningkatan</option>
                            <option value="refactor" {{ $selectedType === 'refactor' ? 'selected' : '' }}>Refaktor</option>
                            <option value="maintenance" {{ $selectedType === 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                        </select>
                    </div>

                    {{-- Dropdown Filter Pengembang --}}
                    <div>
                        <label for="filter-task-dev" class="sr-only">Filter Pengembang</label>
                        <select
                            id="filter-task-dev"
                            name="assigned_to"
                            class="w-full md:w-auto rounded-lg border border-slate-300 bg-white py-2 pl-3 pr-8 text-xs sm:text-sm font-medium text-slate-700 shadow-2xs focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 cursor-pointer"
                        >
                            <option value="">Semua Pengembang</option>
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
                        @if ($selectedSearch || $selectedProject || $selectedStatus || $selectedType || $selectedDeveloper)
                            <a
                                href="{{ route('tasks.index') }}"
                                class="px-3 py-2 rounded-lg border border-slate-200 bg-white text-slate-600 text-xs sm:text-sm font-semibold hover:bg-slate-100 hover:text-slate-900 transition"
                            >
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Tabel Daftar Tugas (Bersih, Tegas, Ramah Desktop & Tablet) --}}
            <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-2xs">
                <div class="overflow-x-auto min-w-full">
                    <table class="min-w-full divide-y divide-slate-200 text-left text-sm">
                        <thead class="bg-slate-50 text-[11px] font-bold uppercase tracking-wider text-slate-500 border-b border-slate-200 select-none">
                            <tr>
                                <th scope="col" class="py-3.5 pl-5 pr-3">Tugas</th>
                                <th scope="col" class="py-3.5 px-3">Proyek &amp; Modul</th>
                                <th scope="col" class="py-3.5 px-3">Tipe</th>
                                <th scope="col" class="py-3.5 px-3">Status</th>
                                <th scope="col" class="py-3.5 px-3">Target Selesai</th>
                                <th scope="col" class="py-3.5 px-3">Ditugaskan</th>
                                <th scope="col" class="py-3.5 pl-3 pr-5 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 bg-white">
                            @forelse ($tasks as $task)
                                @php
                                    $typeBadges = [
                                        'feature' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                        'enhancement' => 'bg-violet-50 text-violet-700 border-violet-200',
                                        'refactor' => 'bg-amber-50 text-amber-700 border-amber-200',
                                        'maintenance' => 'bg-slate-100 text-slate-700 border-slate-200',
                                    ];
                                    $typeLabels = [
                                        'feature' => 'Fitur',
                                        'enhancement' => 'Peningkatan',
                                        'refactor' => 'Refaktor',
                                        'maintenance' => 'Maintenance',
                                    ];
                                    $statusBadges = [
                                        'todo' => 'bg-slate-100 text-slate-700 border-slate-200 dot-slate-500',
                                        'in_progress' => 'bg-sky-50 text-sky-700 border-sky-200 dot-sky-500',
                                        'review' => 'bg-amber-50 text-amber-700 border-amber-200 dot-amber-500',
                                        'done' => 'bg-emerald-50 text-emerald-700 border-emerald-200 dot-emerald-500',
                                    ];
                                    $statusDots = [
                                        'todo' => 'bg-slate-400',
                                        'in_progress' => 'bg-sky-500',
                                        'review' => 'bg-amber-500',
                                        'done' => 'bg-emerald-500',
                                    ];
                                    $statusLabels = [
                                        'todo' => 'To Do',
                                        'in_progress' => 'In Progress',
                                        'review' => 'Review',
                                        'done' => 'Done',
                                    ];
                                @endphp
                                <tr class="hover:bg-slate-50/70 transition-colors" id="row-task-{{ $task->id }}">
                                    {{-- 1. Judul & Deskripsi Tugas (Satu atau Dua Kalimat Inti) --}}
                                    <td class="py-4 pl-5 pr-3 max-w-xs sm:max-w-sm">
                                        <div class="font-bold text-slate-900 leading-snug">{{ $task->title }}</div>
                                        @if ($task->description)
                                            <p class="text-xs text-slate-500 mt-1 line-clamp-2 leading-relaxed" title="{{ $task->description }}">
                                                {{ \Illuminate\Support\Str::limit($task->description, 130) }}
                                            </p>
                                        @else
                                            <p class="text-xs text-slate-400 italic mt-0.5">Tanpa deskripsi</p>
                                        @endif
                                        @if ($task->blocker_notes)
                                            <div class="mt-1.5 inline-flex items-center gap-1 px-2 py-0.5 rounded text-[11px] font-semibold bg-rose-50 text-rose-700 border border-rose-200">
                                                <span>Kendala:</span>
                                                <span class="truncate max-w-[200px]">{{ $task->blocker_notes }}</span>
                                            </div>
                                        @endif
                                    </td>

                                    {{-- 2. Proyek & Modul (Disatukan) --}}
                                    <td class="py-4 px-3 whitespace-nowrap">
                                        <div class="flex flex-col items-start gap-1">
                                            @if ($task->project)
                                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-md text-xs font-semibold bg-slate-100 text-slate-800 border border-slate-200">
                                                    <span class="w-2 h-2 rounded-full shrink-0" style="background-color: {{ $task->project->color ?? '#4F46E5' }}"></span>
                                                    <span>{{ $task->project->code }}</span>
                                                </span>
                                            @endif
                                            @if ($task->module)
                                                <span class="text-xs text-slate-600 font-medium truncate max-w-[150px]" title="{{ $task->module->name }}">
                                                    {{ $task->module->name }}
                                                </span>
                                            @elseif (!$task->project)
                                                <span class="text-xs text-slate-400 italic">-</span>
                                            @endif
                                        </div>
                                    </td>

                                    {{-- 3. Tipe Tugas --}}
                                    <td class="py-4 px-3 whitespace-nowrap">
                                        <span class="px-2.5 py-1 rounded text-[11px] font-bold uppercase tracking-wider border {{ $typeBadges[$task->task_type] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}">
                                            {{ $typeLabels[$task->task_type] ?? $task->task_type }}
                                        </span>
                                    </td>

                                    {{-- 4. Status Tugas --}}
                                    <td class="py-4 px-3 whitespace-nowrap">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-md text-xs font-semibold border {{ $statusBadges[$task->status] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $statusDots[$task->status] ?? 'bg-slate-400' }}"></span>
                                            <span>{{ $statusLabels[$task->status] ?? $task->status }}</span>
                                        </span>
                                    </td>

                                    {{-- 5. Target Selesai (Deadline) --}}
                                    <td class="py-4 px-3 whitespace-nowrap text-xs text-slate-600 font-medium">
                                        @if ($task->target_date)
                                            <div class="flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5"/>
                                                </svg>
                                                <span>{{ \Carbon\Carbon::parse($task->target_date)->translatedFormat('d M Y') }}</span>
                                            </div>
                                        @else
                                            <span class="text-slate-400 italic">Tanpa batas</span>
                                        @endif
                                    </td>

                                    {{-- 6. Ditugaskan Kepada --}}
                                    <td class="cell-assignee py-4 px-3 whitespace-nowrap">
                                        @if ($task->assignee)
                                            <div class="flex items-center gap-2">
                                                @if ($task->assignee->foto_profil_url)
                                                    <img src="{{ $task->assignee->foto_profil_url }}" alt="{{ $task->assignee->name }}" class="w-6 h-6 rounded-full object-cover border border-slate-200">
                                                @else
                                                    <div class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-bold text-white shadow-2xs" style="background-color: {{ $task->assignee->avatar_bg ?? '#1E293B' }}">
                                                        {{ $task->assignee->avatar ?? 'DV' }}
                                                    </div>
                                                @endif
                                                <span class="text-xs font-semibold text-slate-800">{{ $task->assignee->name }}</span>
                                            </div>
                                        @else
                                            <span class="text-xs text-slate-400 italic">Belum ditugaskan</span>
                                        @endif
                                    </td>

                                    {{-- 7. Tombol Aksi --}}
                                    <td class="py-4 pl-3 pr-5 whitespace-nowrap text-right">
                                        <div class="inline-flex items-center gap-1.5 justify-end">
                                            {{-- Fitur Ambil Alih Tugas --}}
                                            <div class="table-takeover-action inline-flex items-center">
                                                @if ($task->assigned_to && (int)$task->assigned_to !== (int)$user->id)
                                                    <button
                                                        type="button"
                                                        class="btn-takeover-task px-2.5 py-1 text-xs font-semibold text-amber-700 bg-amber-50 hover:bg-amber-100 border border-amber-200/80 rounded-md transition flex items-center gap-1 cursor-pointer"
                                                        title="Ambil Alih Tugas dari {{ $task->assignee?->name ?? 'Developer lain' }}"
                                                        aria-label="Ambil Alih Tugas {{ $task->title }}"
                                                        data-task-id="{{ $task->id }}"
                                                        data-task-title="{{ $task->title }}"
                                                        data-project-name="{{ $task->project?->name }}"
                                                        data-project-code="{{ $task->project?->code }}"
                                                        data-developer-id="{{ $task->assigned_to }}"
                                                        data-developer-name="{{ $task->assignee?->name ?? 'Belum Ditugaskan' }}"
                                                        data-developer-role="{{ $task->assignee?->role ?? 'Developer' }}"
                                                        data-developer-avatar="{{ $task->assignee?->avatar ?? 'DV' }}"
                                                    >
                                                        <svg class="w-3.5 h-3.5 text-amber-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/>
                                                        </svg>
                                                        <span>Ambil Alih</span>
                                                    </button>
                                                @elseif ($task->assigned_to && (int)$task->assigned_to === (int)$user->id)
                                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded px-2 py-0.5">
                                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                        <span>Tugas Anda</span>
                                                    </span>
                                                @else
                                                    <button
                                                        type="button"
                                                        class="btn-takeover-task px-2.5 py-1 text-xs font-semibold text-slate-700 bg-slate-100 hover:bg-slate-200 border border-slate-200 rounded-md transition flex items-center gap-1 cursor-pointer"
                                                        title="Ambil Tugas Ini"
                                                        aria-label="Ambil Tugas Ini"
                                                        data-task-id="{{ $task->id }}"
                                                        data-task-title="{{ $task->title }}"
                                                        data-project-name="{{ $task->project?->name }}"
                                                        data-project-code="{{ $task->project?->code }}"
                                                        data-developer-id=""
                                                        data-developer-name="Belum Ditugaskan"
                                                        data-developer-role="Tugas Terbuka"
                                                        data-developer-avatar="--"
                                                    >
                                                        <svg class="w-3.5 h-3.5 text-slate-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                                                        </svg>
                                                        <span>Ambil Tugas</span>
                                                    </button>
                                                @endif
                                            </div>

                                            {{-- Tombol Edit --}}
                                            <button
                                                type="button"
                                                class="btn-edit-task p-1.5 rounded-lg text-slate-500 hover:text-slate-900 hover:bg-slate-100 transition cursor-pointer"
                                                title="Edit Tugas: {{ $task->title }}"
                                                aria-label="Edit Tugas {{ $task->title }}"
                                                data-task-id="{{ $task->id }}"
                                                data-project-id="{{ $task->project_id }}"
                                                data-module-id="{{ $task->module_id }}"
                                                data-title="{{ $task->title }}"
                                                data-description="{{ $task->description }}"
                                                data-task-type="{{ $task->task_type }}"
                                                data-status="{{ $task->status }}"
                                                data-start-date="{{ $task->start_date ? \Carbon\Carbon::parse($task->start_date)->format('Y-m-d') : '' }}"
                                                data-target-date="{{ $task->target_date ? \Carbon\Carbon::parse($task->target_date)->format('Y-m-d') : '' }}"
                                                data-blocker-notes="{{ $task->blocker_notes }}"
                                                data-assigned-to="{{ $task->assigned_to }}"
                                            >
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                                </svg>
                                            </button>

                                            {{-- Tombol Hapus --}}
                                            <button
                                                type="button"
                                                class="btn-delete-task p-1.5 rounded-lg text-rose-500 hover:text-rose-700 hover:bg-rose-50 transition cursor-pointer"
                                                title="Hapus Tugas: {{ $task->title }}"
                                                aria-label="Hapus Tugas {{ $task->title }}"
                                                data-task-id="{{ $task->id }}"
                                                data-title="{{ $task->title }}"
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
                                    <td colspan="7" class="py-12 px-4 text-center">
                                        <div class="flex flex-col items-center justify-center text-slate-400">
                                            <svg class="w-10 h-10 mb-2.5 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2.25 2.25 0 01-2.25-2.25V5.25A2.25 2.25 0 017 3h10a2.25 2.25 0 012.25 2.25v13.5A2.25 2.25 0 0119 21z"/>
                                            </svg>
                                            <p class="text-sm font-semibold text-slate-600">Belum ada tugas ditemukan</p>
                                            <p class="text-xs text-slate-400 mt-0.5">Coba sesuaikan filter atau tambahkan tugas baru melalui tombol di atas.</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Paginasi Bersih --}}
                @if ($tasks->hasPages())
                    <div class="px-5 py-3.5 bg-slate-50 border-t border-slate-200">
                        {{ $tasks->links() }}
                    </div>
                @endif
            </div>

        </main>
    </div>

    {{-- MODAL POP-UP TAMBAH / EDIT TUGAS (Tanpa Pindah Halaman) --}}
    <div
        id="modal-task-form"
        class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden"
        aria-labelledby="modal-task-form-title"
        role="dialog"
        aria-modal="true"
    >
        <div class="bg-white border border-slate-200 rounded-2xl shadow-2xl max-w-xl w-full overflow-hidden transition-all transform duration-200">
            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-slate-50">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-lg bg-slate-900 text-white flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                        </svg>
                    </div>
                    <div>
                        <h3 id="modal-task-form-title" class="text-base font-bold text-slate-900 leading-tight">Tambah Tugas Baru</h3>
                        <p class="text-xs text-slate-500 font-medium">Lengkapi rincian tugas untuk pengembang.</p>
                    </div>
                </div>
                <button
                    type="button"
                    id="btn-close-task-modal"
                    class="text-slate-400 hover:text-slate-700 p-1.5 rounded-lg hover:bg-slate-200/60 transition cursor-pointer"
                    aria-label="Tutup"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Form Tambah/Edit Tugas --}}
            <form id="form-task-action" class="p-6 space-y-4 max-h-[80vh] overflow-y-auto">
                <input type="hidden" id="task-id" value="">

                {{-- Alert Error Validasi --}}
                <div id="task-form-error" class="hidden rounded-lg border border-rose-200 bg-rose-50 px-3.5 py-2.5 text-xs text-rose-800">
                    <span id="task-form-error-text">Terjadi kesalahan validasi.</span>
                </div>

                {{-- 1. Judul Tugas --}}
                <div>
                    <label for="input-task-title" class="block text-xs font-semibold text-slate-800 mb-1">
                        Judul Tugas <span class="text-rose-500">*</span>
                    </label>
                    <input
                        id="input-task-title"
                        type="text"
                        required
                        class="block w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-900 transition focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900"
                        placeholder="Contoh: Implementasi integrasi payment gateway"
                    >
                </div>

                {{-- 2. Proyek & Modul --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="input-task-project" class="block text-xs font-semibold text-slate-800 mb-1">
                            Proyek <span class="text-rose-500">*</span>
                        </label>
                        <select
                            id="input-task-project"
                            required
                            class="block w-full rounded-lg border border-slate-300 py-2 pl-3 pr-8 text-sm text-slate-900 transition focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 cursor-pointer"
                        >
                            <option value="">Pilih Proyek</option>
                            @foreach ($projects as $proj)
                                <option value="{{ $proj->id }}">[{{ $proj->code }}] {{ $proj->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label for="input-task-module" class="block text-xs font-semibold text-slate-800 mb-1">
                            Modul (Opsional)
                        </label>
                        <select
                            id="input-task-module"
                            class="block w-full rounded-lg border border-slate-300 py-2 pl-3 pr-8 text-sm text-slate-900 transition focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 cursor-pointer"
                        >
                            <option value="">Tanpa Modul Khusus</option>
                            @foreach ($modules as $mod)
                                <option value="{{ $mod->id }}" data-project-id="{{ $mod->project_id }}">
                                    {{ $mod->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- 3. Tipe Tugas & Status --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="input-task-type" class="block text-xs font-semibold text-slate-800 mb-1">
                            Tipe Tugas <span class="text-rose-500">*</span>
                        </label>
                        <select
                            id="input-task-type"
                            required
                            class="block w-full rounded-lg border border-slate-300 py-2 pl-3 pr-8 text-sm text-slate-900 transition focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 cursor-pointer"
                        >
                            <option value="feature">Fitur (Feature)</option>
                            <option value="enhancement">Peningkatan (Enhancement)</option>
                            <option value="refactor">Refaktor (Refactor)</option>
                            <option value="maintenance">Maintenance</option>
                        </select>
                    </div>

                    <div>
                        <label for="input-task-status" class="block text-xs font-semibold text-slate-800 mb-1">
                            Status <span class="text-rose-500">*</span>
                        </label>
                        <select
                            id="input-task-status"
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

                {{-- 4. Target Deadline & Ditugaskan Kepada --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label for="input-task-target-date" class="block text-xs font-semibold text-slate-800 mb-1">
                            Target Selesai (Deadline)
                        </label>
                        <input
                            id="input-task-target-date"
                            type="date"
                            class="block w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-900 transition focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 cursor-pointer"
                        >
                    </div>

                    <div>
                        <label for="input-task-assigned-to" class="block text-xs font-semibold text-slate-800 mb-1">
                            Ditugaskan Kepada
                        </label>
                        <select
                            id="input-task-assigned-to"
                            class="block w-full rounded-lg border border-slate-300 py-2 pl-3 pr-8 text-sm text-slate-900 transition focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 cursor-pointer"
                        >
                            <option value="">Belum Ditugaskan</option>
                            @foreach ($developers as $dev)
                                <option value="{{ $dev->id }}">{{ $dev->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- 5. Catatan Kendala / Blocker --}}
                <div>
                    <label for="input-task-blocker" class="block text-xs font-semibold text-slate-800 mb-1">
                        Catatan Kendala (Blocker Notes)
                    </label>
                    <input
                        id="input-task-blocker"
                        type="text"
                        placeholder="Contoh: Menunggu review spesifikasi teknis dari klien"
                        class="block w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-900 transition focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900"
                    >
                </div>

                {{-- 6. Deskripsi Tugas --}}
                <div>
                    <label for="input-task-description" class="block text-xs font-semibold text-slate-800 mb-1">
                        Deskripsi Lengkap
                    </label>
                    <textarea
                        id="input-task-description"
                        rows="3"
                        placeholder="Uraikan instruksi atau kriteria tugas secara ringkas..."
                        class="block w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm text-slate-900 transition focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 resize-none"
                    ></textarea>
                </div>

                {{-- Tombol Aksi Modal Form --}}
                <div class="flex items-center justify-end gap-2.5 pt-4 border-t border-slate-200">
                    <button
                        type="button"
                        id="btn-cancel-task-form"
                        class="px-4 py-2.5 rounded-lg border border-slate-300 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition cursor-pointer"
                    >
                        Batal
                    </button>
                    <button
                        type="submit"
                        id="btn-submit-task-form"
                        class="inline-flex items-center justify-center gap-2 rounded-lg bg-slate-900 px-5 py-2.5 text-sm font-semibold text-white hover:bg-slate-800 transition cursor-pointer shadow-sm"
                    >
                        <span id="submit-task-text">Simpan Tugas</span>
                        <svg id="submit-task-spinner" class="hidden w-4 h-4 animate-spin text-white" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- MODAL KONFIRMASI HAPUS TUGAS --}}
    <div
        id="modal-delete-task"
        class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden"
        aria-labelledby="modal-delete-title"
        role="dialog"
        aria-modal="true"
    >
        <div class="bg-white border border-slate-200 rounded-2xl shadow-2xl max-w-md w-full overflow-hidden transition-all transform duration-200 p-6 text-center">
            <div class="w-12 h-12 rounded-full bg-rose-100 text-rose-600 flex items-center justify-center mx-auto mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0"/>
                </svg>
            </div>
            <h3 id="modal-delete-title" class="text-lg font-bold text-slate-900">Hapus Tugas Ini?</h3>
            <p class="text-sm text-slate-600 mt-1.5">Tugas <strong id="delete-task-title" class="text-slate-900"></strong> akan dihapus permanen dari sistem.</p>

            <div class="flex items-center justify-center gap-3 mt-6">
                <button
                    type="button"
                    id="btn-cancel-delete"
                    class="px-4 py-2.5 rounded-lg border border-slate-300 text-sm font-semibold text-slate-700 hover:bg-slate-100 transition cursor-pointer"
                >
                    Batal
                </button>
                <button
                    type="button"
                    id="btn-confirm-delete"
                    class="px-4 py-2.5 rounded-lg bg-rose-600 text-sm font-semibold text-white hover:bg-rose-700 transition cursor-pointer shadow-xs"
                >
                    Hapus Tugas
                </button>
            </div>
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
                        <div
                            id="takeover-dev-avatar"
                            class="w-10 h-10 rounded-full bg-slate-900 text-white flex items-center justify-center text-xs font-bold shrink-0 shadow-xs"
                        >
                            DV
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="block text-[11px] font-bold uppercase tracking-wider text-amber-900">Developer Saat Ini</span>
                            <div id="takeover-dev-name" class="text-base font-bold text-slate-900 leading-snug">
                                Nama Developer
                            </div>
                            <div id="takeover-dev-role" class="text-xs text-slate-600 mt-0.5">
                                Peran / Spesialisasi
                            </div>
                        </div>
                    </div>

                    {{-- Proyek yang Sedang Dikerjakan --}}
                    <div class="pt-3 border-t border-amber-200/70 flex items-start gap-2.5">
                        <div class="w-5 h-5 rounded-md bg-amber-100 text-amber-800 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 014.5 9.75h15A2.25 2.25 0 0121.75 12v.75m-8.625-4.5H12V3.75A2.25 2.25 0 009.75 1.5h-4.5A2.25 2.25 0 003 3.75v1.5h-.75A2.25 2.25 0 000 7.5v9A2.25 2.25 0 002.25 18.75h19.5A2.25 2.25 0 0024 16.5v-9a2.25 2.25 0 00-2.25-2.25H21"/>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="block text-[11px] font-bold uppercase tracking-wider text-amber-900">Project yang Sedang Dikerjakan</span>
                            <div id="takeover-project-name" class="text-sm font-semibold text-slate-900 mt-0.5">
                                [PRJ-01] Nama Proyek
                            </div>
                        </div>
                    </div>

                    {{-- Judul Tugas --}}
                    <div class="pt-3 border-t border-amber-200/70 flex items-start gap-2.5">
                        <div class="w-5 h-5 rounded-md bg-amber-100 text-amber-800 flex items-center justify-center shrink-0 mt-0.5">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 002.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 00-1.123-.08m-5.801 0c-.065.21-.1.433-.1.664 0 .414.336.75.75.75h4.5a.75.75 0 00.75-.75 2.25 2.25 0 00-.1-.664m-5.8 0A2.251 2.251 0 0113.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25m0 0H4.875c-.621 0-1.125.504-1.125 1.125v11.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V9.375c0-.621-.504-1.125-1.125-1.125H8.25zM6.75 12h.008v.008H6.75V12zm0 3h.008v.008H6.75V15zm0 3h.008v.008H6.75V18z"/>
                            </svg>
                        </div>
                        <div class="min-w-0 flex-1">
                            <span class="block text-[11px] font-bold uppercase tracking-wider text-amber-900">Judul Tugas</span>
                            <div id="takeover-task-title" class="text-sm font-semibold text-slate-900 mt-0.5">
                                Judul Tugas
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Catatan Alih Tugas (Opsional) --}}
                <div>
                    <label for="takeover-note" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Catatan Pengalihan Tugas (Opsional):
                    </label>
                    <textarea
                        id="takeover-note"
                        rows="2"
                        class="w-full text-xs rounded-lg border border-slate-300 px-3 py-2 text-slate-900 focus:outline-none focus:ring-2 focus:ring-amber-500 placeholder:text-slate-400"
                        placeholder="Contoh: Diambil alih untuk percepatan release sprint..."
                    ></textarea>
                </div>
            </div>

            {{-- Footer Modal --}}
            <div class="flex items-center justify-end gap-3 px-6 py-4 bg-slate-50 border-t border-slate-200">
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
                    class="inline-flex items-center gap-2 px-5 py-2.5 rounded-lg bg-amber-600 hover:bg-amber-700 text-xs sm:text-sm font-bold text-white transition cursor-pointer shadow-xs"
                >
                    <svg id="confirm-takeover-spinner" class="animate-spin -ml-1 mr-1 h-4 w-4 text-white hidden" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span id="confirm-takeover-text">Ya, Ambil Alih Tugas</span>
                </button>
            </div>
        </div>
    </div>

    {{-- Script Interaktivitas UI Task --}}
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

            // Modal Tambah / Edit Tugas
            const modalForm = document.getElementById('modal-task-form');
            const modalTitle = document.getElementById('modal-task-form-title');
            const btnOpenCreate = document.getElementById('btn-open-create-task');
            const btnCloseModal = document.getElementById('btn-close-task-modal');
            const btnCancelModal = document.getElementById('btn-cancel-task-form');
            const formAction = document.getElementById('form-task-action');

            const taskIdInput = document.getElementById('task-id');
            const titleInput = document.getElementById('input-task-title');
            const projectInput = document.getElementById('input-task-project');
            const moduleInput = document.getElementById('input-task-module');
            const typeInput = document.getElementById('input-task-type');
            const statusInput = document.getElementById('input-task-status');
            const targetDateInput = document.getElementById('input-task-target-date');
            const assignedToInput = document.getElementById('input-task-assigned-to');
            const blockerInput = document.getElementById('input-task-blocker');
            const descriptionInput = document.getElementById('input-task-description');

            const formError = document.getElementById('task-form-error');
            const formErrorText = document.getElementById('task-form-error-text');
            const btnSubmit = document.getElementById('btn-submit-task-form');
            const submitText = document.getElementById('submit-task-text');
            const submitSpinner = document.getElementById('submit-task-spinner');

            const closeModalForm = () => {
                modalForm?.classList.add('hidden');
                formError?.classList.add('hidden');
                formAction?.reset();
                taskIdInput.value = '';
            };

            btnOpenCreate?.addEventListener('click', () => {
                modalTitle.textContent = 'Tambah Tugas Baru';
                submitText.textContent = 'Tambah Tugas';
                formAction.reset();
                taskIdInput.value = '';
                formError.classList.add('hidden');
                modalForm.classList.remove('hidden');
                setTimeout(() => titleInput.focus(), 100);
            });

            btnCloseModal?.addEventListener('click', closeModalForm);
            btnCancelModal?.addEventListener('click', closeModalForm);

            // Buka Modal Edit via Tombol Edit di Tabel
            document.addEventListener('click', (e) => {
                const btnEdit = e.target.closest('.btn-edit-task');
                if (!btnEdit) return;

                const taskId = btnEdit.getAttribute('data-task-id');
                const projId = btnEdit.getAttribute('data-project-id') || '';
                const modId = btnEdit.getAttribute('data-module-id') || '';
                const title = btnEdit.getAttribute('data-title') || '';
                const desc = btnEdit.getAttribute('data-description') || '';
                const type = btnEdit.getAttribute('data-task-type') || 'feature';
                const status = btnEdit.getAttribute('data-status') || 'todo';
                const targetDate = btnEdit.getAttribute('data-target-date') || '';
                const blocker = btnEdit.getAttribute('data-blocker-notes') || '';
                const assignedTo = btnEdit.getAttribute('data-assigned-to') || '';

                taskIdInput.value = taskId;
                titleInput.value = title;
                projectInput.value = projId;
                moduleInput.value = modId;
                typeInput.value = type;
                statusInput.value = status;
                targetDateInput.value = targetDate;
                assignedToInput.value = assignedTo;
                blockerInput.value = blocker;
                descriptionInput.value = desc;

                modalTitle.textContent = 'Edit Data Tugas';
                submitText.textContent = 'Simpan Perubahan';
                formError.classList.add('hidden');
                modalForm.classList.remove('hidden');
                setTimeout(() => titleInput.focus(), 100);
            });

            // Submit Form Tambah / Edit Tugas
            formAction?.addEventListener('submit', async (e) => {
                e.preventDefault();

                const taskId = taskIdInput.value;
                const isEditing = Boolean(taskId);
                const url = isEditing ? `/tasks/${taskId}` : '/tasks';
                const method = isEditing ? 'PUT' : 'POST';

                const payload = {
                    title: titleInput.value.trim(),
                    project_id: projectInput.value,
                    module_id: moduleInput.value || null,
                    task_type: typeInput.value,
                    status: statusInput.value,
                    target_date: targetDateInput.value || null,
                    assigned_to: assignedToInput.value || null,
                    blocker_notes: blockerInput.value.trim() || null,
                    description: descriptionInput.value.trim() || null,
                };

                btnSubmit.disabled = true;
                submitText.textContent = isEditing ? 'Menyimpan...' : 'Menambahkan...';
                submitSpinner.classList.remove('hidden');
                formError.classList.add('hidden');

                try {
                    const response = await fetch(url, {
                        method: method,
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        closeModalForm();
                        showToast(data.message);
                        setTimeout(() => window.location.reload(), 400);
                    } else {
                        formError.classList.remove('hidden');
                        formErrorText.textContent = data.message || 'Gagal menyimpan tugas.';
                    }
                } catch (err) {
                    formError.classList.remove('hidden');
                    formErrorText.textContent = 'Terjadi kesalahan koneksi jaringan.';
                } finally {
                    btnSubmit.disabled = false;
                    submitText.textContent = isEditing ? 'Simpan Perubahan' : 'Tambah Tugas';
                    submitSpinner.classList.add('hidden');
                }
            });

            // Modal Konfirmasi Hapus Tugas
            const modalDelete = document.getElementById('modal-delete-task');
            const deleteTitle = document.getElementById('delete-task-title');
            const btnCancelDelete = document.getElementById('btn-cancel-delete');
            const btnConfirmDelete = document.getElementById('btn-confirm-delete');
            let taskToDeleteId = null;

            const closeDeleteModal = () => {
                modalDelete?.classList.add('hidden');
                taskToDeleteId = null;
            };

            btnCancelDelete?.addEventListener('click', closeDeleteModal);

            document.addEventListener('click', (e) => {
                const btnDelete = e.target.closest('.btn-delete-task');
                if (!btnDelete) return;

                taskToDeleteId = btnDelete.getAttribute('data-task-id');
                const title = btnDelete.getAttribute('data-title');
                deleteTitle.textContent = `"${title}"`;
                modalDelete.classList.remove('hidden');
            });

            btnConfirmDelete?.addEventListener('click', async () => {
                if (!taskToDeleteId) return;

                btnConfirmDelete.disabled = true;
                btnConfirmDelete.textContent = 'Menghapus...';

                try {
                    const response = await fetch(`/tasks/${taskToDeleteId}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Accept': 'application/json'
                        }
                    });

                    const data = await response.json();

                    if (response.ok && data.success) {
                        closeDeleteModal();
                        showToast(data.message);
                        const row = document.getElementById(`row-task-${taskToDeleteId}`);
                        if (row) row.remove();
                        setTimeout(() => window.location.reload(), 400);
                    } else {
                        showToast(data.message || 'Gagal menghapus tugas.', true);
                    }
                } catch (err) {
                    showToast('Terjadi kesalahan koneksi saat menghapus.', true);
                } finally {
                    btnConfirmDelete.disabled = false;
                    btnConfirmDelete.textContent = 'Hapus Tugas';
                }
            });

            // Modal Konfirmasi Ambil Alih Tugas
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

            // Klik tombol Ambil Alih Tugas pada tabel
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

                takeoverTaskTitle.textContent = title;
                takeoverProjectName.textContent = projCode ? `[${projCode}] ${projName}` : projName;
                takeoverDevName.textContent = devName;
                takeoverDevRole.textContent = devRole;
                takeoverDevAvatar.textContent = devAvatar;

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
                        showToast(data.message || 'Tugas berhasil diambil alih.');

                        // Update tampilan baris tabel secara instan
                        const row = document.getElementById(`row-task-${activeTakeoverTaskId}`);
                        if (row) {
                            const assigneeCell = row.querySelector('.cell-assignee');
                            if (assigneeCell) {
                                assigneeCell.innerHTML = `
                                    <div class="flex items-center gap-2">
                                        <div class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-bold text-white shadow-2xs" style="background-color: ${data.user.avatar_bg || '#1E293B'}">
                                            ${data.user.avatar || 'AK'}
                                        </div>
                                        <span class="text-xs font-semibold text-slate-800">${data.user.name}</span>
                                    </div>
                                `;
                            }

                            const takeoverAction = row.querySelector('.table-takeover-action');
                            if (takeoverAction) {
                                takeoverAction.innerHTML = `
                                    <span class="inline-flex items-center gap-1 text-[11px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded px-2 py-0.5">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        <span>Tugas Anda</span>
                                    </span>
                                `;
                            }

                            const btnEdit = row.querySelector('.btn-edit-task');
                            if (btnEdit) btnEdit.setAttribute('data-assigned-to', data.user.id);

                            row.classList.add('bg-emerald-50/80');
                            setTimeout(() => row.classList.remove('bg-emerald-50/80'), 1200);
                        }
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

            // Escape Key handler
            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape') {
                    if (modalForm && !modalForm.classList.contains('hidden')) {
                        closeModalForm();
                    } else if (modalDelete && !modalDelete.classList.contains('hidden')) {
                        closeDeleteModal();
                    } else if (modalTakeover && !modalTakeover.classList.contains('hidden')) {
                        closeTakeoverModal();
                    } else if (sidebar && !sidebar.classList.contains('-translate-x-full')) {
                        closeSidebar();
                    }
                }
            });
        });
    </script>

</body>
</html>
