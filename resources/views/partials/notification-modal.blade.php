{{-- MODAL POP-UP NOTIFIKASI (Bersih, Tegas, Tanpa Tab Bersarang, Ramah Desktop & Tablet) --}}
<div
    id="modal-notifications"
    class="fixed inset-0 z-50 bg-slate-900/60 backdrop-blur-xs flex items-center justify-center p-4 hidden"
    role="dialog"
    aria-modal="true"
    aria-labelledby="notification-modal-title"
>
    <div class="bg-white border border-slate-200 rounded-2xl shadow-2xl max-w-lg w-full overflow-hidden flex flex-col max-h-[85vh] transition-all transform duration-200">

        {{-- Header Pop-up Notifikasi --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 bg-slate-50 shrink-0">
            <div class="flex items-center gap-2.5">
                <div class="w-8 h-8 rounded-lg bg-slate-900 text-white flex items-center justify-center shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                    </svg>
                </div>
                <div>
                    <h3 id="notification-modal-title" class="text-base font-bold text-slate-900 leading-tight flex items-center gap-2">
                        <span>Notifikasi</span>
                        <span id="notif-unread-count-badge" class="px-2 py-0.5 rounded-full text-[11px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">
                            0 Baru
                        </span>
                    </h3>
                </div>
            </div>

            <div class="flex items-center gap-1.5">
                <button
                    type="button"
                    id="btn-notif-mark-all"
                    class="text-xs font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-200/60 px-2.5 py-1.5 rounded-lg transition cursor-pointer"
                    title="Tandai semua notifikasi sudah dibaca"
                >
                    Tandai Semua Dibaca
                </button>
                <button
                    type="button"
                    id="btn-close-notif-x"
                    class="p-1 rounded-lg text-slate-400 hover:text-slate-700 hover:bg-slate-200/60 transition cursor-pointer"
                    aria-label="Tutup Notifikasi"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>

        {{-- Filter Ringkas Tanpa Tab Bersarang (Hanya 'Semua' & 'Belum Dibaca') --}}
        <div class="px-6 py-2.5 bg-slate-100/70 border-b border-slate-200 flex items-center justify-between gap-2 shrink-0">
            <div class="flex items-center gap-1.5" role="group" aria-label="Filter Notifikasi">
                <button
                    type="button"
                    id="btn-filter-all"
                    class="btn-notif-filter px-3 py-1 rounded-md text-xs font-semibold transition cursor-pointer bg-slate-900 text-white shadow-2xs"
                    data-filter="all"
                >
                    Semua (<span id="notif-count-all">0</span>)
                </button>
                <button
                    type="button"
                    id="btn-filter-unread"
                    class="btn-notif-filter px-3 py-1 rounded-md text-xs font-medium text-slate-600 hover:text-slate-900 hover:bg-slate-200/60 transition cursor-pointer"
                    data-filter="unread"
                >
                    Belum Dibaca (<span id="notif-count-unread">0</span>)
                </button>
            </div>

            <button
                type="button"
                id="btn-notif-clear-all"
                class="text-[11px] font-semibold text-slate-400 hover:text-rose-600 transition cursor-pointer"
                title="Hapus semua notifikasi dari daftar"
            >
                Hapus Semua
            </button>
        </div>

        {{-- Wadah Daftar Item Notifikasi (Scrollable, Tegas, Sederhana) --}}
        <div id="notif-items-list" class="overflow-y-auto flex-1 divide-y divide-slate-100 max-h-[50vh] min-h-[220px]">
            {{-- Placeholder Loading --}}
            <div id="notif-loading" class="flex flex-col items-center justify-center p-8 text-center text-slate-400">
                <svg class="w-6 h-6 animate-spin text-slate-700 mb-2" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
                <p class="text-xs font-medium">Memuat notifikasi terbaru...</p>
            </div>

            {{-- Empty State (Tampil jika kosong atau semua telah dibersihkan) --}}
            <div id="notif-empty" class="hidden flex-col items-center justify-center p-8 text-center text-slate-400 min-h-[200px]">
                <div class="w-12 h-12 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center mb-2.5 text-slate-400">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0"/>
                    </svg>
                </div>
                <p class="text-xs font-bold text-slate-700">Tidak Ada Notifikasi</p>
                <p class="text-[11px] text-slate-500 mt-0.5">Semua pemberitahuan tugas dan bug telah selesai ditinjau.</p>
            </div>
        </div>

        {{-- Footer Pop-up --}}
        <div class="px-6 py-3 border-t border-slate-200 bg-slate-50 flex items-center justify-between shrink-0 text-xs text-slate-500">
            <span id="notif-footer-summary">Aktivitas pengerjaan tugas & temuan bug terbaru</span>
            <button
                type="button"
                id="btn-close-notif-footer"
                class="px-4 py-2 rounded-lg border border-slate-300 text-xs font-semibold text-slate-700 hover:bg-slate-100 transition cursor-pointer"
            >
                Tutup
            </button>
        </div>

    </div>
</div>

{{-- Script Interaktif Pop-up Notifikasi --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const notifModal = document.getElementById('modal-notifications');
        const notifItemsList = document.getElementById('notif-items-list');
        const notifLoading = document.getElementById('notif-loading');
        const notifEmpty = document.getElementById('notif-empty');
        const notifUnreadBadge = document.getElementById('notif-unread-count-badge');
        const notifCountAll = document.getElementById('notif-count-all');
        const notifCountUnread = document.getElementById('notif-count-unread');
        const btnMarkAll = document.getElementById('btn-notif-mark-all');
        const btnClearAll = document.getElementById('btn-notif-clear-all');
        const filterButtons = document.querySelectorAll('.btn-notif-filter');
        const btnCloseX = document.getElementById('btn-close-notif-x');
        const btnCloseFooter = document.getElementById('btn-close-notif-footer');

        let notificationsData = [];
        let currentFilter = 'all';

        // Buka modal notifikasi
        const openNotifModal = () => {
            if (!notifModal) return;
            notifModal.classList.remove('hidden');
            fetchNotifications();
        };

        // Tutup modal notifikasi
        const closeNotifModal = () => {
            if (!notifModal) return;
            notifModal.classList.add('hidden');
        };

        // Bind semua tombol pembuka notifikasi (desktop & tablet)
        document.querySelectorAll('.btn-trigger-notifications').forEach(btn => {
            btn.addEventListener('click', (e) => {
                e.preventDefault();
                openNotifModal();
            });
        });

        // Tombol penutup modal
        btnCloseX?.addEventListener('click', closeNotifModal);
        btnCloseFooter?.addEventListener('click', closeNotifModal);

        // Tutup jika klik backdrop
        notifModal?.addEventListener('click', (e) => {
            if (e.target === notifModal) {
                closeNotifModal();
            }
        });

        // Tutup jika tekan tombol Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && notifModal && !notifModal.classList.contains('hidden')) {
                closeNotifModal();
            }
        });

        // Render badge lonceng di navbar/header
        const updateNavbarBadges = (unreadCount) => {
            document.querySelectorAll('.notification-badge-count').forEach(badge => {
                if (unreadCount > 0) {
                    badge.textContent = unreadCount;
                    badge.classList.remove('hidden');
                } else {
                    badge.classList.add('hidden');
                }
            });
        };

        // Render daftar notifikasi ke DOM
        const renderNotifications = () => {
            if (!notifItemsList) return;

            // Hapus item sebelumnya (kecuali loading & empty state)
            notifItemsList.querySelectorAll('.notif-item-card').forEach(el => el.remove());

            const filtered = notificationsData.filter(item => {
                if (currentFilter === 'unread') {
                    return !item.is_read;
                }
                return true;
            });

            const unreadCount = notificationsData.filter(i => !i.is_read).length;
            const totalCount = notificationsData.length;

            if (notifCountAll) notifCountAll.textContent = totalCount;
            if (notifCountUnread) notifCountUnread.textContent = unreadCount;
            if (notifUnreadBadge) notifUnreadBadge.textContent = `${unreadCount} Belum Dibaca`;

            updateNavbarBadges(unreadCount);

            if (filtered.length === 0) {
                notifEmpty?.classList.remove('hidden');
                notifEmpty?.classList.add('flex');
                return;
            } else {
                notifEmpty?.classList.add('hidden');
                notifEmpty?.classList.remove('flex');
            }

            const typeIcons = {
                task: `
                    <div class="w-8 h-8 rounded-lg bg-sky-50 text-sky-700 border border-sky-200 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                `,
                bug: `
                    <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-700 border border-rose-200 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z"/>
                        </svg>
                    </div>
                `,
                blocker: `
                    <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-700 border border-amber-200 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0-10.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.002A11.959 11.959 0 0112 2.714z"/>
                        </svg>
                    </div>
                `,
                release: `
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center justify-center shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 01-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 006.16-12.12A14.98 14.98 0 009.631 8.41m5.96 5.96a14.926 14.926 0 01-5.841 2.58m-.119-8.54a6 6 0 00-7.381 5.84h4.8m2.58-5.84a14.927 14.927 0 00-2.58 5.84m2.699 2.7c-.103.021-.207.041-.311.06a15.09 15.09 0 01-2.448-2.448 14.9 14.9 0 01.06-.312m-2.24 2.39a4.493 4.493 0 00-1.757 4.306 4.493 4.493 0 004.306-1.758M16.5 9a1.5 1.5 0 11-3 0 1.5 1.5 0 013 0z"/>
                        </svg>
                    </div>
                `
            };

            filtered.forEach(item => {
                const itemEl = document.createElement('div');
                itemEl.className = `notif-item-card p-4 transition-colors flex items-start gap-3.5 relative group border-l-4 ${item.is_read ? 'border-l-transparent bg-white hover:bg-slate-50/80' : 'border-l-indigo-600 bg-indigo-50/25 hover:bg-indigo-50/40'}`;
                itemEl.setAttribute('data-notif-id', item.id);

                const iconHtml = typeIcons[item.type] || typeIcons.task;

                itemEl.innerHTML = `
                    ${iconHtml}
                    <div class="flex-1 min-w-0 pr-6">
                        <div class="flex items-center gap-2 mb-0.5">
                            <h4 class="text-xs font-bold text-slate-900 truncate">${item.title}</h4>
                            ${!item.is_read ? '<span class="w-2 h-2 rounded-full bg-indigo-600 shrink-0" title="Belum dibaca"></span>' : ''}
                        </div>
                        <p class="text-xs text-slate-600 leading-snug line-clamp-2">${item.message}</p>
                        <div class="flex items-center gap-2 mt-2 text-[10px] text-slate-400 font-medium">
                            <span>${item.time_ago}</span>
                            <span>&bull;</span>
                            <span class="px-1.5 py-0.2 rounded bg-slate-100 text-slate-600 font-semibold border border-slate-200">${item.project_code}</span>
                            ${item.link ? `<span>&bull;</span><a href="${item.link}" class="text-indigo-600 hover:text-indigo-800 font-semibold">Buka Fitur</a>` : ''}
                        </div>
                    </div>
                    <div class="flex items-center gap-1 shrink-0 absolute top-3.5 right-3.5">
                        ${!item.is_read ? `
                            <button
                                type="button"
                                class="btn-item-mark-read p-1 rounded-md text-slate-400 hover:text-indigo-600 hover:bg-slate-100 transition cursor-pointer"
                                title="Tandai sudah dibaca"
                                data-id="${item.id}"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5"/>
                                </svg>
                            </button>
                        ` : ''}
                        <button
                            type="button"
                            class="btn-item-delete p-1 rounded-md text-slate-400 hover:text-rose-600 hover:bg-slate-100 transition cursor-pointer"
                            title="Hapus notifikasi"
                            data-id="${item.id}"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                `;

                notifItemsList.appendChild(itemEl);
            });
        };

        // Ambil data notifikasi dari server via AJAX
        const fetchNotifications = async () => {
            notifLoading?.classList.remove('hidden');
            notifEmpty?.classList.add('hidden');

            try {
                const response = await fetch('/notifications', {
                    headers: { 'Accept': 'application/json' }
                });
                const data = await response.json();

                if (response.ok && data.success) {
                    notificationsData = data.notifications || [];
                    renderNotifications();
                }
            } catch (err) {
                console.error('Gagal memuat data notifikasi:', err);
            } finally {
                notifLoading?.classList.add('hidden');
            }
        };

        // Event listener filter Semua vs Belum Dibaca
        filterButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                filterButtons.forEach(b => {
                    b.classList.remove('bg-slate-900', 'text-white', 'shadow-2xs');
                    b.classList.add('text-slate-600', 'hover:text-slate-900', 'hover:bg-slate-200/60');
                });

                btn.classList.add('bg-slate-900', 'text-white', 'shadow-2xs');
                btn.classList.remove('text-slate-600', 'hover:text-slate-900', 'hover:bg-slate-200/60');

                currentFilter = btn.getAttribute('data-filter') || 'all';
                renderNotifications();
            });
        });

        // Event listener tandai sebuah notifikasi dibaca
        document.addEventListener('click', async (e) => {
            const btnRead = e.target.closest('.btn-item-mark-read');
            if (btnRead) {
                const id = btnRead.getAttribute('data-id');
                if (!id) return;

                // Optimistic UI update
                const target = notificationsData.find(n => n.id === id);
                if (target) target.is_read = true;
                renderNotifications();

                try {
                    await fetch(`/notifications/${id}/read`, {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Accept': 'application/json'
                        }
                    });
                } catch (err) {
                    console.error('Gagal menandai notifikasi dibaca:', err);
                }
            }

            const btnDelete = e.target.closest('.btn-item-delete');
            if (btnDelete) {
                const id = btnDelete.getAttribute('data-id');
                if (!id) return;

                // Optimistic UI update
                notificationsData = notificationsData.filter(n => n.id !== id);
                renderNotifications();

                try {
                    await fetch(`/notifications/${id}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Accept': 'application/json'
                        }
                    });
                } catch (err) {
                    console.error('Gagal menghapus notifikasi:', err);
                }
            }
        });

        // Event listener tandai semua dibaca
        btnMarkAll?.addEventListener('click', async () => {
            const allIds = notificationsData.map(n => n.id);
            notificationsData.forEach(n => n.is_read = true);
            renderNotifications();

            try {
                await fetch('/notifications/mark-all-read', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ ids: allIds })
                });
            } catch (err) {
                console.error('Gagal menandai semua dibaca:', err);
            }
        });

        // Event listener bersihkan semua notifikasi
        btnClearAll?.addEventListener('click', async () => {
            const allIds = notificationsData.map(n => n.id);
            notificationsData = [];
            renderNotifications();

            try {
                await fetch('/notifications', {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ ids: allIds })
                });
            } catch (err) {
                console.error('Gagal membersihkan notifikasi:', err);
            }
        });

        // Panggil saat awal untuk inisialisasi badge di navbar
        fetchNotifications();

        if (new URLSearchParams(window.location.search).has('open_notif')) {
            setTimeout(openNotifModal, 300);
        }
    });
</script>
