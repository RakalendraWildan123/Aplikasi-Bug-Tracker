<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Akun &mdash; {{ config('app.name', 'Aplikasi Bug Tracker') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    <div class="mx-auto max-w-2xl px-6 py-10">
        <div class="mb-6 flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Tambah Akun Baru</h1>
                <p class="mt-1 text-sm text-slate-600">Jika memilih role Developer, profil pada tabel <code>developers</code> akan dibuat otomatis.</p>
            </div>
            <a href="{{ route('admin.accounts.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">&larr; Kembali</a>
        </div>

        @if ($errors->any())
            <div class="mb-4 rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                <ul class="list-disc space-y-1 pl-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.accounts.store') }}" class="space-y-5 rounded-xl border border-slate-200 bg-white p-6 shadow-xs">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-slate-700">Nama Lengkap</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required maxlength="150" class="mt-1 block w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email') }}" required maxlength="150" class="mt-1 block w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm">
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-700">Password</label>
                    <input id="password" name="password" type="password" required minlength="8" class="mt-1 block w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-700">Konfirmasi Password</label>
                    <input id="password_confirmation" name="password_confirmation" type="password" required minlength="8" class="mt-1 block w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm">
                </div>
            </div>

            <div>
                <label for="role" class="block text-sm font-medium text-slate-700">Role Akses</label>
                <select id="role" name="role" class="mt-1 block w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm">
                    @foreach ($roleLabels as $roleValue => $roleText)
                        <option value="{{ $roleValue }}" @selected(old('role', 'developer') === $roleValue)>{{ $roleText }}</option>
                    @endforeach
                </select>
            </div>

            <div id="developer-fields" class="space-y-4 rounded-lg border border-slate-200 bg-slate-50 p-4">
                <p class="text-xs font-semibold tracking-wider text-slate-500 uppercase">Profil Developer</p>
                <div>
                    <label for="role_title" class="block text-sm font-medium text-slate-700">Posisi Developer</label>
                    <select id="role_title" name="role_title" class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-sm">
                        @foreach ($developerRoles as $devRole)
                            <option value="{{ $devRole }}" @selected(old('role_title') === $devRole)>{{ $devRole }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="avatar" class="block text-sm font-medium text-slate-700">Inisial Avatar (opsional)</label>
                        <input id="avatar" name="avatar" type="text" value="{{ old('avatar') }}" maxlength="10" placeholder="Otomatis dari nama" class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-sm">
                    </div>
                    <div>
                        <label for="avatar_bg" class="block text-sm font-medium text-slate-700">Warna Avatar (HEX)</label>
                        <input id="avatar_bg" name="avatar_bg" type="text" value="{{ old('avatar_bg', '#4F46E5') }}" maxlength="50" class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-sm">
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('admin.accounts.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">Batal</a>
                <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">Simpan Akun</button>
            </div>
        </form>
    </div>

    <script>
        const roleSelect = document.getElementById('role');
        const devFields = document.getElementById('developer-fields');
        function toggleDevFields() {
            devFields.style.display = roleSelect.value === 'developer' ? 'block' : 'none';
        }
        roleSelect.addEventListener('change', toggleDevFields);
        toggleDevFields();
    </script>
</body>
</html>
