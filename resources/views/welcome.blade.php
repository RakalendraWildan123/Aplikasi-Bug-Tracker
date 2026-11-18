<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Aplikasi Bug Tracker') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    <div class="mx-auto max-w-6xl px-6 py-10">
        <header class="mb-8 flex flex-col gap-4 border-b border-slate-200 pb-6 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-semibold tracking-wider text-indigo-600 uppercase">Laravel 12 Foundation</p>
                <h1 class="mt-1 text-2xl font-bold text-slate-900">{{ config('app.name', 'Aplikasi Bug Tracker') }}</h1>
                <p class="mt-1 text-sm text-slate-600">
                    Vue 3 Standalone &middot; Tailwind CSS &middot; HTML5 Drag &amp; Drop API &middot; MySQL &middot; Redis &middot; Snappy PDF
                </p>
            </div>
            <div class="flex flex-wrap gap-3">
                <a
                    href="{{ route('exports.sample-pdf') }}"
                    target="_blank"
                    class="inline-flex items-center rounded-lg bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-xs transition hover:bg-indigo-700"
                >
                    Ekspor PDF (Snappy + Redis)
                </a>
                <a
                    href="{{ route('exports.sample-pdf', ['refresh' => 1]) }}"
                    target="_blank"
                    class="inline-flex items-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-xs transition hover:bg-slate-100"
                >
                    Regenerate Cache PDF
                </a>
            </div>
        </header>

        <section id="kanban-board-app" v-cloak>
            <div class="mb-4 flex items-center justify-between rounded-lg border border-indigo-100 bg-indigo-50 px-4 py-3 text-sm text-indigo-900">
                <span>@{{ lastEvent }}</span>
                <span class="rounded bg-white px-2 py-0.5 text-xs font-semibold text-indigo-700">Native HTML5 DnD</span>
            </div>

            <div class="grid gap-6 md:grid-cols-3">
                <div
                    v-for="column in columns"
                    :key="column.id"
                    @dragover="onDragOver($event, column.id)"
                    @dragleave="onDragLeave(column.id)"
                    @drop="onDrop($event, column.id)"
                    :class="[
                        'flex min-h-72 flex-col rounded-xl border p-4 transition',
                        dragOverColumn === column.id
                            ? 'border-indigo-400 bg-indigo-50/70 ring-2 ring-indigo-200'
                            : 'border-slate-200 bg-white shadow-xs'
                    ]"
                >
                    <div class="mb-4 flex items-center justify-between">
                        <h2 class="text-sm font-semibold text-slate-800">@{{ column.title }}</h2>
                        <span :class="['rounded-full px-2.5 py-0.5 text-xs font-medium', column.badgeClass]">
                            @{{ column.tasks.length }}
                        </span>
                    </div>

                    <div class="flex flex-1 flex-col gap-3">
                        <article
                            v-for="task in column.tasks"
                            :key="task.id"
                            draggable="true"
                            @dragstart="onDragStart($event, task, column.id)"
                            @dragend="onDragEnd"
                            :class="[
                                'cursor-grab rounded-lg border border-slate-200 bg-slate-50 p-3.5 transition active:cursor-grabbing',
                                dragging && dragging.taskId === task.id ? 'opacity-50' : 'hover:border-indigo-300 hover:bg-white'
                            ]"
                        >
                            <div class="flex items-center justify-between text-xs text-slate-500">
                                <span class="font-mono font-semibold text-indigo-600">@{{ task.id }}</span>
                                <span class="rounded bg-slate-200/80 px-1.5 py-0.5 font-medium text-slate-700">@{{ task.priority }}</span>
                            </div>
                            <p class="mt-2 text-sm font-medium text-slate-800">@{{ task.title }}</p>
                        </article>

                        <p v-if="column.tasks.length === 0" class="rounded-lg border border-dashed border-slate-200 p-4 text-center text-xs text-slate-400">
                            Lepaskan kartu di sini
                        </p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>
</html>
