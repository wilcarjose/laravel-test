<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends DuskTestCase
{
    use RefreshDatabase;

    public function test_successful_registration()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                    ->type('name', 'Test Name')
                    ->type('email', 'user@test.com')
                    ->type('password', 'abcd123456')
                    ->type('password_confirmation', 'abcd123456')
                    ->press('Register')
                    ->assertPathIs('/dashboard')
                    ->assertSee("You're logged in!");
        });
    }

    public function test_password_confirmation_failed()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                    ->type('name', 'Test Name')
                    ->type('email', 'mail@test.com')
                    ->type('password', 'abcd123456')
                    ->type('password_confirmation', 'abcddefgh')
                    ->press('Register')
                    ->assertPathIs('/register')
                    ->assertSee('The password confirmation does not match.');
        });
    }

    public function test_email_already_exists()
    {
        User::factory()->create([
            'email' => 'user@test.com',
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/register')
                    ->type('name', 'Test Name')
                    ->type('email', 'user@test.com')
                    ->type('password', 'abcd123456')
                    ->type('password_confirmation', 'abcd123456')
                    ->press('Register')
                    ->assertPathIs('/register')
                    ->assertSee('The email has already been taken.');
        });
    }
}
