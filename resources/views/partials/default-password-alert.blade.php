@if (isset($user) && $user->usesDefaultPassword())
    <div id="default-password-alert" role="alert" class="mb-5 rounded-xl border border-rose-200 bg-rose-50/90 p-4 shadow-2xs">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3">
            <div class="flex items-start gap-3">
                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-rose-100 text-rose-700 border border-rose-200">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                </div>
                <div class="min-w-0">
                    <h3 class="text-xs sm:text-sm font-bold text-rose-950">
                        Keamanan Akun: Segera Ganti Password Default Anda
                    </h3>
                    <p class="mt-0.5 text-xs text-rose-800">
                        Akun Anda saat ini masih menggunakan kata sandi bawaan (<code class="px-1 py-0.5 rounded bg-rose-100 font-semibold text-rose-900">password</code>). Demi keamanan, segera ubah kata sandi Anda.
                    </p>
                </div>
            </div>

            <div class="shrink-0 sm:ml-2">
                @if (request()->routeIs('account.edit'))
                    <button
                        type="button"
                        onclick="document.getElementById('btn-open-modal')?.click()"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-rose-600 hover:bg-rose-700 px-3.5 py-2 text-xs font-semibold text-white transition shadow-2xs cursor-pointer"
                    >
                        <span>Ganti Password Sekarang</span>
                    </button>
                @else
                    <a
                        href="{{ route('account.edit') }}"
                        class="inline-flex items-center gap-1.5 rounded-lg bg-rose-600 hover:bg-rose-700 px-3.5 py-2 text-xs font-semibold text-white transition shadow-2xs"
                    >
                        <span>Ganti Password Sekarang</span>
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                        </svg>
                    </a>
                @endif
            </div>
        </div>
    </div>
@endif
