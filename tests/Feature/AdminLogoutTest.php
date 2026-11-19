<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_pages_ask_before_logging_out(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']))
            ->get('/admin')->assertOk()
            ->assertSee('id="logout-form"', false)
            ->assertSee('Are you sure you want to log out of the admin panel?')
            ->assertSee('Yes, log out');
    }

    public function test_confirming_logs_the_admin_out(): void
    {
        $this->actingAs(User::factory()->create(['role' => 'admin']));

        $this->post('/logout')->assertRedirect();
        $this->assertGuest();
        $this->get('/admin')->assertRedirect('/login');
    }
}
