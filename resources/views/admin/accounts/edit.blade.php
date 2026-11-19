@extends('layouts.app')

@section('title', 'Edit Akun')

@section('content')
    <div class="mx-auto max-w-2xl space-y-6">
        <header class="flex items-center justify-between border-b border-slate-200 pb-5">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-950">Edit Akun: {{ $account->name }}</h1>
                <p class="mt-1 text-sm text-slate-600">Perubahan nama, email, dan status aktif otomatis disinkronkan ke profil developer.</p>
            </div>
            <a href="{{ route('admin.accounts.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700">&larr; Kembali</a>
        </header>

        <form method="POST" action="{{ route('admin.accounts.update', $account) }}" enctype="multipart/form-data" class="space-y-5 rounded-xl border border-slate-200 bg-white p-6 shadow-xs">
            @csrf
            @method('PUT')

            <div class="flex flex-col gap-4 rounded-xl border border-slate-200 bg-slate-50/70 p-4 sm:flex-row sm:items-center">
                <div class="shrink-0">
                    <img
                        id="edit-photo-preview"
                        src="{{ $account->foto_profil_url ?? '' }}"
                        alt="Foto Profil {{ $account->name }}"
                        class="{{ $account->foto_profil_url ? '' : 'hidden' }} h-14 w-14 rounded-xl border border-slate-200 object-cover"
                    >
                    <div
                        id="edit-photo-fallback"
                        class="{{ $account->foto_profil_url ? 'hidden' : 'flex' }} h-14 w-14 items-center justify-center rounded-xl bg-slate-200 text-xs font-bold text-slate-600"
                    >
                        {{ $account->developer?->avatar ?: 'FOTO' }}
                    </div>
                </div>
                <div class="min-w-0 flex-1 space-y-1.5">
                    <label for="foto_profil" class="block text-sm font-semibold text-slate-800">Foto Profil</label>
                    <input
                        id="foto_profil"
                        name="foto_profil"
                        type="file"
                        accept="image/jpeg,image/png,image/webp"
                        data-photo-input
                        data-preview-img="edit-photo-preview"
                        data-preview-fallback="edit-photo-fallback"
                        class="block w-full text-sm text-slate-600 file:mr-3 file:cursor-pointer file:rounded-lg file:border file:border-slate-300 file:bg-white file:px-3.5 file:py-2 file:text-xs file:font-semibold file:text-slate-800 hover:file:bg-slate-100"
                    >
                    <p class="text-xs text-slate-500">JPG, PNG, atau WEBP maksimal 2 MB.</p>
                    @if ($account->foto_profil)
                        <label class="inline-flex cursor-pointer items-center gap-2 pt-1 text-xs font-medium text-rose-700">
                            <input type="checkbox" name="hapus_foto_profil" value="1" class="rounded border-rose-300 text-rose-600 focus:ring-rose-500">
                            Hapus foto profil saat ini
                        </label>
                    @endif
                </div>
            </div>

            <div>
                <label for="name" class="block text-sm font-semibold text-slate-800">Nama Lengkap</label>
                <input id="name" name="name" type="text" value="{{ old('name', $account->name) }}" required maxlength="150" class="mt-1.5 block min-h-11 w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm">
            </div>

            <div>
                <label for="email" class="block text-sm font-semibold text-slate-800">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $account->email) }}" required maxlength="150" class="mt-1.5 block min-h-11 w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm">
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="role" class="block text-sm font-semibold text-slate-800">Role Akses</label>
                    <select id="role" name="role" class="mt-1.5 block min-h-11 w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm">
                        @foreach ($roleLabels as $roleValue => $roleText)
                            <option value="{{ $roleValue }}" @selected(old('role', $account->role) === $roleValue)>{{ $roleText }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="is_active" class="block text-sm font-semibold text-slate-800">Status Akun</label>
                    <select id="is_active" name="is_active" class="mt-1.5 block min-h-11 w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm">
                        <option value="1" @selected((string) old('is_active', (int) $account->is_active) === '1')>Aktif</option>
                        <option value="0" @selected((string) old('is_active', (int) $account->is_active) === '0')>Non-Aktif</option>
                    </select>
                </div>
            </div>

            <div id="developer-fields" class="space-y-4 rounded-lg border border-slate-200 bg-slate-50 p-4">
                <p class="text-xs font-semibold tracking-wider text-slate-500 uppercase">Profil Developer</p>
                <div>
                    <label for="role_title" class="block text-sm font-semibold text-slate-800">Posisi Developer</label>
                    <select id="role_title" name="role_title" class="mt-1.5 block min-h-11 w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm">
                        @foreach ($developerRoles as $devRole)
                            <option value="{{ $devRole }}" @selected(old('role_title', $account->developer?->role_title) === $devRole)>{{ $devRole }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="avatar" class="block text-sm font-semibold text-slate-800">Inisial Avatar</label>
                        <input id="avatar" name="avatar" type="text" value="{{ old('avatar', $account->developer?->avatar) }}" maxlength="10" class="mt-1.5 block min-h-11 w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm">
                    </div>
                    <div>
                        <label for="avatar_bg" class="block text-sm font-semibold text-slate-800">Warna Avatar</label>
                        <input id="avatar_bg" name="avatar_bg" type="text" value="{{ old('avatar_bg', $account->developer?->avatar_bg ?? '#4F46E5') }}" maxlength="50" class="mt-1.5 block min-h-11 w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm">
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('admin.accounts.index') }}" class="inline-flex min-h-11 items-center rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">Batal</a>
                <button type="submit" class="min-h-11 cursor-pointer rounded-lg bg-slate-950 px-5 py-2 text-sm font-semibold text-white hover:bg-slate-800">Simpan Perubahan</button>
            </div>
        </form>

        <form method="POST" action="{{ route('admin.accounts.reset-password', $account) }}" class="space-y-4 rounded-xl border border-slate-200 bg-white p-6 shadow-xs">
            @csrf
            @method('PATCH')
            <h2 class="text-base font-bold text-slate-950">Reset Password Akun</h2>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="reset_password" class="block text-sm font-semibold text-slate-800">Password Baru</label>
                    <input id="reset_password" name="password" type="password" required minlength="8" class="mt-1.5 block min-h-11 w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm">
                </div>
                <div>
                    <label for="reset_password_confirmation" class="block text-sm font-semibold text-slate-800">Konfirmasi Password Baru</label>
                    <input id="reset_password_confirmation" name="password_confirmation" type="password" required minlength="8" class="mt-1.5 block min-h-11 w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm">
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="min-h-11 cursor-pointer rounded-lg bg-slate-900 px-5 py-2 text-sm font-semibold text-white hover:bg-slate-800">Reset Password</button>
            </div>
        </form>

        @if (auth()->id() !== $account->id)
            <section class="rounded-xl border border-rose-200 bg-rose-50/50 p-6 shadow-xs">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-base font-bold text-rose-900">Hapus Akun</h2>
                        <p class="mt-0.5 text-xs text-rose-700">Menghapus akun akan mencabut akses login secara permanen.</p>
                    </div>
                    <form
                        method="POST"
                        action="{{ route('admin.accounts.destroy', $account) }}"
                        data-confirm-title="Hapus Akun Secara Permanen?"
                        data-confirm-message="Akun {{ $account->name }} akan dihapus dan akses loginnya dicabut secara permanen."
                        data-confirm-label="Ya, Hapus Akun"
                        data-confirm-variant="danger"
                    >
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="min-h-11 cursor-pointer rounded-lg bg-rose-600 px-5 py-2 text-sm font-semibold text-white hover:bg-rose-700">
                            Hapus Akun
                        </button>
                    </form>
                </div>
            </section>
        @endif
    </div>
@endsection

@push('scripts')
    <script>
        const roleSelect = document.getElementById('role');
        const devFields = document.getElementById('developer-fields');
        function toggleDevFields() {
            devFields.style.display = roleSelect.value === 'developer' ? 'block' : 'none';
        }
        roleSelect.addEventListener('change', toggleDevFields);
        toggleDevFields();
    </script>
@endpush
