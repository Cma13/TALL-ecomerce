<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\CreateData;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations, CreateData;

    /**
     * A Dusk test example.
     *
     * @return void
     */

    /** @test */
    public function it_shows_the_right_buttons_when_not_logged_in()
    {
        $this->createDefault();

        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->click('@userButton')
                ->waitForText('Iniciar sesión')
                ->assertSee('Iniciar sesión')
                ->assertSee('Registrarse')
                ->assertDontSee('Perfil')
                ->assertDontSee('Finalizar sesión');
        });
    }

    /** @test */
    public function it_shows_the_right_buttons_when_logged()
    {
        $this->createDefault();

        $this->browse(function (Browser $browser) {
            $browser->loginAs(User::find(1))
                ->visit('/')
                ->click('@userButton')
                ->pause(1500)
                ->assertDontSee('Iniciar sesión')
                ->assertDontSee('Registrarse')
                ->assertSee('Perfil')
                ->assertSee('Finalizar sesión');
            });
    }
}
