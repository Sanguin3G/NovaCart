<?php

namespace Tests\Feature\Auth;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_login_uses_only_the_admin_guard(): void
    {
        Admin::create([
            'name' => 'Store Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->post(route('login-post'), [
            'email' => 'admin@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect(route('dashboard', absolute: false));
        $this->assertAuthenticated('admin');
        $this->assertGuest('web');
        $this->get(route('dashboard'))->assertOk();
    }
}
