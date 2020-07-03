<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\User;

class RegisterTest extends DuskTestCase
{
    /**
     * A Dusk test example.
     *
     * @return void
     */

    /** @test */
    public function person_can_create_account()
    {
        $user = factory(User::class)->make();
        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit('/')
            ->visit('/register')
            ->waitFor('.v-form')
            ->type('name', $user->name)
            ->type('email', $user->email)
            ->type('username', $user->username)
            ->type('password', $user->password)
            ->type('password_confirmation', $user->password)
            ->press('register')
            ->waitFor('.swal2-confirm')
            ->assertSee('Welcome');
        });
    }

    /** @test */
    public function person_cant_create_account_incorrectly_email()
    {
        $user = factory(User::class)->make();
        $this->browse(function (Browser $browser) use ($user){
            $browser->visit('/')
           ->visit('/register')
           ->waitFor('.v-form')
           ->type('name', $user->name)
           ->type('email', substr($user->email, 0, strlen($user->email)-4))
           ->type('username', $user->username)
           ->type('password', $user->password)
           ->type('password_confirmation', $user->password)
           ->press('register')
           ->waitFor('.v-messages__message')
           ->assertSee('The email must be a valid email address.');
        });
    }

    /** @test */
    public function person_cant_create_account_incorrectly_password()
    {
        $user = factory(User::class)->make();
        $this->browse(function (Browser $browser) use ($user){
            $browser->visit('/')
              ->visit('/register')
              ->waitFor('.v-form')
              ->type('name', $user->name)
              ->type('email', $user->email)
              ->type('username', $user->username)
              ->type('password', '1')
              ->type('password_confirmation', '1')
              ->press('register')
              ->waitFor('.v-messages__message')
              ->assertSee('The password must be at least 8 characters');
        });
    }
}
