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

            {{-- Header Halaman & Filter Ringkas --}}
            <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4 pb-5 border-b border-slate-200">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold tracking-tight text-slate-900">Papan Kanban</h1>
                    <p class="mt-1 text-sm text-slate-600">Visualisasi alur pengerjaan tugas tim pengembang (To Do, In Progress, Review, Done).</p>
                </div>

                {{-- Filter Ringkas Sederhana (Tanpa Tab Bersarang) --}}
                <form method="GET" action="{{ route('kanban.index') }}" class="flex flex-wrap items-center gap-2.5">
                    {{-- Filter Proyek --}}
                    <div>
                        <label for="select-project" class="sr-only">Filter Proyek</label>
                        <select
                            id="select-project"
                            name="project_id"
                            onchange="this.form.submit()"
                            class="rounded-lg border border-slate-300 bg-white py-2 pl-3 pr-8 text-xs sm:text-sm font-medium text-slate-700 shadow-2xs transition focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 cursor-pointer"
                        >
                            <option value="">Semua Proyek</option>
                            @foreach ($projects as $proj)
                                <option value="{{ $proj->id }}" {{ $selectedProject == $proj->id ? 'selected' : '' }}>
                                    [{{ $proj->code }}] {{ $proj->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filter Tipe Tugas --}}
                    <div>
                        <label for="select-type" class="sr-only">Filter Tipe Tugas</label>
                        <select
                            id="select-type"
                            name="task_type"
                            onchange="this.form.submit()"
                            class="rounded-lg border border-slate-300 bg-white py-2 pl-3 pr-8 text-xs sm:text-sm font-medium text-slate-700 shadow-2xs transition focus:border-slate-900 focus:outline-none focus:ring-1 focus:ring-slate-900 cursor-pointer"
                        >
                            <option value="">Semua Tipe</option>
                            <option value="feature" {{ $selectedType === 'feature' ? 'selected' : '' }}>Fitur (Feature)</option>
                            <option value="enhancement" {{ $selectedType === 'enhancement' ? 'selected' : '' }}>Peningkatan (Enhancement)</option>
                            <option value="refactor" {{ $selectedType === 'refactor' ? 'selected' : '' }}>Refaktor (Refactor)</option>
                            <option value="maintenance" {{ $selectedType === 'maintenance' ? 'selected' : '' }}>Pemeliharaan (Maintenance)</option>
                        </select>
                    </div>

                    {{-- Reset Filter Jika Ada Filter Aktif --}}
                    @if ($selectedProject || $selectedType)
                        <a
                            href="{{ route('kanban.index') }}"
                            class="inline-flex items-center gap-1 rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-semibold text-slate-600 hover:bg-slate-100 hover:text-slate-900 transition"
                        >
                            Reset
                        </a>
                    @endif
                </form>
            </div>

            {{-- Pesan Status Berhasil --}}
            <div id="toast-message" class="hidden mb-4 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-2.5 text-xs sm:text-sm font-medium text-emerald-800 items-center justify-between transition-all">
                <span id="toast-text">Status tugas berhasil diperbarui.</span>
            </div>

            {{-- Papan Kanban (4 Kolom Tegas, Ramah Desktop & Tablet) --}}
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-4.5 items-start w-full">

                @foreach ($columns as $colKey => $column)
                    <div
                        class="w-full flex flex-col rounded-xl border border-slate-200 bg-slate-100/70 shadow-xs overflow-hidden"
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
                                    class="kanban-card bg-white border border-slate-200 rounded-xl p-4 shadow-2xs hover:shadow-sm hover:border-slate-300 transition-all select-none cursor-grab active:cursor-grabbing"
                                    draggable="true"
                                    data-task-id="{{ $task->id }}"
                                    data-current-status="{{ $task->status }}"
                                >
                                    {{-- Baris Atas: Badge Proyek & Tipe Tugas --}}
                                    <div class="flex items-center justify-between gap-2 mb-2">
                                        @if ($task->project)
                                            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200 truncate max-w-[170px]">
                                                <span class="w-1.5 h-1.5 rounded-full shrink-0" style="background-color: {{ $task->project->color ?? '#4F46E5' }}"></span>
                                                <span class="truncate">{{ $task->project->code }}</span>
                                            </span>
                                        @endif

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
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider border {{ $typeBadges[$task->task_type] ?? 'bg-slate-100 text-slate-700 border-slate-200' }}">
                                            {{ $typeLabels[$task->task_type] ?? $task->task_type }}
                                        </span>
                                    </div>

                                    {{-- Modul Tugas --}}
                                    @if ($task->module)
                                        <p class="text-[11px] font-medium text-slate-500 mb-1 truncate">
                                            {{ $task->module->name }}
                                        </p>
                                    @endif

                                    {{-- Judul Tugas --}}
                                    <h4 class="font-bold text-slate-900 text-sm leading-snug mb-1.5">
                                        {{ $task->title }}
                                    </h4>

                                    {{-- Deskripsi Ringkas --}}
                                    @if ($task->description)
                                        <p class="text-xs text-slate-600 line-clamp-2 mb-3">
                                            {{ $task->description }}
                                        </p>
                                    @endif

                                    {{-- Catatan Blocker (Jika Ada) --}}
                                    @if ($task->blocker_notes)
                                        <div class="mb-3 rounded-lg border border-rose-200 bg-rose-50 p-2 text-[11px] text-rose-800 leading-tight">
                                            <span class="font-semibold text-rose-900">Kendala:</span> {{ $task->blocker_notes }}
                                        </div>
                                    @endif

                                    {{-- Footer Kartu: Deadline & Avatar Assignee --}}
                                    <div class="pt-3 border-t border-slate-100 flex items-center justify-between text-xs text-slate-500">
                                        {{-- Target Selesai --}}
                                        <div class="flex items-center gap-1.5">
                                            <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 9v7.5"/>
                                            </svg>
                                            <span class="font-medium text-slate-600 text-[11px]">
                                                {{ $task->target_date ? \Carbon\Carbon::parse($task->target_date)->translatedFormat('d M Y') : 'Tanpa batas' }}
                                            </span>
                                        </div>

                                        {{-- Assignee Pengembang --}}
                                        @if ($task->assignee)
                                            <div class="flex items-center gap-1.5" title="Ditugaskan kepada: {{ $task->assignee->name }}">
                                                @if ($task->assignee->foto_profil_url)
                                                    <img src="{{ $task->assignee->foto_profil_url }}" alt="{{ $task->assignee->name }}" class="w-6 h-6 rounded-full object-cover border border-slate-200">
                                                @else
                                                    <div class="w-6 h-6 rounded-full flex items-center justify-center text-[10px] font-bold text-white shadow-2xs" style="background-color: {{ $task->assignee->avatar_bg ?? '#1E293B' }}">
                                                        {{ $task->assignee->avatar ?? 'DV' }}
                                                    </div>
                                                @endif
                                                <span class="text-[11px] font-semibold text-slate-700 truncate max-w-[80px]">
                                                    {{ $task->assignee->name }}
                                                </span>
                                            </div>
                                        @else
                                            <span class="text-[11px] text-slate-400 italic">Belum ditugaskan</span>
                                        @endif
                                    </div>

                                    {{-- Quick Move Status Selector (Ramah Tablet & Desktop) --}}
                                    <div class="mt-3 pt-2.5 border-t border-slate-100 flex items-center justify-between gap-2">
                                        <label for="move-status-{{ $task->id }}" class="text-[10px] font-semibold text-slate-400 uppercase tracking-wider">
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

    {{-- Script Interaktivitas Papan Kanban & Drag-Drop --}}
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

            // Toast feedback
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

            // Update status kartu ke server
            const updateTaskStatus = async (taskId, newStatus, cardElement) => {
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
                        // Pindahkan kartu ke dropzone kolom baru
                        const targetDropzone = document.querySelector(`.kanban-dropzone[data-status="${newStatus}"]`);
                        if (targetDropzone && cardElement) {
                            // Hapus placeholder kosong jika ada
                            const placeholder = targetDropzone.querySelector('.empty-placeholder');
                            if (placeholder) placeholder.remove();

                            targetDropzone.appendChild(cardElement);
                            cardElement.setAttribute('data-current-status', newStatus);

                            // Sinkronkan select status pada kartu
                            const select = cardElement.querySelector('.task-status-select');
                            if (select) select.value = newStatus;

                            // Refresh counter setiap kolom
                            refreshColumnCounters();
                            showToast(data.message || 'Status tugas berhasil diperbarui.');
                        }
                    } else {
                        showToast(data.message || 'Gagal memperbarui status tugas.', true);
                    }
                } catch (error) {
                    showToast('Terjadi kesalahan koneksi saat memperbarui status.', true);
                }
            };

            // Fungsi refresh jumlah tugas di header setiap kolom
            const refreshColumnCounters = () => {
                document.querySelectorAll('.kanban-dropzone').forEach(dropzone => {
                    const status = dropzone.getAttribute('data-status');
                    const cardsCount = dropzone.querySelectorAll('.kanban-card').length;
                    const columnWrapper = dropzone.closest('[data-column-status]');
                    if (columnWrapper) {
                        const counter = columnWrapper.querySelector('.column-count');
                        if (counter) counter.textContent = cardsCount;

                        // Jika kosong, tambahkan placeholder jika belum ada
                        if (cardsCount === 0 && !dropzone.querySelector('.empty-placeholder')) {
                            dropzone.innerHTML = `
                                <div class="empty-placeholder flex flex-col items-center justify-center h-48 border-2 border-dashed border-slate-200 rounded-xl p-4 text-center text-slate-400">
                                    <svg class="w-8 h-8 mb-2 text-slate-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2.25 2.25 0 01-2.25-2.25V5.25A2.25 2.25 0 017 3h10a2.25 2.25 0 012.25 2.25v13.5A2.25 2.25 0 0119 21z"/>
                                    </svg>
                                    <p class="text-xs font-medium">Belum ada tugas</p>
                                </div>
                            `;
                        }
                    }
                });
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
        });
    </script>

</body>
</html>
