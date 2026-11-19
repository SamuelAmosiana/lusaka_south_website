<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminLoginPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_page_shows_the_site_logo_and_form(): void
    {
        $this->get('/login')->assertOk()
            ->assertSee('images/school_logo.jpg')
            ->assertSee('Welcome back')
            ->assertSee('name="email"', false)
            ->assertSee('name="password"', false)
            ->assertSee('Back to website');
    }

    public function test_wrong_password_shows_an_error_and_keeps_the_email(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->from('/login')->post('/login', ['email' => $user->email, 'password' => 'wrong-password'])
            ->assertRedirect('/login');
        $this->assertGuest();

        $this->get('/login')->assertSee('These credentials do not match our records.')->assertSee($user->email);
    }

    public function test_admin_can_sign_in(): void
    {
        $user = User::factory()->create(['role' => 'admin']);

        $this->post('/login', ['email' => $user->email, 'password' => 'password'])->assertRedirect();
        $this->assertAuthenticatedAs($user);
    }
}
