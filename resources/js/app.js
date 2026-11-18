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

const notificationEl = document.getElementById('notification-popup-app');

if (notificationEl) {
    let initialItems = [];
    try {
        initialItems = JSON.parse(notificationEl.dataset.notifications || '[]');
    } catch {
        initialItems = [];
    }

    createApp({
        setup() {
            const notifications = ref(initialItems);
            const timers = new Map();

            function dismiss(id) {
                const timer = timers.get(id);
                if (timer) {
                    clearTimeout(timer);
                    timers.delete(id);
                }
                notifications.value = notifications.value.filter((item) => item.id !== id);
            }

            function scheduleAutoDismiss(id, delayMs = 5000) {
                const existing = timers.get(id);
                if (existing) {
                    clearTimeout(existing);
                }
                timers.set(id, setTimeout(() => dismiss(id), delayMs));
            }

            function pauseAutoDismiss(id) {
                const existing = timers.get(id);
                if (existing) {
                    clearTimeout(existing);
                    timers.delete(id);
                }
            }

            function resumeAutoDismiss(id) {
                scheduleAutoDismiss(id, 3000);
            }

            notifications.value.forEach((item) => {
                const delay = item.type === 'error' ? 7000 : 5000;
                scheduleAutoDismiss(item.id, delay);
            });

            return {
                notifications,
                dismiss,
                pauseAutoDismiss,
                resumeAutoDismiss,
            };
        },
    }).mount(notificationEl);
}

const confirmModalEl = document.getElementById('confirm-modal-app');

if (confirmModalEl) {
    createApp({
        setup() {
            const isOpen = ref(false);
            const title = ref('');
            const message = ref('');
            const confirmLabel = ref('Lanjutkan');
            const variant = ref('danger');
            let pendingForm = null;

            function open(form) {
                pendingForm = form;
                title.value = form.dataset.confirmTitle || 'Konfirmasi Aksi';
                message.value = form.dataset.confirmMessage || 'Apakah Anda yakin ingin melanjutkan?';
                confirmLabel.value = form.dataset.confirmLabel || 'Ya, Lanjutkan';
                variant.value = form.dataset.confirmVariant || 'danger';
                isOpen.value = true;
            }

            function close() {
                isOpen.value = false;
                pendingForm = null;
            }

            function confirmAction() {
                if (!pendingForm) {
                    close();
                    return;
                }
                const formToSubmit = pendingForm;
                close();
                formToSubmit.dataset.confirmed = 'true';
                formToSubmit.submit();
            }

            document.addEventListener('submit', (event) => {
                const form = event.target;
                if (!(form instanceof HTMLFormElement)) {
                    return;
                }
                if (!form.dataset.confirmTitle || form.dataset.confirmed === 'true') {
                    return;
                }
                event.preventDefault();
                open(form);
            });

            document.addEventListener('keydown', (event) => {
                if (event.key === 'Escape' && isOpen.value) {
                    close();
                }
            });

            return {
                isOpen,
                title,
                message,
                confirmLabel,
                variant,
                close,
                confirmAction,
            };
        },
    }).mount(confirmModalEl);
}
