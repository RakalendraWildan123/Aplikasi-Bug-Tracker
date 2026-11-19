<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MyAccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_my_account_page(): void
    {
        $this->get('/account')->assertRedirect(route('login'));
    }

    public function test_account_and_admin_endpoints_accessible_per_role(): void
    {
        $dev = User::factory()->create(['name' => 'Dev User', 'role' => 'developer']);
        $this->actingAs($dev)
            ->getJson('/account')
            ->assertOk()
            ->assertJsonPath('user.name', 'Dev User');

        $admin = User::factory()->admin()->create(['name' => 'Admin User']);
        $this->actingAs($admin)
            ->getJson('/account')
            ->assertOk()
            ->assertJsonPath('user.name', 'Admin User');
    }

    public function test_account_owner_can_update_profile_without_role_escalation(): void
    {
        $user = User::factory()->create([
            'name' => 'Raka Lama',
            'email' => 'raka.lama@bugtracker.test',
            'password' => 'password123',
            'role' => 'developer',
            'avatar' => 'RL',
            'avatar_bg' => '#4F46E5',
        ]);

        $this->actingAs($user)
            ->put('/account/profile', [
                'current_password' => 'password123',
                'name' => 'Raka Baru',
                'email' => 'raka.baru@bugtracker.test',
                'avatar' => 'rb',
                'avatar_bg' => '#10B981',
                'role' => 'admin', // Must be ignored (no self role escalation)
            ])
            ->assertRedirect(route('account.edit'))
            ->assertSessionHas('profile_status');

        $freshUser = $user->fresh();

        $this->assertSame('Raka Baru', $freshUser->name);
        $this->assertSame('raka.lama@bugtracker.test', $freshUser->email); // Email permanen tidak berubah
        $this->assertSame('developer', $freshUser->role);
        $this->assertSame('RB', $freshUser->avatar);
        $this->assertSame('#10B981', $freshUser->avatar_bg);
    }

    public function test_account_owner_can_change_password_with_valid_current_password(): void
    {
        $user = User::factory()->create([
            'email' => 'owner@bugtracker.test',
            'password' => 'oldpass123',
            'role' => 'developer',
        ]);

        // Wrong current password rejected
        $this->actingAs($user)
            ->put('/account/password', [
                'current_password' => 'wrongpass',
                'password' => 'newpass123',
                'password_confirmation' => 'newpass123',
            ])
            ->assertSessionHasErrors('current_password');

        // Correct current password updates password
        $this->actingAs($user)
            ->put('/account/password', [
                'current_password' => 'oldpass123',
                'password' => 'newpass123',
                'password_confirmation' => 'newpass123',
            ])
            ->assertRedirect(route('account.edit'))
            ->assertSessionHas('password_status');

        $this->assertTrue(Hash::check('newpass123', $user->fresh()->password));

        // Can log in with new password and redirects to account.edit
        $this->post('/logout');
        $this->post('/login', [
            'email' => 'owner@bugtracker.test',
            'password' => 'newpass123',
        ])->assertRedirect(route('account.edit'));
    }

    public function test_account_owner_can_upload_replace_and_delete_profile_photo(): void
    {
        Storage::fake('public');

        $user = User::factory()->create([
            'name' => 'Foto Dev',
            'email' => 'foto.dev@bugtracker.test',
            'password' => 'password123',
            'role' => 'developer',
        ]);

        // 1. Upload initial photo
        $firstPhoto = UploadedFile::fake()->image('avatar-1.jpg', 300, 300);
        $this->actingAs($user)
            ->put('/account/profile', [
                'current_password' => 'password123',
                'name' => 'Foto Dev',
                'foto_profil' => $firstPhoto,
            ])
            ->assertRedirect(route('account.edit'))
            ->assertSessionHas('profile_status');

        $firstPath = $user->fresh()->foto_profil;
        $this->assertNotNull($firstPath);
        Storage::disk('public')->assertExists($firstPath);

        // API returns uploaded photo URL
        $this->actingAs($user->fresh())
            ->getJson('/account')
            ->assertOk()
            ->assertJsonPath('user.foto_profil', $firstPath);

        // 2. Replace with new photo -> old photo removed
        $secondPhoto = UploadedFile::fake()->image('avatar-2.png', 300, 300);
        $this->actingAs($user->fresh())
            ->put('/account/profile', [
                'current_password' => 'password123',
                'name' => 'Foto Dev',
                'foto_profil' => $secondPhoto,
            ])
            ->assertRedirect(route('account.edit'));

        $secondPath = $user->fresh()->foto_profil;
        $this->assertNotSame($firstPath, $secondPath);
        Storage::disk('public')->assertMissing($firstPath);
        Storage::disk('public')->assertExists($secondPath);

        // 3. Reject non-image file
        $badFile = UploadedFile::fake()->create('document.pdf', 100, 'application/pdf');
        $this->actingAs($user->fresh())
            ->put('/account/profile', [
                'current_password' => 'password123',
                'name' => 'Foto Dev',
                'foto_profil' => $badFile,
            ])
            ->assertSessionHasErrors('foto_profil');

        // 4. Delete current photo
        $this->actingAs($user->fresh())
            ->put('/account/profile', [
                'current_password' => 'password123',
                'name' => 'Foto Dev',
                'hapus_foto_profil' => '1',
            ])
            ->assertRedirect(route('account.edit'));

        $this->assertNull($user->fresh()->foto_profil);
        Storage::disk('public')->assertMissing($secondPath);
    }
}
