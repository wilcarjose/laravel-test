<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends DuskTestCase
{
    use RefreshDatabase;

    public function test_successful_registration()
    {
        $user = User::factory()->create([
            'email' => 'user@test.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', 'password')
                    ->press('Login')
                    ->assertPathIs('/dashboard')
                    ->assertSee("You're logged in!");
        });
    }

    public function test_wrong_password()
    {
        $user = User::factory()->create([
            'email' => 'user@test.com',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
        ]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/login')
                    ->type('email', $user->email)
                    ->type('password', '1234567890')
                    ->press('Login')
                    ->assertPathIs('/login')
                    ->assertSee("These credentials do not match our records.");
        });
    }

    public function test_user_does_not_exist()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/login')
                    ->type('email', 'user@test.com')
                    ->type('password', 'password')
                    ->press('Login')
                    ->assertPathIs('/login')
                    ->assertSee("These credentials do not match our records.");
        });
    }
}
