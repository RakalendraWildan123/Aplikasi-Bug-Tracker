@if (isset($pendingApprovals) && $pendingApprovals->isNotEmpty())
    <div id="pending-handovers-banner" class="rounded-xl border border-amber-200 bg-amber-50/90 p-4 shadow-xs">
        <div class="flex items-start gap-3">
            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-amber-100 text-amber-700 border border-amber-200">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                </svg>
            </div>
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between gap-2 flex-wrap">
                    <h2 class="text-sm font-bold text-amber-950">
                        Menunggu Persetujuan Anda ({{ $pendingApprovals->count() }})
                    </h2>
                    <span class="text-[11px] font-medium text-amber-800">Tugas baru berpindah setelah Anda menyetujui</span>
                </div>

                <div class="mt-3 space-y-2.5">
                    @foreach ($pendingApprovals as $pendingTask)
                        @php
                            $pending = $pendingTask->pending_handover ?? [];
                            $isTransfer = ($pending['type'] ?? '') === 'transfer';
                            $requesterName = $pending['requested_by_name'] ?? 'Rekan Tim';
                        @endphp
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 rounded-lg border border-amber-200/90 bg-white p-3 shadow-2xs">
                            <div class="min-w-0 space-y-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    @if ($pendingTask->project)
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[10px] font-bold text-white" style="background-color: {{ $pendingTask->project->color ?? '#4F46E5' }};">
                                            {{ $pendingTask->project->code }}
                                        </span>
                                    @endif
                                    <span class="text-xs sm:text-sm font-bold text-slate-900">{{ $pendingTask->title }}</span>
                                </div>
                                <p class="text-xs text-slate-600">
                                    @if ($isTransfer)
                                        <strong class="text-slate-900">{{ $requesterName }}</strong> ingin <strong>memberikan / mengalihkan</strong> tugas ini kepada Anda.
                                    @else
                                        <strong class="text-slate-900">{{ $requesterName }}</strong> ingin <strong>mengambil alih</strong> tugas ini dari Anda.
                                    @endif
                                </p>
                                @if (! empty($pending['note']))
                                    <p class="text-[11px] text-slate-500 italic">Catatan: "{{ $pending['note'] }}"</p>
                                @endif
                            </div>

                            <div class="flex items-center gap-2 shrink-0">
                                <form method="POST" action="{{ route(($routePrefix ?? 'tasks') . '.handover.approve', $pendingTask->id) }}">
                                    @csrf
                                    <button
                                        type="submit"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-semibold transition cursor-pointer shadow-2xs"
                                    >
                                        <span>✓ Setujui</span>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route(($routePrefix ?? 'tasks') . '.handover.reject', $pendingTask->id) }}">
                                    @csrf
                                    <button
                                        type="submit"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-rose-200 bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-semibold transition cursor-pointer"
                                    >
                                        <span>✕ Tolak</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif
