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

    /** @test */
    public function it_shows_the_dropdown_menu_when_clicking_the_button()
    {
        $category1 = $this->createCategory();
        $category2 = $this->createCategory();

        $this->createSubcategory($category1);
        $this->createSubcategory($category2);

        $this->browse(function (Browser $browser) use ($category1, $category2){
            $browser->visit('/')
                    ->click('@categoriasLink')
                    ->assertSee($category1->name)
                    ->assertSee($category2->name);
        });
    }

    /** @test */
    public function it_shows_the_subcategories_list_when_mouseover_category()
    {
        $category1 = $this->createCategory();
        $category2 = $this->createCategory();

        $subcategory1 = $this->createSubcategory($category1);
        $subcategory2 = $this->createSubcategory($category2);

        $this->browse(function (Browser $browser) use ($category1, $category2, $subcategory1, $subcategory2){
            $browser->visit('/')
                    ->click('@categoriasLink')
                    ->assertSee($category1->name)
                    ->assertSee($category2->name)
                    ->mouseover('@category_' . $category1->name)
                    ->assertSee($subcategory1->name)
                    ->assertDontSee($subcategory2->name);
        });
    }
}
