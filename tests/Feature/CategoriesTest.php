<?php

namespace Tests\Feature;

use App\Http\Livewire\Navigation;
use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class CategoriesTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */

    /** @test */
    public function it_shows_the_navigation_component()
    {
        $category = Category::factory()->create([
            'name' => 'Computación',
        ]);

        Livewire::test(Navigation::class)
            ->assertStatus(200)
            ->assertSee('Categorías');
    }
}