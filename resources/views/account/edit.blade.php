@extends('layouts.app')

@section('title', 'Akun Saya')

@section('content')
    <div class="space-y-6">
        <header class="border-b border-slate-200 pb-5">
            <p class="text-xs font-semibold tracking-wider text-indigo-600 uppercase">Pengaturan Pribadi</p>
            <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-950">Akun Saya</h1>
            <p class="mt-1 text-sm text-slate-600">
                Perbarui nama, email, profil avatar, dan password akun Anda secara mandiri.
            </p>
        </header>

        {{-- Kartu 1: Informasi Profil --}}
        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-xs sm:p-7">
            <div class="mb-5 flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-4">
                <div>
                    <h2 class="text-base font-bold text-slate-950">Informasi Profil</h2>
                    <p class="mt-0.5 text-xs text-slate-500">Data dasar yang digunakan untuk login dan tampilan identitas.</p>
                </div>
                <div class="flex flex-wrap items-center gap-2">
                    <span class="rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-700">
                        Role: {{ $user->roleLabel() }}
                    </span>
                    @if ($developer)
                        <span class="rounded-full bg-indigo-50 px-3 py-1 text-xs font-semibold text-indigo-700">
                            {{ $developer->role_title }}
                        </span>
                    @endif
                </div>
            </div>

            @if (session('profile_status'))
                <div role="status" class="mb-5 rounded-lg border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-900">
                    {{ session('profile_status') }}
                </div>
            @endif

            @if ($errors->hasAny(['name', 'email', 'avatar', 'avatar_bg']))
                <div role="alert" class="mb-5 rounded-lg border border-rose-300 bg-rose-50 px-4 py-3 text-sm text-rose-900">
                    <ul class="list-disc space-y-0.5 pl-4">
                        @foreach (['name', 'email', 'avatar', 'avatar_bg'] as $field)
                            @foreach ($errors->get($field) as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('account.profile.update') }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="name" class="block text-sm font-semibold text-slate-800">Nama Lengkap</label>
                        <input
                            id="name"
                            name="name"
                            type="text"
                            value="{{ old('name', $user->name) }}"
                            required
                            maxlength="150"
                            class="mt-1.5 block min-h-11 w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-base text-slate-900 focus:border-slate-900 focus:ring-2 focus:ring-slate-200 focus:outline-none sm:text-sm"
                        >
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-semibold text-slate-800">Alamat Email</label>
                        <input
                            id="email"
                            name="email"
                            type="email"
                            value="{{ old('email', $user->email) }}"
                            required
                            maxlength="150"
                            class="mt-1.5 block min-h-11 w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-base text-slate-900 focus:border-slate-900 focus:ring-2 focus:ring-slate-200 focus:outline-none sm:text-sm"
                        >
                    </div>
                </div>

                @if ($developer)
                    <div class="grid gap-5 border-t border-slate-100 pt-5 md:grid-cols-2">
                        <div>
                            <label for="avatar" class="block text-sm font-semibold text-slate-800">Inisial Avatar</label>
                            <input
                                id="avatar"
                                name="avatar"
                                type="text"
                                value="{{ old('avatar', $developer->avatar) }}"
                                maxlength="10"
                                placeholder="Contoh: RP"
                                class="mt-1.5 block min-h-11 w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-base text-slate-900 uppercase focus:border-slate-900 focus:ring-2 focus:ring-slate-200 focus:outline-none sm:text-sm"
                            >
                        </div>

                        <div>
                            <label for="avatar_bg" class="block text-sm font-semibold text-slate-800">Warna Avatar (HEX)</label>
                            <input
                                id="avatar_bg"
                                name="avatar_bg"
                                type="text"
                                value="{{ old('avatar_bg', $developer->avatar_bg) }}"
                                maxlength="50"
                                placeholder="#4F46E5"
                                class="mt-1.5 block min-h-11 w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-base text-slate-900 focus:border-slate-900 focus:ring-2 focus:ring-slate-200 focus:outline-none sm:text-sm"
                            >
                        </div>
                    </div>
                @endif

                <div class="flex justify-end pt-1">
                    <button
                        type="submit"
                        class="inline-flex min-h-11 cursor-pointer items-center justify-center rounded-lg bg-slate-950 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800"
                    >
                        Simpan Profil
                    </button>
                </div>
            </form>
        </section>

        {{-- Kartu 2: Ubah Password --}}
        <section id="account-password-app" class="rounded-xl border border-slate-200 bg-white p-6 shadow-xs sm:p-7">
            <div class="mb-5 flex flex-wrap items-center justify-between gap-3 border-b border-slate-100 pb-4">
                <div>
                    <h2 class="text-base font-bold text-slate-950">Ubah Password</h2>
                    <p class="mt-0.5 text-xs text-slate-500">Gunakan minimal 8 karakter untuk menjaga keamanan akun.</p>
                </div>
                <button
                    type="button"
                    @click="showPasswords = !showPasswords"
                    class="inline-flex min-h-9 cursor-pointer items-center gap-1.5 rounded-lg border border-slate-200 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-50"
                >
                    <span v-if="!showPasswords">Tampilkan Password</span>
                    <span v-else>Sembunyikan Password</span>
                </button>
            </div>

            @if (session('password_status'))
                <div role="status" class="mb-5 rounded-lg border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-900">
                    {{ session('password_status') }}
                </div>
            @endif

            @if ($errors->hasAny(['current_password', 'password']))
                <div role="alert" class="mb-5 rounded-lg border border-rose-300 bg-rose-50 px-4 py-3 text-sm text-rose-900">
                    <ul class="list-disc space-y-0.5 pl-4">
                        @foreach (['current_password', 'password'] as $field)
                            @foreach ($errors->get($field) as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('account.password.update') }}" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="block text-sm font-semibold text-slate-800">Password Saat Ini</label>
                    <input
                        id="current_password"
                        name="current_password"
                        :type="showPasswords ? 'text' : 'password'"
                        required
                        autocomplete="current-password"
                        class="mt-1.5 block min-h-11 w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-base text-slate-900 focus:border-slate-900 focus:ring-2 focus:ring-slate-200 focus:outline-none sm:text-sm"
                    >
                </div>

                <div class="grid gap-5 md:grid-cols-2">
                    <div>
                        <label for="new_password" class="block text-sm font-semibold text-slate-800">Password Baru</label>
                        <input
                            id="new_password"
                            name="password"
                            :type="showPasswords ? 'text' : 'password'"
                            required
                            minlength="8"
                            autocomplete="new-password"
                            class="mt-1.5 block min-h-11 w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-base text-slate-900 focus:border-slate-900 focus:ring-2 focus:ring-slate-200 focus:outline-none sm:text-sm"
                        >
                    </div>

                    <div>
                        <label for="new_password_confirmation" class="block text-sm font-semibold text-slate-800">Konfirmasi Password Baru</label>
                        <input
                            id="new_password_confirmation"
                            name="password_confirmation"
                            :type="showPasswords ? 'text' : 'password'"
                            required
                            minlength="8"
                            autocomplete="new-password"
                            class="mt-1.5 block min-h-11 w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-base text-slate-900 focus:border-slate-900 focus:ring-2 focus:ring-slate-200 focus:outline-none sm:text-sm"
                        >
                    </div>
                </div>

                <div class="flex justify-end pt-1">
                    <button
                        type="submit"
                        class="inline-flex min-h-11 cursor-pointer items-center justify-center rounded-lg bg-slate-950 px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800"
                    >
                        Perbarui Password
                    </button>
                </div>
            </form>
        </section>

        {{-- Kartu 3: Keluar dari Akun --}}
        <section class="rounded-xl border border-slate-200 bg-white p-6 shadow-xs sm:p-7">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h2 class="text-base font-bold text-slate-950">Sesi Login</h2>
                    <p class="mt-0.5 text-xs text-slate-500">Akhiri sesi Anda di perangkat ini untuk keluar dari aplikasi.</p>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button
                        type="submit"
                        class="inline-flex min-h-11 w-full cursor-pointer items-center justify-center gap-2 rounded-lg border border-rose-300 bg-rose-50 px-5 py-2.5 text-sm font-semibold text-rose-700 transition hover:bg-rose-100 sm:w-auto"
                    >
                        <svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                        </svg>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </section>
    </div>
@endsection
