<div id="confirm-modal-app" v-cloak>
    <div
        v-if="isOpen"
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/50 p-4"
        role="dialog"
        aria-modal="true"
        aria-labelledby="confirm-modal-title"
        @click.self="close"
    >
        <div class="w-full max-w-md rounded-xl border border-slate-200 bg-white p-6 shadow-xl">
            <div class="flex items-start gap-3.5">
                <span
                    :class="[
                        'flex h-10 w-10 shrink-0 items-center justify-center rounded-lg',
                        variant === 'danger' ? 'bg-rose-50 text-rose-600' : 'bg-amber-50 text-amber-700'
                    ]"
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                </span>
                <div class="min-w-0 flex-1">
                    <h2 id="confirm-modal-title" class="text-base font-bold text-slate-950">@{{ title }}</h2>
                    <p class="mt-1.5 text-sm leading-relaxed text-slate-600">@{{ message }}</p>
                </div>
            </div>

            <div class="mt-6 flex flex-col-reverse gap-2.5 sm:flex-row sm:justify-end">
                <button
                    type="button"
                    @click="close"
                    class="inline-flex min-h-11 cursor-pointer items-center justify-center rounded-lg border border-slate-300 bg-white px-4 py-2 text-sm font-semibold text-slate-700 transition hover:bg-slate-100"
                >
                    Batal
                </button>
                <button
                    type="button"
                    @click="confirmAction"
                    :class="[
                        'inline-flex min-h-11 cursor-pointer items-center justify-center rounded-lg px-4 py-2 text-sm font-semibold text-white transition',
                        variant === 'danger' ? 'bg-rose-600 hover:bg-rose-700' : 'bg-slate-950 hover:bg-slate-800'
                    ]"
                >
                    @{{ confirmLabel }}
                </button>
            </div>
        </div>
    </div>
</div>
