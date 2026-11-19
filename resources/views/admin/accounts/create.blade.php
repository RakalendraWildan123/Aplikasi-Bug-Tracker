@extends('layouts.app')

@section('title', 'Tambah Akun')

@section('content')
    <div class="mx-auto max-w-2xl space-y-6">
        <header class="flex items-center justify-between border-b border-slate-200 pb-5">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-slate-950">Tambah Akun Baru</h1>
                <p class="mt-1 text-sm text-slate-600">Jika memilih role Developer, profil pada tabel <code>developers</code> akan dibuat otomatis.</p>
            </div>
            <a href="{{ route('admin.accounts.index') }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700">&larr; Kembali</a>
        </header>

        <form method="POST" action="{{ route('admin.accounts.store') }}" enctype="multipart/form-data" class="space-y-5 rounded-xl border border-slate-200 bg-white p-6 shadow-xs">
            @csrf

            <div class="flex flex-col gap-4 rounded-xl border border-slate-200 bg-slate-50/70 p-4 sm:flex-row sm:items-center">
                <div class="shrink-0">
                    <img
                        id="create-photo-preview"
                        src=""
                        alt="Preview Foto Profil"
                        class="hidden h-14 w-14 rounded-xl border border-slate-200 object-cover"
                    >
                    <div
                        id="create-photo-fallback"
                        class="flex h-14 w-14 items-center justify-center rounded-xl bg-slate-200 text-xs font-bold text-slate-600"
                    >
                        FOTO
                    </div>
                </div>
                <div class="min-w-0 flex-1 space-y-1.5">
                    <label for="foto_profil" class="block text-sm font-semibold text-slate-800">Foto Profil (opsional)</label>
                    <input
                        id="foto_profil"
                        name="foto_profil"
                        type="file"
                        accept="image/jpeg,image/png,image/webp"
                        data-photo-input
                        data-preview-img="create-photo-preview"
                        data-preview-fallback="create-photo-fallback"
                        class="block w-full text-sm text-slate-600 file:mr-3 file:cursor-pointer file:rounded-lg file:border file:border-slate-300 file:bg-white file:px-3.5 file:py-2 file:text-xs file:font-semibold file:text-slate-800 hover:file:bg-slate-100"
                    >
                    <p class="text-xs text-slate-500">JPG, PNG, atau WEBP maksimal 2 MB.</p>
                </div>
            </div>

            <div>
                <label for="name" class="block text-sm font-semibold text-slate-800">Nama Lengkap</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required maxlength="150" class="mt-1.5 block min-h-11 w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm">
            </div>

            <div>
                <label for="email" class="block text-sm font-semibold text-slate-800">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required maxlength="150" class="mt-1.5 block min-h-11 w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm">
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-800">Password</label>
                    <input id="password" name="password" type="password" required minlength="8" class="mt-1.5 block min-h-11 w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-800">Konfirmasi Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required minlength="8" class="mt-1.5 block min-h-11 w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm">
                </div>
            </div>

            <div>
                <label for="role" class="block text-sm font-semibold text-slate-800">Role Akses</label>
                <select id="role" name="role" class="mt-1.5 block min-h-11 w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm">
                    @foreach ($roleLabels as $roleValue => $roleText)
                        <option value="{{ $roleValue }}" @selected(old('role', 'developer') === $roleValue)>{{ $roleText }}</option>
                    @endforeach
                </select>
            </div>

            <div id="developer-fields" class="space-y-4 rounded-lg border border-slate-200 bg-slate-50 p-4">
                <p class="text-xs font-semibold tracking-wider text-slate-500 uppercase">Profil Developer</p>
                <div>
                    <label for="role_title" class="block text-sm font-semibold text-slate-800">Posisi Developer</label>
                    <select id="role_title" name="role_title" class="mt-1.5 block min-h-11 w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm">
                        @foreach ($developerRoles as $devRole)
                            <option value="{{ $devRole }}" @selected(old('role_title') === $devRole)>{{ $devRole }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="avatar" class="block text-sm font-semibold text-slate-800">Inisial Avatar (opsional)</label>
                        <input id="avatar" name="avatar" type="text" value="{{ old('avatar') }}" maxlength="10" placeholder="Otomatis dari nama" class="mt-1.5 block min-h-11 w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm">
                    </div>
                    <div>
                        <label for="avatar_bg" class="block text-sm font-semibold text-slate-800">Warna Avatar (HEX)</label>
                        <input id="avatar_bg" name="avatar_bg" type="text" value="{{ old('avatar_bg', '#4F46E5') }}" maxlength="50" class="mt-1.5 block min-h-11 w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2.5 text-sm">
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('admin.accounts.index') }}" class="inline-flex min-h-11 items-center rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">Batal</a>
                <button type="submit" class="min-h-11 cursor-pointer rounded-lg bg-slate-950 px-5 py-2 text-sm font-semibold text-white hover:bg-slate-800">Simpan Akun</button>
            </div>
        </form>
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
