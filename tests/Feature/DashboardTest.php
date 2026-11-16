<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/dashboard')->assertRedirect(route('login'));
    }

    public function test_dashboard_lists_registered_users(): void
    {
        $currentUser = User::factory()->create(['name' => 'Current User']);
        $otherUser = User::factory()->create(['name' => 'Other User']);

        $this->actingAs($currentUser)
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('Current User')
            ->assertSee('Other User')
            ->assertSee($otherUser->email)
            ->assertSee('Verified');
    }
}
