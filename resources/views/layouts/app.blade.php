<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Dashboard') &mdash; {{ config('app.name', 'Aplikasi Bug Tracker') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-900 antialiased">
    @php
        $currentUser = auth()->user();
        $currentDeveloper = $currentUser?->developer;
        $userInitials = $currentDeveloper?->avatar ?: collect(preg_split('/\s+/', trim((string) $currentUser?->name)))
            ->filter()
            ->take(2)
            ->map(fn (string $p) => strtoupper(substr($p, 0, 1)))
            ->implode('');
        $avatarColor = ($currentDeveloper && str_starts_with((string) $currentDeveloper->avatar_bg, '#'))
            ? $currentDeveloper->avatar_bg
            : '#4F46E5';
    @endphp

    <div class="flex min-h-screen">
        {{-- Overlay Sidebar (Tablet Portrait & Mobile) --}}
        <div
            id="sidebar-backdrop"
            class="fixed inset-0 z-30 hidden bg-slate-950/50 lg:hidden"
            aria-hidden="true"
        ></div>

        {{-- Sidebar Kiri --}}
        <aside
            id="app-sidebar"
            class="fixed inset-y-0 left-0 z-40 flex w-68 -translate-x-full flex-col justify-between border-r border-slate-800 bg-slate-950 text-slate-100 transition-transform duration-200 ease-out lg:static lg:translate-x-0"
        >
            <div>
                {{-- Brand Header --}}
                <div class="flex h-16 items-center justify-between border-b border-slate-800 px-5">
                    <a href="{{ route('home') }}" class="flex items-center gap-3">
                        <div class="flex h-9 w-9 items-center justify-center rounded-lg border border-slate-700 bg-slate-900 text-indigo-400">
                            <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h10M4 18h16" />
                            </svg>
                        </div>
                        <div class="leading-tight">
                            <p class="text-sm font-bold tracking-tight text-white">{{ config('app.name', 'Aplikasi Bug Tracker') }}</p>
                            <p class="text-[11px] text-slate-400">IT Dev Workspace</p>
                        </div>
                    </a>
                    <button
                        type="button"
                        id="sidebar-close-btn"
                        aria-label="Tutup navigasi"
                        class="flex h-9 w-9 items-center justify-center rounded-lg text-slate-400 hover:bg-slate-800 hover:text-white lg:hidden"
                    >
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Navigasi Utama --}}
                <nav class="space-y-1.5 px-3 py-5" aria-label="Navigasi Utama">
                    <p class="px-3 pb-1 text-[11px] font-semibold tracking-wider text-slate-500 uppercase">Menu</p>

                    <a
                        href="{{ route('home') }}"
                        class="flex min-h-11 items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs('home') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}"
                    >
                        <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('home') ? 'text-indigo-400' : 'text-slate-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9M5 10v10h14V10" />
                        </svg>
                        <span>Dashboard</span>
                    </a>

                    <a
                        href="{{ route('account.edit') }}"
                        class="flex min-h-11 items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs('account.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}"
                    >
                        <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('account.*') ? 'text-indigo-400' : 'text-slate-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.5 20.118a7.5 7.5 0 0 1 15 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.5-1.632Z" />
                        </svg>
                        <span>Akun Saya</span>
                    </a>

                    @if ($currentUser && $currentUser->isAdmin())
                        <div class="pt-4">
                            <p class="px-3 pb-1 text-[11px] font-semibold tracking-wider text-slate-500 uppercase">Administrasi</p>
                            <a
                                href="{{ route('admin.accounts.index') }}"
                                class="mt-1 flex min-h-11 items-center gap-3 rounded-lg px-3 py-2.5 text-sm font-medium transition {{ request()->routeIs('admin.accounts.*') ? 'bg-slate-800 text-white' : 'text-slate-300 hover:bg-slate-900 hover:text-white' }}"
                            >
                                <svg class="h-5 w-5 shrink-0 {{ request()->routeIs('admin.accounts.*') ? 'text-indigo-400' : 'text-slate-400' }}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                                </svg>
                                <span>Manajemen Akun</span>
                            </a>
                        </div>
                    @endif
                </nav>
            </div>

            {{-- Info Pemilik Akun (menuju Akun Saya) --}}
            <div class="border-t border-slate-800 p-4">
                <a
                    href="{{ route('account.edit') }}"
                    class="flex items-center gap-3 rounded-lg p-1.5 transition hover:bg-slate-900"
                >
                    @if ($currentUser->foto_profil_url)
                        <img
                            src="{{ $currentUser->foto_profil_url }}"
                            alt="Foto {{ $currentUser->name }}"
                            class="h-9 w-9 shrink-0 rounded-lg border border-slate-700 object-cover"
                        >
                    @else
                        <span
                            class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg text-xs font-bold text-white"
                            style="background-color: {{ $avatarColor }}"
                        >
                            {{ $userInitials ?: 'US' }}
                        </span>
                    @endif
                    <div class="min-w-0 flex-1">
                        <p class="truncate text-sm font-semibold text-white">{{ $currentUser->name }}</p>
                        <p class="truncate text-xs text-slate-400">{{ $currentUser->roleLabel() }}</p>
                    </div>
                </a>
            </div>
        </aside>

        {{-- Area Konten Utama --}}
        <div class="flex min-w-0 flex-1 flex-col">
            {{-- Topbar Ringkas untuk Tablet & Mobile --}}
            <header class="sticky top-0 z-20 flex h-16 items-center justify-between border-b border-slate-200 bg-white px-4 sm:px-6 lg:hidden">
                <button
                    type="button"
                    id="sidebar-open-btn"
                    aria-label="Buka navigasi"
                    class="flex h-11 w-11 items-center justify-center rounded-lg border border-slate-200 text-slate-700 hover:bg-slate-100"
                >
                    <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
                <span class="text-sm font-bold text-slate-900">@yield('title', 'Dashboard')</span>
                <a
                    href="{{ route('account.edit') }}"
                    class="flex h-9 w-9 items-center justify-center overflow-hidden rounded-lg text-xs font-bold text-white"
                    style="background-color: {{ $avatarColor }}"
                    aria-label="Akun Saya"
                >
                    @if ($currentUser->foto_profil_url)
                        <img src="{{ $currentUser->foto_profil_url }}" alt="Foto {{ $currentUser->name }}" class="h-full w-full object-cover">
                    @else
                        {{ $userInitials ?: 'US' }}
                    @endif
                </a>
            </header>

            <main class="flex-1 px-4 py-6 sm:px-6 lg:px-10 lg:py-8">
                <div class="mx-auto max-w-5xl">
                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    @include('partials.notification-popup')
    @include('partials.confirm-modal')

    <script>
        (() => {
            const sidebar = document.getElementById('app-sidebar');
            const backdrop = document.getElementById('sidebar-backdrop');
            const openBtn = document.getElementById('sidebar-open-btn');
            const closeBtn = document.getElementById('sidebar-close-btn');

            function openSidebar() {
                if (!sidebar || !backdrop) return;
                sidebar.classList.remove('-translate-x-full');
                backdrop.classList.remove('hidden');
            }

            function closeSidebar() {
                if (!sidebar || !backdrop) return;
                sidebar.classList.add('-translate-x-full');
                backdrop.classList.add('hidden');
            }

            openBtn?.addEventListener('click', openSidebar);
            closeBtn?.addEventListener('click', closeSidebar);
            backdrop?.addEventListener('click', closeSidebar);
        })();
    </script>
    @stack('scripts')
</body>
</html>
