@php
    $flashSuccess = session('status') ?? session('profile_status') ?? session('password_status');
    $flashErrors = $errors->all();
    $initialNotifications = [];

    if ($flashSuccess) {
        $initialNotifications[] = [
            'id' => 1,
            'type' => 'success',
            'title' => 'Berhasil',
            'messages' => [$flashSuccess],
        ];
    }

    if (count($flashErrors) > 0) {
        $initialNotifications[] = [
            'id' => 2,
            'type' => 'error',
            'title' => 'Terjadi Kesalahan',
            'messages' => $flashErrors,
        ];
    }
@endphp

@if (count($initialNotifications) > 0)
    <div
        id="notification-popup-app"
        data-notifications='@json($initialNotifications)'
        class="pointer-events-none fixed inset-x-4 top-5 z-50 flex flex-col items-end gap-3 sm:left-auto sm:right-5 sm:w-full sm:max-w-sm"
    >
        <div
            v-for="item in notifications"
            :key="item.id"
            :role="item.type === 'error' ? 'alert' : 'status'"
            aria-live="polite"
            @mouseenter="pauseAutoDismiss(item.id)"
            @mouseleave="resumeAutoDismiss(item.id)"
            :class="[
                'pointer-events-auto w-full rounded-xl border bg-white p-4 shadow-lg transition',
                item.type === 'error'
                    ? 'border-slate-200 border-l-4 border-l-rose-600'
                    : 'border-slate-200 border-l-4 border-l-emerald-600'
            ]"
        >
            <div class="flex items-start gap-3">
                <span
                    :class="[
                        'mt-0.5 flex h-7 w-7 shrink-0 items-center justify-center rounded-lg',
                        item.type === 'error' ? 'bg-rose-50 text-rose-600' : 'bg-emerald-50 text-emerald-600'
                    ]"
                >
                    <svg v-if="item.type === 'success'" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                    </svg>
                    <svg v-else class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3.75h.008v.008H12v-.008ZM21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </span>

                <div class="min-w-0 flex-1 text-sm">
                    <p class="font-bold text-slate-950">@{{ item.title }}</p>
                    <p v-if="item.messages.length === 1" class="mt-0.5 text-slate-600">@{{ item.messages[0] }}</p>
                    <ul v-else class="mt-1 list-disc space-y-0.5 pl-4 text-slate-600">
                        <li v-for="(msg, idx) in item.messages" :key="idx">@{{ msg }}</li>
                    </ul>
                </div>

                <button
                    type="button"
                    @click="dismiss(item.id)"
                    aria-label="Tutup notifikasi"
                    class="flex h-8 w-8 shrink-0 cursor-pointer items-center justify-center rounded-lg text-slate-400 transition hover:bg-slate-100 hover:text-slate-700"
                >
                    <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>

        {{-- Fallback teks untuk rendering non-JS / pemeriksaan awal --}}
        <noscript>
            @foreach ($initialNotifications as $item)
                <div class="pointer-events-auto w-full rounded-xl border border-slate-200 bg-white p-4 text-sm shadow-lg">
                    <p class="font-bold text-slate-950">{{ $item['title'] }}</p>
                    @foreach ($item['messages'] as $msg)
                        <p class="mt-0.5 text-slate-600">{{ $msg }}</p>
                    @endforeach
                </div>
            @endforeach
        </noscript>
    </div>
@endif
