import './bootstrap';
import { createApp, ref } from 'vue';

const mountEl = document.getElementById('kanban-board-app');

if (mountEl) {
    createApp({
        setup() {
            const columns = ref([
                {
                    id: 'todo',
                    title: 'To Do',
                    badgeClass: 'bg-slate-200 text-slate-700',
                    tasks: [
                        { id: 'BUG-101', title: 'Validasi koneksi MySQL & migrasi awal', priority: 'High' },
                        { id: 'BUG-102', title: 'Konfigurasi Redis untuk caching ekspor', priority: 'Medium' },
                    ],
                },
                {
                    id: 'in_progress',
                    title: 'In Progress',
                    badgeClass: 'bg-amber-100 text-amber-800',
                    tasks: [
                        { id: 'BUG-103', title: 'Integrasi HTML5 Drag & Drop API native', priority: 'High' },
                    ],
                },
                {
                    id: 'done',
                    title: 'Done',
                    badgeClass: 'bg-emerald-100 text-emerald-800',
                    tasks: [
                        { id: 'BUG-100', title: 'Inisialisasi Laravel 12 + Vue 3 Standalone + Tailwind CSS', priority: 'Low' },
                    ],
                },
            ]);

            const dragging = ref(null);
            const dragOverColumn = ref(null);
            const lastEvent = ref('Tarik kartu antar kolom untuk menguji HTML5 Drag & Drop API.');

            function onDragStart(event, task, sourceColumnId) {
                dragging.value = { taskId: task.id, sourceColumnId };
                event.dataTransfer.effectAllowed = 'move';
                event.dataTransfer.setData('text/plain', JSON.stringify({
                    taskId: task.id,
                    sourceColumnId,
                }));
            }

            function onDragOver(event, columnId) {
                event.preventDefault();
                event.dataTransfer.dropEffect = 'move';
                dragOverColumn.value = columnId;
            }

            function onDragLeave(columnId) {
                if (dragOverColumn.value === columnId) {
                    dragOverColumn.value = null;
                }
            }

            function onDrop(event, targetColumnId) {
                event.preventDefault();
                dragOverColumn.value = null;

                let payload = dragging.value;
                if (!payload) {
                    try {
                        payload = JSON.parse(event.dataTransfer.getData('text/plain'));
                    } catch {
                        return;
                    }
                }

                if (!payload || payload.sourceColumnId === targetColumnId) {
                    dragging.value = null;
                    return;
                }

                const sourceCol = columns.value.find((c) => c.id === payload.sourceColumnId);
                const targetCol = columns.value.find((c) => c.id === targetColumnId);
                if (!sourceCol || !targetCol) {
                    dragging.value = null;
                    return;
                }

                const taskIndex = sourceCol.tasks.findIndex((t) => t.id === payload.taskId);
                if (taskIndex === -1) {
                    dragging.value = null;
                    return;
                }

                const [movedTask] = sourceCol.tasks.splice(taskIndex, 1);
                targetCol.tasks.push(movedTask);
                lastEvent.value = `${movedTask.id} dipindahkan dari "${sourceCol.title}" ke "${targetCol.title}".`;
                dragging.value = null;
            }

            function onDragEnd() {
                dragging.value = null;
                dragOverColumn.value = null;
            }

            return {
                columns,
                dragging,
                dragOverColumn,
                lastEvent,
                onDragStart,
                onDragOver,
                onDragLeave,
                onDrop,
                onDragEnd,
            };
        },
    }).mount(mountEl);
}

const loginEl = document.getElementById('login-form-app');

if (loginEl) {
    createApp({
        setup() {
            const showPassword = ref(false);
            const isSubmitting = ref(false);

            function onSubmit() {
                isSubmitting.value = true;
            }

            return {
                showPassword,
                isSubmitting,
                onSubmit,
            };
        },
    }).mount(loginEl);
}

const accountPasswordEl = document.getElementById('account-password-app');

if (accountPasswordEl) {
    createApp({
        setup() {
            const showPasswords = ref(false);

            return {
                showPasswords,
            };
        },
    }).mount(accountPasswordEl);
}
