@extends('layouts.app')

@section('title', 'Manajemen Akun')

@section('content')
    <div class="space-y-6">
        <header class="flex flex-col gap-4 border-b border-slate-200 pb-5 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-semibold tracking-wider text-indigo-600 uppercase">Administrasi</p>
                <h1 class="mt-1 text-2xl font-bold tracking-tight text-slate-950">Manajemen Akun</h1>
                <p class="mt-1 text-sm text-slate-600">Atur akun login Atasan (Admin) dan tim Developer yang terhubung ke sistem.</p>
            </div>
            <a href="{{ route('admin.accounts.create') }}" class="inline-flex min-h-11 items-center justify-center rounded-lg bg-slate-950 px-4 py-2.5 text-sm font-semibold text-white transition hover:bg-slate-800">
                + Tambah Akun
            </a>
        </header>

        @if (session('status'))
            <div role="status" class="rounded-lg border border-emerald-300 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-900">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div role="alert" class="rounded-lg border border-rose-300 bg-rose-50 px-4 py-3 text-sm text-rose-900">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="GET" action="{{ route('admin.accounts.index') }}" class="flex flex-wrap items-end gap-3 rounded-xl border border-slate-200 bg-white p-4 shadow-xs">
            <div>
                <label for="role" class="block text-xs font-semibold text-slate-600">Filter Role</label>
                <select id="role" name="role" class="mt-1 min-h-10 rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    <option value="">Semua Role</option>
                    @foreach ($roleLabels as $roleValue => $roleText)
                        <option value="{{ $roleValue }}" @selected($roleFilter === $roleValue)>{{ $roleText }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label for="status" class="block text-xs font-semibold text-slate-600">Filter Status</label>
                <select id="status" name="status" class="mt-1 min-h-10 rounded-lg border border-slate-300 px-3 py-2 text-sm">
                    <option value="">Semua Status</option>
                    <option value="active" @selected($statusFilter === 'active')>Aktif</option>
                    <option value="inactive" @selected($statusFilter === 'inactive')>Non-Aktif</option>
                </select>
            </div>
            <button type="submit" class="min-h-10 cursor-pointer rounded-lg bg-slate-900 px-4 py-2 text-sm font-medium text-white hover:bg-slate-800">Terapkan</button>
            @if ($roleFilter !== '' || $statusFilter !== '')
                <a href="{{ route('admin.accounts.index') }}" class="inline-flex min-h-10 items-center rounded-lg border border-slate-300 px-3 py-2 text-sm text-slate-600 hover:bg-slate-100">Reset</a>
            @endif
        </form>

        <div class="overflow-x-auto rounded-xl border border-slate-200 bg-white shadow-xs">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead class="bg-slate-50 text-left text-xs font-semibold tracking-wider text-slate-500 uppercase">
                    <tr>
                        <th class="px-4 py-3">Nama &amp; Email</th>
                        <th class="px-4 py-3">Role Akses</th>
                        <th class="px-4 py-3">Profil Developer</th>
                        <th class="px-4 py-3">Status</th>
                        <th class="px-4 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($accounts as $account)
                        <tr class="hover:bg-slate-50/70">
                            <td class="px-4 py-3">
                                <div class="font-semibold text-slate-900">{{ $account->name }}</div>
                                <div class="text-xs text-slate-500">{{ $account->email }}</div>
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $account->isAdmin() ? 'bg-indigo-100 text-indigo-800' : 'bg-sky-100 text-sky-800' }}">
                                    {{ $account->roleLabel() }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @if ($account->developer)
                                    <div class="flex items-center gap-2">
                                        <span class="inline-flex h-7 w-7 items-center justify-center rounded-full text-xs font-bold text-white" style="background-color: {{ str_starts_with($account->developer->avatar_bg, '#') ? $account->developer->avatar_bg : '#4F46E5' }}">
                                            {{ $account->developer->avatar }}
                                        </span>
                                        <span class="text-slate-700">{{ $account->developer->role_title }}</span>
                                    </div>
                                @else
                                    <span class="text-xs text-slate-400">Tidak terhubung</span>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span class="inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium {{ $account->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ $account->is_active ? 'Aktif' : 'Non-Aktif' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-2">
                                    <a href="{{ route('admin.accounts.edit', $account) }}" class="rounded-md border border-slate-300 px-3 py-1.5 text-xs font-medium text-slate-700 hover:bg-slate-100">
                                        Edit
                                    </a>
                                    @if (auth()->id() !== $account->id)
                                        <form method="POST" action="{{ route('admin.accounts.toggle-active', $account) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="cursor-pointer rounded-md border px-3 py-1.5 text-xs font-medium {{ $account->is_active ? 'border-amber-200 text-amber-800 hover:bg-amber-50' : 'border-emerald-200 text-emerald-700 hover:bg-emerald-50' }}">
                                                {{ $account->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                            </button>
                                        </form>
                                        <form
                                            method="POST"
                                            action="{{ route('admin.accounts.destroy', $account) }}"
                                            onsubmit="return confirm('Yakin ingin menghapus akun {{ addslashes($account->name) }}?');"
                                        >
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="cursor-pointer rounded-md border border-rose-200 bg-rose-50 px-3 py-1.5 text-xs font-medium text-rose-700 hover:bg-rose-100">
                                                Hapus
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-sm text-slate-500">Belum ada akun yang sesuai filter.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
