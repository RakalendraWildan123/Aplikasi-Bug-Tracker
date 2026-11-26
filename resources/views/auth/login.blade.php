<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login &mdash; {{ config('app.name', 'Aplikasi Bug Tracker') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-gradient-to-br from-slate-950 via-slate-900 to-indigo-950 flex flex-col items-center justify-center p-4 sm:p-6 lg:p-8 text-slate-900 antialiased relative overflow-hidden">

    {{-- Efek Ambient Glow Latar Belakang --}}
    <div class="absolute -top-32 -left-32 w-80 h-80 sm:w-96 sm:h-96 bg-indigo-600/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-32 -right-32 w-80 h-80 sm:w-96 sm:h-96 bg-violet-600/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-full h-full bg-[radial-gradient(#312e81_1px,transparent_1px)] [background-size:24px_24px] opacity-25 pointer-events-none"></div>

    {{-- Kartu Utama Login --}}
    <div class="relative z-10 w-full max-w-sm sm:max-w-md bg-white/95 backdrop-blur-md border border-slate-200/80 rounded-2xl shadow-2xl p-6 sm:p-8">
        {{-- Header Form & Branding --}}
        <div class="mb-6 text-center">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-gradient-to-tr from-indigo-600 to-violet-600 text-white shadow-lg shadow-indigo-600/30 mb-3.5">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.25 9.75L16.5 12l-2.25 2.25m-4.5 0L7.5 12l2.25-2.25M6 20.25h12A2.25 2.25 0 0020.25 18V6A2.25 2.25 0 0018 3.75H6A2.25 2.25 0 003.75 6v12A2.25 2.25 0 006 20.25z"/>
                </svg>
            </div>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight text-slate-900">Aplikasi Bug Tracker</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-1 font-medium">Monitoring & Manajemen IT Developer</p>
        </div>

        {{-- Status Notifikasi --}}
        @if (session('status'))
            <div role="status" class="mb-5 rounded-xl border border-emerald-200 bg-emerald-50/90 px-3.5 py-2.5 text-xs sm:text-sm text-emerald-800 flex items-center gap-2">
                <svg class="w-4 h-4 text-emerald-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <span>{{ session('status') }}</span>
            </div>
        @endif

        {{-- Pesan Error Validasi / Otentikasi --}}
        @if ($errors->any())
            <div role="alert" class="mb-5 rounded-xl border border-rose-200 bg-rose-50/90 px-3.5 py-2.5 text-xs sm:text-sm text-rose-800 flex items-center gap-2">
                <svg class="w-4 h-4 text-rose-600 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/>
                </svg>
                <span>{{ $errors->first() }}</span>
            </div>
        @endif

        {{-- Form Login --}}
        <form method="POST" action="{{ route('login.attempt') }}" class="space-y-4" autocomplete="off">
            @csrf

            {{-- Input Email --}}
            <div>
                <label for="email" class="block text-xs sm:text-sm font-semibold text-slate-800 mb-1.5">Alamat Email</label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75"/>
                        </svg>
                    </div>
                    <input
                        id="email"
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        autofocus
                        autocomplete="email"
                        placeholder="nama@bugtracker.test"
                        class="block min-h-11 w-full rounded-xl border pl-10 pr-3.5 py-2 text-sm text-slate-900 placeholder:text-slate-400 transition focus:outline-none {{ $errors->has('email') ? 'border-rose-400 focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20' : 'border-slate-300 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20' }}"
                    >
                </div>
            </div>

            {{-- Input Password --}}
            <div>
                <label for="password" class="block text-xs sm:text-sm font-semibold text-slate-800 mb-1.5">Kata Sandi</label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z"/>
                        </svg>
                    </div>
                    <input
                        id="password"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                        class="block min-h-11 w-full rounded-xl border pl-10 pr-11 py-2 text-sm text-slate-900 placeholder:text-slate-400 transition focus:outline-none {{ $errors->has('password') ? 'border-rose-400 focus:border-rose-500 focus:ring-2 focus:ring-rose-500/20' : 'border-slate-300 focus:border-indigo-600 focus:ring-2 focus:ring-indigo-600/20' }}"
                    >
                    <button
                        type="button"
                        id="toggle-password"
                        aria-label="Tampilkan password"
                        class="absolute inset-y-0 right-0 flex w-11 items-center justify-center text-slate-400 transition hover:text-slate-700 focus:outline-none cursor-pointer"
                    >
                        <svg class="h-4 w-4 eye-open" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <svg class="h-4 w-4 eye-closed hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" />
                        </svg>
                    </button>
                </div>
            </div>

            {{-- Ingat Saya --}}
            <div class="flex items-center justify-between pt-0.5">
                <label class="inline-flex cursor-pointer items-center gap-2 text-xs sm:text-sm text-slate-600 select-none">
                    <input
                        type="checkbox"
                        name="remember"
                        value="1"
                        {{ old('remember') ? 'checked' : '' }}
                        class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-600"
                    >
                    <span>Ingat sesi saya</span>
                </label>
            </div>

            {{-- Tombol Masuk --}}
            <button
                type="submit"
                class="flex min-h-11 w-full cursor-pointer items-center justify-center rounded-xl bg-gradient-to-r from-indigo-600 via-indigo-700 to-violet-700 px-4 py-2.5 text-sm font-semibold text-white shadow-md shadow-indigo-600/25 transition hover:from-indigo-500 hover:to-violet-600 focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 focus:outline-none active:scale-[0.99]"
            >
                <span>Masuk ke Dashboard</span>
                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3"/>
                </svg>
            </button>
        </form>

        {{-- Panel Akun Demo Sidang PKL (Klik Otomatis Isi) --}}
        <div class="mt-6 pt-5 border-t border-slate-100">
            <div class="flex items-center justify-between mb-2.5">
                <span class="text-xs font-bold text-slate-800 flex items-center gap-1.5">
                    <span class="inline-block w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <span>Akun Demo Sidang PKL</span>
                </span>
                <span class="text-[11px] text-slate-400 font-medium">Klik untuk isi form</span>
            </div>
            <div class="grid grid-cols-2 gap-2 text-left">
                <button
                    type="button"
                    onclick="fillCredentials('admin@bugtracker.test', 'password')"
                    class="p-2.5 rounded-xl border border-slate-200 bg-slate-50/80 hover:bg-indigo-50/80 hover:border-indigo-300 transition text-left cursor-pointer group"
                    title="Klik untuk gunakan akun Administrator"
                >
                    <div class="text-[11px] font-bold text-slate-800 group-hover:text-indigo-700 flex items-center gap-1">
                        <span>👑</span>
                        <span>Administrator</span>
                    </div>
                    <div class="text-[10px] text-slate-500 truncate mt-0.5">admin@bugtracker.test</div>
                </button>
                <button
                    type="button"
                    onclick="fillCredentials('raka.pratama@bugtracker.test', 'password')"
                    class="p-2.5 rounded-xl border border-slate-200 bg-slate-50/80 hover:bg-indigo-50/80 hover:border-indigo-300 transition text-left cursor-pointer group"
                    title="Klik untuk gunakan akun Lead Developer"
                >
                    <div class="text-[11px] font-bold text-slate-800 group-hover:text-indigo-700 flex items-center gap-1">
                        <span>💻</span>
                        <span>Lead Developer</span>
                    </div>
                    <div class="text-[10px] text-slate-500 truncate mt-0.5">raka.pratama@bugtracker.test</div>
                </button>
            </div>
        </div>
    </div>

    {{-- Footer Info --}}
    <footer class="relative z-10 mt-6 text-center text-xs text-slate-400 font-medium">
        &copy; 2026 Tim Pengembang IT Developer &bull; Sistem Monitoring &amp; Pelacak Bug
    </footer>

    <script>
        // Fungsi klik otomatis mengisi kredensial demo
        function fillCredentials(email, password) {
            const emailInput = document.getElementById('email');
            const passwordInput = document.getElementById('password');
            if (emailInput && passwordInput) {
                emailInput.value = email;
                passwordInput.value = password;
                emailInput.classList.add('ring-2', 'ring-indigo-500/40');
                passwordInput.classList.add('ring-2', 'ring-indigo-500/40');
                setTimeout(() => {
                    emailInput.classList.remove('ring-2', 'ring-indigo-500/40');
                    passwordInput.classList.remove('ring-2', 'ring-indigo-500/40');
                }, 600);
            }
        }

        // Bersihkan sisa input saat halaman login dimuat atau dikembalikan via tombol Back browser
        window.addEventListener('pageshow', function (event) {
            const hasError = document.querySelector('[role="alert"]');
            const passwordInput = document.getElementById('password');
            if (passwordInput && !hasError) {
                passwordInput.value = '';
            }
            if (!hasError) {
                const emailInput = document.getElementById('email');
                if (emailInput) {
                    emailInput.value = '';
                }
                const form = document.querySelector('form');
                if (form) {
                    form.reset();
                }
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            const toggleBtn = document.getElementById('toggle-password');
            const passwordInput = document.getElementById('password');
            if (toggleBtn && passwordInput) {
                toggleBtn.addEventListener('click', () => {
                    const isPassword = passwordInput.type === 'password';
                    passwordInput.type = isPassword ? 'text' : 'password';
                    toggleBtn.setAttribute('aria-label', isPassword ? 'Sembunyikan password' : 'Tampilkan password');
                    const eyeOpen = toggleBtn.querySelector('.eye-open');
                    const eyeClosed = toggleBtn.querySelector('.eye-closed');
                    if (eyeOpen && eyeClosed) {
                        eyeOpen.classList.toggle('hidden', isPassword);
                        eyeClosed.classList.toggle('hidden', !isPassword);
                    }
                });
            }
        });
    </script>
</body>
</html>
