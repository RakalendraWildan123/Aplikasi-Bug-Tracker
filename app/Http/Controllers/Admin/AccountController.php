<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAccountRequest;
use App\Http\Requests\Admin\UpdateAccountRequest;
use App\Models\Developer;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AccountController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $roleFilter = $request->string('role')->toString();
        $statusFilter = $request->string('status')->toString();

        $accounts = User::query()
            ->when(in_array($roleFilter, User::ROLES, true), fn ($q) => $q->where('role', $roleFilter))
            ->when($statusFilter === 'active', fn ($q) => $q->where('is_active', true))
            ->when($statusFilter === 'inactive', fn ($q) => $q->where('is_active', false))
            ->orderByRaw("CASE WHEN role = 'admin' THEN 0 ELSE 1 END")
            ->orderBy('name')
            ->get();

        return response()->json([
            'accounts' => $accounts,
            'role_labels' => User::ROLE_LABELS,
            'role_filter' => $roleFilter,
            'status_filter' => $statusFilter,
        ]);
    }

    public function create(): JsonResponse
    {
        return response()->json([
            'role_labels' => User::ROLE_LABELS,
            'developer_roles' => Developer::ROLES,
        ]);
    }

    public function show(User $user): JsonResponse
    {
        return response()->json([
            'account' => $user,
        ]);
    }

    public function edit(User $user): JsonResponse
    {
        return response()->json([
            'account' => $user,
            'role_labels' => User::ROLE_LABELS,
            'developer_roles' => Developer::ROLES,
        ]);
    }

    public function store(StoreAccountRequest $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validated();
        $isActive = array_key_exists('is_active', $validated) ? (bool) $validated['is_active'] : true;
        $role = $validated['role'];

        $photoPath = null;
        if ($request->hasFile('foto_profil')) {
            $photoPath = $request->file('foto_profil')->store('profile-photos', 'public');
        }

        $userPayload = [
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => $role,
            'is_active' => $isActive,
            'foto_profil' => $photoPath,
        ];

        if ($role === 'developer') {
            $avatarInput = trim((string) ($validated['avatar'] ?? ''));
            $userPayload['role_title'] = $validated['role_title'] ?? ($validated['developer_role_title'] ?? 'Backend');
            $userPayload['avatar'] = $avatarInput !== ''
                ? Str::upper(Str::substr($avatarInput, 0, 10))
                : $this->generateInitials($validated['name']);
            $userPayload['avatar_bg'] = ($validated['avatar_bg'] ?? null) ?: '#4F46E5';
        } else {
            $userPayload['role_title'] = null;
            $userPayload['avatar'] = null;
            $userPayload['avatar_bg'] = null;
        }

        $createdUser = User::query()->create($userPayload);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Akun berhasil dibuat.',
                'account' => $createdUser,
            ], 201);
        }

        return redirect()
            ->route('admin.accounts.index')
            ->with('status', 'Akun berhasil dibuat.');
    }

    public function update(UpdateAccountRequest $request, User $user): JsonResponse|RedirectResponse
    {
        $validated = $request->validated();
        $isActive = array_key_exists('is_active', $validated) ? (bool) $validated['is_active'] : (bool) $user->is_active;
        $role = $validated['role'];

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
            'email' => $validated['email'],
            'role' => $role,
            'is_active' => $isActive,
            'foto_profil' => $newPhotoPath,
        ];

        if (! empty($validated['password'])) {
            $userPayload['password'] = $validated['password'];
        }

        if ($role === 'developer') {
            $avatarInput = trim((string) ($validated['avatar'] ?? ''));
            $userPayload['role_title'] = $validated['role_title'] ?? ($validated['developer_role_title'] ?? ($user->role_title ?? 'Backend'));
            $userPayload['avatar'] = $avatarInput !== ''
                ? Str::upper(Str::substr($avatarInput, 0, 10))
                : ($user->avatar ?: $this->generateInitials($validated['name']));
            $userPayload['avatar_bg'] = ($validated['avatar_bg'] ?? null) ?: ($user->avatar_bg ?? '#4F46E5');
        } else {
            $userPayload['role_title'] = null;
            $userPayload['avatar'] = null;
            $userPayload['avatar_bg'] = null;
        }

        $user->update($userPayload);

        if ($shouldDeleteOld && $oldPhoto) {
            Storage::disk('public')->delete($oldPhoto);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Akun berhasil diperbarui.',
                'account' => $user->fresh(),
            ]);
        }

        return redirect()
            ->route('admin.accounts.index')
            ->with('status', 'Akun berhasil diperbarui.');
    }

    public function toggleActive(Request $request, User $user): JsonResponse|RedirectResponse
    {
        if ($user->id === $request->user()->id) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Anda tidak dapat menonaktifkan akun sendiri.',
                ], 422);
            }

            return back()->withErrors(['account' => 'Anda tidak dapat menonaktifkan akun sendiri.']);
        }

        $nextState = ! $user->is_active;
        $user->update(['is_active' => $nextState]);

        $statusMessage = $nextState
            ? "Akun {$user->name} berhasil diaktifkan."
            : "Akun {$user->name} berhasil dinonaktifkan.";

        if ($request->wantsJson()) {
            return response()->json([
                'message' => $statusMessage,
                'is_active' => $user->fresh()->is_active,
            ]);
        }

        return redirect()
            ->route('admin.accounts.index')
            ->with('status', $statusMessage);
    }

    public function resetPassword(Request $request, User $user): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $user->update([
            'password' => $validated['password'],
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'message' => "Password {$user->name} berhasil direset.",
            ]);
        }

        return redirect()
            ->route('admin.accounts.edit', $user)
            ->with('status', "Password akun {$user->name} berhasil direset.");
    }

    public function destroy(Request $request, User $user): JsonResponse|RedirectResponse
    {
        if ($request->user()->is($user)) {
            if ($request->wantsJson()) {
                return response()->json([
                    'message' => 'Anda tidak dapat menghapus akun Anda sendiri.',
                ], 422);
            }

            return back()->withErrors(['account' => 'Anda tidak dapat menghapus akun Anda sendiri.']);
        }

        $photoToDelete = $user->foto_profil;

        $user->delete();

        if ($photoToDelete) {
            Storage::disk('public')->delete($photoToDelete);
        }

        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Akun berhasil dihapus.',
            ]);
        }

        return redirect()
            ->route('admin.accounts.index')
            ->with('status', 'Akun berhasil dihapus.');
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
