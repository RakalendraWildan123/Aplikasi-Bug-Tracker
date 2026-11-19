<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreAccountRequest;
use App\Http\Requests\Admin\UpdateAccountRequest;
use App\Models\Developer;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function index(Request $request): View
    {
        $roleFilter = $request->string('role')->toString();
        $statusFilter = $request->string('status')->toString();

        $accounts = User::query()
            ->with('developer')
            ->when(in_array($roleFilter, User::ROLES, true), fn ($q) => $q->where('role', $roleFilter))
            ->when($statusFilter === 'active', fn ($q) => $q->where('is_active', true))
            ->when($statusFilter === 'inactive', fn ($q) => $q->where('is_active', false))
            ->orderByRaw("CASE WHEN role = 'admin' THEN 0 ELSE 1 END")
            ->orderBy('name')
            ->get();

        return view('admin.accounts.index', [
            'accounts' => $accounts,
            'roleLabels' => User::ROLE_LABELS,
            'roleFilter' => $roleFilter,
            'statusFilter' => $statusFilter,
        ]);
    }

    public function create(): View
    {
        return view('admin.accounts.create', [
            'roleLabels' => User::ROLE_LABELS,
            'developerRoles' => Developer::ROLES,
        ]);
    }

    public function store(StoreAccountRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $isActive = array_key_exists('is_active', $validated) ? (bool) $validated['is_active'] : true;
        $role = $validated['role'];
        $photoPath = $request->hasFile('foto_profil')
            ? $request->file('foto_profil')->store('profile-photos', 'public')
            : null;

        DB::transaction(function () use ($validated, $role, $isActive, $photoPath) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
                'role' => $role,
                'is_active' => $isActive,
                'foto_profil' => $photoPath,
            ]);

            if ($role === 'developer') {
                Developer::create([
                    'user_id' => $user->id,
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'password' => $validated['password'],
                    'role_title' => $validated['role_title'],
                    'avatar' => $this->resolveAvatar($validated['avatar'] ?? null, $validated['name']),
                    'avatar_bg' => ($validated['avatar_bg'] ?? null) ?: '#4F46E5',
                    'foto_profil' => $photoPath,
                    'is_active' => $isActive,
                ]);
            }
        });

        return redirect()
            ->route('admin.accounts.index')
            ->with('status', "Akun {$validated['name']} berhasil dibuat.");
    }

    public function edit(User $user): View
    {
        $user->load('developer');

        return view('admin.accounts.edit', [
            'account' => $user,
            'roleLabels' => User::ROLE_LABELS,
            'developerRoles' => Developer::ROLES,
        ]);
    }

    public function update(UpdateAccountRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();
        $role = $validated['role'];
        $isActive = array_key_exists('is_active', $validated) ? (bool) $validated['is_active'] : $user->is_active;

        if ($request->user()->is($user)) {
            if ($role !== 'admin') {
                return back()
                    ->withErrors(['role' => 'Anda tidak dapat menurunkan peran akun Anda sendiri.'])
                    ->withInput();
            }

            if (! $isActive) {
                return back()
                    ->withErrors(['is_active' => 'Anda tidak dapat menonaktifkan akun Anda sendiri.'])
                    ->withInput();
            }
        }

        $oldPhoto = $user->foto_profil ?? $user->developer?->foto_profil;
        $newPhotoPath = $oldPhoto;
        $shouldDeleteOld = false;

        if ($request->hasFile('foto_profil')) {
            $newPhotoPath = $request->file('foto_profil')->store('profile-photos', 'public');
            $shouldDeleteOld = $oldPhoto !== null && $oldPhoto !== $newPhotoPath;
        } elseif ($request->boolean('hapus_foto_profil')) {
            $newPhotoPath = null;
            $shouldDeleteOld = $oldPhoto !== null;
        }

        DB::transaction(function () use ($user, $validated, $role, $isActive, $newPhotoPath) {
            $user->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $role,
                'is_active' => $isActive,
                'foto_profil' => $newPhotoPath,
            ]);

            if ($role === 'developer') {
                $developerData = [
                    'user_id' => $user->id,
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'role_title' => $validated['role_title'],
                    'avatar' => $this->resolveAvatar($validated['avatar'] ?? null, $validated['name']),
                    'avatar_bg' => ($validated['avatar_bg'] ?? null) ?: '#4F46E5',
                    'foto_profil' => $newPhotoPath,
                    'is_active' => $isActive,
                ];

                if ($user->developer) {
                    $user->developer->update($developerData);
                } else {
                    Developer::create($developerData);
                }
            } elseif ($user->developer) {
                $user->developer->update([
                    'name' => $validated['name'],
                    'email' => $validated['email'],
                    'foto_profil' => $newPhotoPath,
                    'is_active' => $isActive,
                ]);
            }
        });

        if ($shouldDeleteOld && $oldPhoto) {
            Storage::disk('public')->delete($oldPhoto);
        }

        return redirect()
            ->route('admin.accounts.index')
            ->with('status', "Akun {$user->name} berhasil diperbarui.");
    }

    public function toggleActive(Request $request, User $user): RedirectResponse
    {
        if ($request->user()->is($user)) {
            return back()->withErrors(['account' => 'Anda tidak dapat menonaktifkan akun Anda sendiri.']);
        }

        DB::transaction(function () use ($user) {
            $nextStatus = ! $user->is_active;

            $user->update(['is_active' => $nextStatus]);

            if ($user->developer) {
                $user->developer->update(['is_active' => $nextStatus]);
            }
        });

        $label = $user->fresh()->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()
            ->route('admin.accounts.index')
            ->with('status', "Akun {$user->name} berhasil {$label}.");
    }

    public function resetPassword(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
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
            ->route('admin.accounts.edit', $user)
            ->with('status', "Password akun {$user->name} berhasil direset.");
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        if ($request->user()->is($user)) {
            return back()->withErrors(['account' => 'Anda tidak dapat menghapus akun Anda sendiri.']);
        }

        $name = $user->name;
        $photoToDelete = $user->foto_profil;
        $keepPhotoForDeveloper = false;

        DB::transaction(function () use ($user, &$keepPhotoForDeveloper) {
            $developer = $user->developer;

            if ($developer) {
                $hasCreatedHistory = $developer->createdTasks()->exists()
                    || $developer->createdBugs()->exists();

                if ($hasCreatedHistory) {
                    $keepPhotoForDeveloper = $developer->foto_profil !== null;
                    $developer->update([
                        'user_id' => null,
                        'is_active' => false,
                    ]);
                } else {
                    $developer->delete();
                }
            }

            $user->delete();
        });

        if ($photoToDelete && ! $keepPhotoForDeveloper) {
            Storage::disk('public')->delete($photoToDelete);
        }

        return redirect()
            ->route('admin.accounts.index')
            ->with('status', "Akun {$name} berhasil dihapus.");
    }

    private function resolveAvatar(?string $avatar, string $name): string
    {
        $trimmed = trim((string) $avatar);

        if ($trimmed !== '') {
            return Str::upper(Str::substr($trimmed, 0, 10));
        }

        $initials = collect(preg_split('/\s+/', trim($name)))
            ->filter()
            ->take(2)
            ->map(fn (string $part) => Str::upper(Str::substr($part, 0, 1)))
            ->implode('');

        return $initials !== '' ? $initials : 'DV';
    }
}
