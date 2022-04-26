<?php

namespace Tests\Browser;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UserDropdownTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @return void
     */

    /** @test */
    public function it_show_the_login_and_register_dropdown_when_not_logged_in()
    {
        $category1 = Category::factory()->create([
            'name' => 'Celulares y tablets'
        ]);

        Subcategory::factory()->create([
            'category_id' => $category1->id,
            'name' => 'Celulares y smartphones'
        ]);

        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->click('@loginLink')
                    ->assertSee('Iniciar sesión')
                    ->assertSee('Registrarse')
                    ->assertDontSee('Perfil');
        });
    }
}
