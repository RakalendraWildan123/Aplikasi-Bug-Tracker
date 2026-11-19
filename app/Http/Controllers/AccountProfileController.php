<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AccountProfileController extends Controller
{
    public function edit(Request $request): View|JsonResponse
    {
        $user = $request->user();

        if ($request->wantsJson()) {
            return response()->json([
                'user' => $user,
                'developer' => $user->developer,
            ]);
        }

        return view('account.edit', [
            'user' => $user,
        ]);
    }

    public function verifyPassword(Request $request): JsonResponse
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        if (! Hash::check($request->password, $request->user()->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Password saat ini yang Anda masukkan salah.',
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Verifikasi password berhasil.',
        ]);
    }

    public function updateProfile(Request $request): JsonResponse|RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => ['required', 'string', 'current_password'],
            'name' => ['required', 'string', 'max:150'],
            'avatar' => ['nullable', 'string', 'max:10'],
            'avatar_bg' => ['nullable', 'string', 'max:50'],
            'foto_profil' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'hapus_foto_profil' => ['sometimes', 'boolean'],
            'new_password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Password saat ini wajib diisi untuk verifikasi.',
            'current_password.current_password' => 'Password saat ini yang Anda masukkan salah.',
            'name.required' => 'Nama lengkap wajib diisi.',
            'foto_profil.image' => 'File foto profil harus berupa gambar.',
            'foto_profil.mimes' => 'Format foto profil harus JPG, PNG, atau WebP.',
            'foto_profil.max' => 'Ukuran foto profil maksimal 2 MB.',
            'new_password.min' => 'Password baru minimal 8 karakter.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $oldPhoto = $user->foto_profil;
        $newPhotoPath = $oldPhoto;
        $shouldDeleteOld = false;

        if ($request->hasFile('foto_profil')) {
            $newPhotoPath = $request->file('foto_profil')->store('profile-photos', 'public');
            $shouldDeleteOld = $oldPhoto !== null && $oldPhoto !== $newPhotoPath;
        } elseif ($request->boolean('hapus_foto_profil')) {
            $newPhotoPath = null;
            $shouldDeleteOld = $oldPhoto !== null;
        }

        $userPayload = [
            'name' => $validated['name'],
            'email' => $user->email, // Email permanen, tidak dapat diubah oleh pemilik akun
            'foto_profil' => $newPhotoPath,
        ];

        if (! empty($validated['new_password'])) {
            $userPayload['password'] = $validated['new_password'];
        }

        if ($user->isDeveloper()) {
            $avatarInput = trim((string) ($validated['avatar'] ?? ''));
            $userPayload['avatar'] = $avatarInput !== ''
                ? Str::upper(Str::substr($avatarInput, 0, 10))
                : $this->generateInitials($validated['name']);

            if (! empty($validated['avatar_bg'])) {
                $userPayload['avatar_bg'] = $validated['avatar_bg'];
            }
        }

        $user->update($userPayload);

        if ($shouldDeleteOld && $oldPhoto) {
            Storage::disk('public')->delete($oldPhoto);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Data akun Anda berhasil diperbarui.',
                'user' => $user->fresh(),
                'developer' => $user->fresh()->developer,
            ]);
        }

        return redirect()
            ->route('account.edit')
            ->with('profile_status', 'Data akun Anda berhasil diperbarui.');
    }

    public function updatePassword(Request $request): JsonResponse|RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => ['required', 'string', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update([
            'password' => $validated['password'],
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Password Anda berhasil diperbarui.',
            ]);
        }

        return redirect()
            ->route('account.edit')
            ->with('password_status', 'Password Anda berhasil diperbarui.');
    }

    private function generateInitials(string $name): string
    {
        $initials = collect(preg_split('/\s+/', trim($name)))
            ->filter()
            ->take(2)
            ->map(fn (string $part) => Str::upper(Str::substr($part, 0, 1)))
            ->implode('');

        return $initials !== '' ? $initials : 'DV';
    }
}
