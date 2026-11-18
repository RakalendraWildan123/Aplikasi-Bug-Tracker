<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_homepage(): void
    {
        $response = $this->get('/');

        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_receives_successful_response(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->withoutVite()->get('/');

        $response->assertStatus(200);
    }
}
