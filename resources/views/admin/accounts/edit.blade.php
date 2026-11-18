<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Akun &mdash; {{ config('app.name', 'Aplikasi Bug Tracker') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 antialiased">
    <div class="mx-auto max-w-2xl space-y-6 px-6 py-10">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-900">Edit Akun: {{ $account->name }}</h1>
                <p class="mt-1 text-sm text-slate-600">Perubahan nama, email, dan status aktif otomatis disinkronkan ke profil developer.</p>
            </div>
            <a href="{{ route('admin.accounts.index') }}" class="text-sm font-medium text-indigo-600 hover:text-indigo-700">&larr; Kembali</a>
        </div>

        @if (session('status'))
            <div class="rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-lg border border-rose-200 bg-rose-50 px-4 py-3 text-sm text-rose-800">
                <ul class="list-disc space-y-1 pl-4">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('admin.accounts.update', $account) }}" class="space-y-5 rounded-xl border border-slate-200 bg-white p-6 shadow-xs">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-medium text-slate-700">Nama Lengkap</label>
                <input id="name" name="name" type="text" value="{{ old('name', $account->name) }}" required maxlength="150" class="mt-1 block w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm">
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-slate-700">Email</label>
                <input id="email" name="email" type="email" value="{{ old('email', $account->email) }}" required maxlength="150" class="mt-1 block w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm">
            </div>

            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="role" class="block text-sm font-medium text-slate-700">Role Akses</label>
                    <select id="role" name="role" class="mt-1 block w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm">
                        @foreach ($roleLabels as $roleValue => $roleText)
                            <option value="{{ $roleValue }}" @selected(old('role', $account->role) === $roleValue)>{{ $roleText }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label for="is_active" class="block text-sm font-medium text-slate-700">Status Akun</label>
                    <select id="is_active" name="is_active" class="mt-1 block w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm">
                        <option value="1" @selected((string) old('is_active', (int) $account->is_active) === '1')>Aktif</option>
                        <option value="0" @selected((string) old('is_active', (int) $account->is_active) === '0')>Non-Aktif</option>
                    </select>
                </div>
            </div>

            <div id="developer-fields" class="space-y-4 rounded-lg border border-slate-200 bg-slate-50 p-4">
                <p class="text-xs font-semibold tracking-wider text-slate-500 uppercase">Profil Developer</p>
                <div>
                    <label for="role_title" class="block text-sm font-medium text-slate-700">Posisi Developer</label>
                    <select id="role_title" name="role_title" class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-sm">
                        @foreach ($developerRoles as $devRole)
                            <option value="{{ $devRole }}" @selected(old('role_title', $account->developer?->role_title) === $devRole)>{{ $devRole }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="avatar" class="block text-sm font-medium text-slate-700">Inisial Avatar</label>
                        <input id="avatar" name="avatar" type="text" value="{{ old('avatar', $account->developer?->avatar) }}" maxlength="10" class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-sm">
                    </div>
                    <div>
                        <label for="avatar_bg" class="block text-sm font-medium text-slate-700">Warna Avatar</label>
                        <input id="avatar_bg" name="avatar_bg" type="text" value="{{ old('avatar_bg', $account->developer?->avatar_bg ?? '#4F46E5') }}" maxlength="50" class="mt-1 block w-full rounded-lg border border-slate-300 bg-white px-3.5 py-2 text-sm">
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <a href="{{ route('admin.accounts.index') }}" class="rounded-lg border border-slate-300 px-4 py-2 text-sm font-medium text-slate-700 hover:bg-slate-100">Batal</a>
                <button type="submit" class="rounded-lg bg-indigo-600 px-4 py-2 text-sm font-semibold text-white hover:bg-indigo-700">Simpan Perubahan</button>
            </div>
        </form>

        <form method="POST" action="{{ route('admin.accounts.reset-password', $account) }}" class="space-y-4 rounded-xl border border-slate-200 bg-white p-6 shadow-xs">
            @csrf
            @method('PATCH')
            <h2 class="text-base font-semibold text-slate-900">Reset Password Akun</h2>
            <div class="grid gap-4 sm:grid-cols-2">
                <div>
                    <label for="reset_password" class="block text-sm font-medium text-slate-700">Password Baru</label>
                    <input id="reset_password" name="password" type="password" required minlength="8" class="mt-1 block w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm">
                </div>
                <div>
                    <label for="reset_password_confirmation" class="block text-sm font-medium text-slate-700">Konfirmasi Password Baru</label>
                    <input id="reset_password_confirmation" name="password_confirmation" type="password" required minlength="8" class="mt-1 block w-full rounded-lg border border-slate-300 px-3.5 py-2 text-sm">
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="rounded-lg bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-800">Reset Password</button>
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
