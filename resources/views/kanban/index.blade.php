<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Papan Kanban &mdash; {{ config('app.name', 'Aplikasi Bug Tracker') }}</title>
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

            {{-- Menu Navigasi Utama --}}
            <nav class="flex-1 px-3 py-5 space-y-1.5 overflow-y-auto" aria-label="Navigasi Sidebar">
                <p class="px-3 pb-1 text-[11px] font-bold uppercase tracking-wider text-slate-400">Menu Utama</p>

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
                </div>
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
                                <div
                                    class="kanban-card bg-white border border-slate-200 rounded-xl p-4 shadow-2xs hover:shadow-sm hover:border-slate-300 transition-all select-none cursor-grab active:cursor-grabbing relative"
                                    draggable="true"
                                    id="card-task-{{ $task->id }}"
                                    data-task-id="{{ $task->id }}"
                                    data-current-status="{{ $task->status }}"
                                    data-project-id="{{ $task->project_id ?? '' }}"
                                    data-assigned-to="{{ $task->assigned_to ?? '' }}"
                                >
                                    {{-- Baris Atas: Badge Proyek, Tipe Tugas & TOMBOL EDIT KARTU --}}
                                    <div class="flex items-center justify-between gap-2 mb-2">
                                        <div class="flex items-center gap-1.5 truncate max-w-[170px]">
                                            @if ($task->project)
                                                <span class="card-project-badge inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200 truncate">
                                                    <span class="w-1.5 h-1.5 rounded-full shrink-0" style="background-color: {{ $task->project->color ?? '#4F46E5' }}"></span>
                                                    <span class="truncate">{{ $task->project->code }}</span>
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
                                                ];
                                                $typeLabels = [
                                                    'feature' => 'Fitur',
                                                    'enhancement' => 'Peningkatan',
                                                    'refactor' => 'Refaktor',
                                                    'maintenance' => 'Maintenance',
                                                ];
                                            @endphp
                                            <span class="card-type-badge px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider border {{ $typeBadges[$task->task_type] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}">
                                                {{ $typeLabels[$task->task_type] ?? $task->task_type }}
                                            </span>

                                            {{-- TOMBOL EDIT PADA KARTU --}}
                                            <button
                                                type="button"
                                                class="btn-edit-task p-1 rounded-md text-slate-400 hover:text-slate-900 hover:bg-slate-100 transition cursor-pointer"
                                                title="Edit Tugas: {{ $task->title }}"
                                                aria-label="Edit Tugas {{ $task->title }}"
                                                data-task-id="{{ $task->id }}"
                                                data-title="{{ $task->title }}"
                                                data-description="{{ $task->description }}"
                                                data-task-type="{{ $task->task_type }}"
                                                data-status="{{ $task->status }}"
                                                data-target-date="{{ $task->target_date ? \Carbon\Carbon::parse($task->target_date)->format('Y-m-d') : '' }}"
                                                data-blocker-notes="{{ $task->blocker_notes }}"
                                                data-assigned-to="{{ $task->assigned_to }}"
                                                data-project-code="{{ $task->project?->code }}"
                                                data-project-name="{{ $task->project?->name }}"
                                            >
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10"/>
                                                </svg>
                                            </button>
                                        </div>
                                    </div>

                                    {{-- Modul Tugas --}}
                                    @if ($task->module)
                                        <p class="card-module-name text-[11px] font-medium text-slate-500 mb-1 truncate">
                                            {{ $task->module->name }}
                                        </p>
                                    @endif

                                    {{-- Judul Tugas --}}
                                    <h4 class="card-title font-bold text-slate-900 text-sm leading-snug mb-1.5">
                                        {{ $task->title }}
                                    </h4>

                                    {{-- Deskripsi Ringkas --}}
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
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5"/>
                                            </svg>
                                            <span class="card-target-date font-medium text-slate-600 text-[11px]">
                                                {{ $task->target_date ? \Carbon\Carbon::parse($task->target_date)->translatedFormat('d M Y') : 'Tanpa batas' }}
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

                                    {{-- Quick Move Status Selector & Ambil Alih Tugas (Ramah Tablet & Desktop) --}}
                                    <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between gap-2">
                                        <div class="flex items-center gap-1.5 min-w-0">
                                            <label for="move-status-{{ $task->id }}" class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider shrink-0">
                                                Status:
                                            </label>
                                            <select
                                                id="move-status-{{ $task->id }}"
                                                data-task-id="{{ $task->id }}"
                                                class="task-status-select text-[11px] font-medium bg-slate-50 border border-slate-200 rounded-md py-1 pl-2 pr-6 text-slate-700 focus:outline-none focus:ring-1 focus:ring-slate-900 cursor-pointer"
                                            >
                                                <option value="todo" {{ $task->status === 'todo' ? 'selected' : '' }}>To Do</option>
                                                <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                                <option value="review" {{ $task->status === 'review' ? 'selected' : '' }}>Review</option>
                                                <option value="done" {{ $task->status === 'done' ? 'selected' : '' }}>Done</option>
                                            </select>
                                        </div>

                                        {{-- Tombol Ambil Alih / Indikator Pemilik Tugas --}}
                                        <div class="card-takeover-action shrink-0">
                                            @if ($task->assigned_to && (int)$task->assigned_to !== (int)$user->id)
                                                <button
                                                    type="button"
                                                    class="btn-takeover-task text-[11px] font-semibold text-amber-700 hover:text-amber-800 bg-amber-50 hover:bg-amber-100 border border-amber-200/80 rounded-md px-2 py-1 transition flex items-center gap-1 cursor-pointer"
                                                    data-task-id="{{ $task->id }}"
                                                    data-task-title="{{ $task->title }}"
                                                    data-project-name="{{ $task->project?->name }}"
                                                    data-project-code="{{ $task->project?->code }}"
                                                    data-developer-id="{{ $task->assigned_to }}"
                                                    data-developer-name="{{ $task->assignee?->name ?? 'Belum ditugaskan' }}"
                                                    data-developer-role="{{ $task->assignee?->role_title ?? $task->assignee?->roleLabel() }}"
                                                    data-developer-avatar="{{ $task->assignee?->avatar ?? 'DV' }}"
                                                    title="Ambil Alih Tugas dari {{ $task->assignee?->name }}"
                                                >
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 21L3 16.5m0 0L7.5 12M3 16.5h13.5m0-13.5L21 7.5m0 0L16.5 12M21 7.5H7.5"/>
                                                    </svg>
                                                    <span>Ambil Alih</span>
                                                </button>
                                            @elseif ($task->assigned_to && (int)$task->assigned_to === (int)$user->id)
                                                <span class="inline-flex items-center gap-1 text-[10px] font-bold text-emerald-700 bg-emerald-50 border border-emerald-200 rounded px-1.5 py-0.5">
                                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                    <span>Tugas Anda</span>
                                                </span>
                                            @else
                                                <button
                                                    type="button"
                                                    class="btn-takeover-task text-[11px] font-semibold text-indigo-700 hover:text-indigo-800 bg-indigo-50 hover:bg-indigo-100 border border-indigo-200 rounded-md px-2 py-1 transition flex items-center gap-1 cursor-pointer"
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
                                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
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
                        <label for="edit-task-assigned-to" class="block text-xs font-semibold text-slate-800 mb-1">
                            Ditugaskan Kepada
                        </label>
                        <select
                            id="edit-task-assigned-to"
                            class="block w-full rounded-lg border border-slate-300 py-2 pl-3 pr-8 text-sm text-slate-900 transition focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 cursor-pointer"
                        >
                            <option value="">Belum Ditugaskan</option>
                            @foreach ($developers as $dev)
                                <option value="{{ $dev->id }}">{{ $dev->name }}</option>
                            @endforeach
                        </select>
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
                        <div class="w-8 h-8 rounded-full bg-slate-900 text-white flex items-center justify-center text-xs font-bold shrink-0 mt-0.5" id="takeover-dev-avatar">
                            DV
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
            const editTaskAssignedTo = document.getElementById('edit-task-assigned-to');
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

                editTaskId.value = taskId;
                editTaskTitle.value = title;
                editTaskDesc.value = desc;
                editTaskType.value = taskType;
                editTaskStatus.value = status;
                editTaskTargetDate.value = targetDate;
                editTaskAssignedTo.value = assignedTo;
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
                    assigned_to: editTaskAssignedTo.value || null,
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
                                const typeLabels = { feature: 'Fitur', enhancement: 'Peningkatan', refactor: 'Refaktor', maintenance: 'Maintenance' };
                                const typeBadges = {
                                    feature: 'bg-indigo-50 text-indigo-700 border-indigo-200',
                                    enhancement: 'bg-violet-50 text-violet-700 border-violet-200',
                                    refactor: 'bg-amber-50 text-amber-700 border-amber-200',
                                    maintenance: 'bg-slate-100 text-slate-700 border-slate-200'
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

                            // Update Target Date
                            const targetDateEl = card.querySelector('.card-target-date');
                            if (targetDateEl) {
                                targetDateEl.textContent = payload.target_date || 'Tanpa batas';
                            }

                            // Update Assignee Name
                            const assigneeNameEl = card.querySelector('.card-assignee-name');
                            if (assigneeNameEl) {
                                const selectedOption = editTaskAssignedTo.options[editTaskAssignedTo.selectedIndex];
                                assigneeNameEl.textContent = payload.assigned_to && selectedOption ? selectedOption.text : 'Belum ditugaskan';
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
                                btnEdit.setAttribute('data-assigned-to', payload.assigned_to || '');
                            }

                            // Update data attribute assigned-to pada kartu
                            card.setAttribute('data-assigned-to', payload.assigned_to || '');

                            // Pindahkan kartu jika status berubah
                            const currentStatus = card.getAttribute('data-current-status');
                            if (payload.status !== currentStatus) {
                                const targetDropzone = document.querySelector(`.kanban-dropzone[data-status="${payload.status}"]`);
                                if (targetDropzone) {
                                    const placeholder = targetDropzone.querySelector('.empty-placeholder');
                                    if (placeholder) placeholder.remove();

                                    targetDropzone.insertBefore(card, targetDropzone.firstChild);
                                    card.setAttribute('data-current-status', payload.status);

                                    const select = card.querySelector('.task-status-select');
                                    if (select) select.value = payload.status;
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

            // Update status kartu secara instan (< 0.8 detik, optimistik) & persistensi asinkron ke server
            const updateTaskStatus = async (taskId, newStatus, cardElement) => {
                if (!taskId || !newStatus || !cardElement) return;

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

                // Perbarui status atribut & kontrol input pada kartu
                cardElement.setAttribute('data-current-status', newStatus);
                const select = cardElement.querySelector('.task-status-select');
                if (select) select.value = newStatus;

                const btnEdit = cardElement.querySelector('.btn-edit-task');
                if (btnEdit) btnEdit.setAttribute('data-status', newStatus);

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
                        // Rollback jika server mengembalikan error
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
                cardElement.setAttribute('data-current-status', previousStatus);

                const select = cardElement.querySelector('.task-status-select');
                if (select) select.value = previousStatus;

                const btnEdit = cardElement.querySelector('.btn-edit-task');
                if (btnEdit) btnEdit.setAttribute('data-status', previousStatus);

                refreshColumnCounters();
            };

            // Event listener ganti status via select
            document.addEventListener('change', (e) => {
                if (e.target && e.target.classList.contains('task-status-select')) {
                    const select = e.target;
                    const taskId = select.getAttribute('data-task-id');
                    const newStatus = select.value;
                    const card = select.closest('.kanban-card');
                    if (taskId && newStatus && card) {
                        updateTaskStatus(taskId, newStatus, card);
                    }
                }
            });

            // Native HTML5 Drag and Drop
            let draggedCard = null;

            document.querySelectorAll('.kanban-card').forEach(card => {
                card.addEventListener('dragstart', (e) => {
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

                        // Update tampilan kartu di papan Kanban seketika
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
                            if (btnEdit) btnEdit.setAttribute('data-assigned-to', data.user.id);

                            card.classList.add('ring-2', 'ring-emerald-400');
                            setTimeout(() => card.classList.remove('ring-2', 'ring-emerald-400'), 800);

                            applyInstantFilters();
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
        });
    </script>

</body>
</html>
