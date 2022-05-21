<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\CreateData;

class DropdownTest extends DuskTestCase
{
    use DatabaseMigrations, CreateData;

    /**
     * A Dusk test example.
     *
     * @return void
     */

    public function createData()
    {
        $category1 = $this->createCategory('Celulares y tablets');
        $category2 = $this->createCategory('Computación');

        $this->createSubcategory('Celulares y smartphones' ,$category1);
        $this->createSubcategory('Portátiles', $category2);
    }

    /** @test */
    public function it_shows_the_dropdown_menu_when_clicking_the_button()
    {
        $this->createData();

        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->click('@categoriasLink')
                    ->assertSee('Celulares y tablets')
                    ->assertSee('Computación');
        });
    }

    /** @test */
    public function it_shows_the_subcategories_list_when_mouseover_category()
    {
        $this->createData();

        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->click('@categoriasLink')
                    ->assertSee('Celulares y tablets')
                    ->assertSee('Computación')
                    ->mouseover('@category_Celulares y tablets')
                    ->assertSee('Celulares y smartphones')
                    ->assertDontSee('Portátiles');
        });
    }
}
