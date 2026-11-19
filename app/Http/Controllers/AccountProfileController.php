<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AccountProfileController extends Controller
{
    public function edit(Request $request): View
    {
        $user = $request->user()->load('developer');

        return view('account.edit', [
            'user' => $user,
            'developer' => $user->developer,
        ]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user()->load('developer');
        $developer = $user->developer;

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'email' => [
                'required',
                'string',
                'email',
                'max:150',
                Rule::unique('users', 'email')->ignore($user->id),
                Rule::when($developer !== null, [
                    Rule::unique('developers', 'email')->ignore($developer?->id),
                ]),
            ],
            'avatar' => ['nullable', 'string', 'max:10'],
            'avatar_bg' => ['nullable', 'string', 'max:50'],
            'foto_profil' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'hapus_foto_profil' => ['sometimes', 'boolean'],
        ]);

        $oldPhoto = $user->foto_profil ?? $developer?->foto_profil;
        $newPhotoPath = $oldPhoto;
        $shouldDeleteOld = false;

        if ($request->hasFile('foto_profil')) {
            $newPhotoPath = $request->file('foto_profil')->store('profile-photos', 'public');
            $shouldDeleteOld = $oldPhoto !== null && $oldPhoto !== $newPhotoPath;
        } elseif ($request->boolean('hapus_foto_profil')) {
            $newPhotoPath = null;
            $shouldDeleteOld = $oldPhoto !== null;
        }

        DB::transaction(function () use ($user, $developer, $validated, $newPhotoPath) {
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'foto_profil' => $newPhotoPath,
            ]);

            if ($developer) {
                $avatarInput = trim((string) ($validated['avatar'] ?? ''));
                $avatar = $avatarInput !== ''
                    ? Str::upper(Str::substr($avatarInput, 0, 10))
                    : $this->generateInitials($validated['name']);

                $developer->update([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'avatar' => $avatar,
                    'avatar_bg' => ($validated['avatar_bg'] ?? '') !== '' ? $validated['avatar_bg'] : $developer->avatar_bg,
                    'foto_profil' => $newPhotoPath,
                ]);
            }
        });

        if ($shouldDeleteOld && $oldPhoto) {
            Storage::disk('public')->delete($oldPhoto);
        }

        return redirect()
            ->route('account.edit')
            ->with('profile_status', 'Informasi profil Anda berhasil diperbarui.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $user = $request->user()->load('developer');

        $validated = $request->validate([
            'current_password' => ['required', 'string', 'current_password'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        DB::transaction(function () use ($user, $validated) {
            $user->update([
                'password' => $validated['password'],
            ]);

            if ($user->developer) {
                $user->developer->update([
                    'password' => $validated['password'],
                ]);
            }
        });

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
