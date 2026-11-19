<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AccountVerificationAndModalTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_verify_password(): void
    {
        $this->postJson('/account/verify-password', [
            'password' => 'secret123',
        ])->assertUnauthorized();
    }

    public function test_user_cannot_verify_with_wrong_password(): void
    {
        $user = User::factory()->create([
            'password' => 'secret123',
        ]);

        $this->actingAs($user)
            ->postJson('/account/verify-password', [
                'password' => 'wrong-pass',
            ])
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
            ]);
    }

    public function test_user_can_verify_with_correct_password(): void
    {
        $user = User::factory()->create([
            'password' => 'secret123',
        ]);

        $this->actingAs($user)
            ->postJson('/account/verify-password', [
                'password' => 'secret123',
            ])
            ->assertOk()
            ->assertJson([
                'success' => true,
                'message' => 'Verifikasi password berhasil.',
            ]);
    }

    public function test_account_page_renders_readonly_info_and_modal(): void
    {
        $user = User::factory()->create([
            'name' => 'Budi Santoso',
            'email' => 'budi@bugtracker.test',
            'role' => 'admin',
        ]);

        $response = $this->actingAs($user)->get('/account');

        $response->assertOk();
        $response->assertSee('Budi Santoso');
        $response->assertSee('budi@bugtracker.test');
        $response->assertSee('Informasi Akun');
        $response->assertSee('Edit Akun');
        $response->assertSee('Logout');
        $response->assertSee('modal-edit');
        $response->assertSee('step-verification');
        $response->assertSee('step-edit-form');
        $response->assertDontSee('Permanen (Tidak dapat diedit)');
    }

    public function test_user_can_update_profile_and_password_with_verified_current_password(): void
    {
        $user = User::factory()->create([
            'name' => 'Nama Lama',
            'email' => 'lama@bugtracker.test',
            'password' => 'current123',
            'role' => 'developer',
        ]);

        $response = $this->actingAs($user)
            ->put('/account/profile', [
                'current_password' => 'current123',
                'name' => 'Nama Baru',
                'new_password' => 'brandnewpass123',
                'new_password_confirmation' => 'brandnewpass123',
            ]);

        $response->assertRedirect(route('account.edit'));
        $response->assertSessionHas('profile_status');

        $user->refresh();
        $this->assertSame('Nama Baru', $user->name);
        $this->assertSame('lama@bugtracker.test', $user->email); // Email tetap permanen
        $this->assertTrue(Hash::check('brandnewpass123', $user->password));
    }
}
