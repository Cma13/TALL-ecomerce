<?php

namespace Tests\Browser;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class DropdownTest extends DuskTestCase
{
    use DatabaseMigrations;

    /**
     * A Dusk test example.
     *
     * @return void
     */

    /** @test */
    public function it_shows_the_dropdown_menu_when_clicking_the_button()
    {
        $category1 = Category::factory()->create([
            'name' => 'Celulares y tablets'
        ]);
        
        $category2 = Category::factory()->create([
            'name' => 'Computación'
        ]);

        Subcategory::factory()->create([
            'category_id' => $category1->id,
            'name' => 'Celulares y smartphones'
        ]);

        Subcategory::factory()->create([
            'category_id' => $category2->id,
            'name' => 'Portátiles',
        ]);

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
        $category1 = Category::factory()->create([
            'name' => 'Celulares y tablets'
        ]);
        
        $category2 = Category::factory()->create([
            'name' => 'Computación'
        ]);

        Subcategory::factory()->create([
            'category_id' => $category1->id,
            'name' => 'Celulares y smartphones'
        ]);

        Subcategory::factory()->create([
            'category_id' => $category2->id,
            'name' => 'Portátiles',
        ]);

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
