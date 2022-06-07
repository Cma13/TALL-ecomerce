<?php

namespace Tests\Feature;

use App\Http\Livewire\Search;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\CreateData;

class SearchBarTest extends TestCase
{
    use RefreshDatabase, CreateData;

    /** @test */
    public function it_shows_the_products_when_searching_it_in_the_search_bar()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category);
        $brand = $this->createBrand($category);

        $product1 = $this->createProduct($subcategory, $brand, 5);
        $product1->name = 'Xbox';
        $product1->save(); //Actualiza el producto en la base de datos

        $product2 = $this->createProduct($subcategory, $brand, 5);

        Livewire::test(Search::class)
            ->set(['search' => 'bo'])
            ->assertSee($product1->name)
            ->assertDontSee($product2->name);
    }

    /** @test */
    public function it_doesnt_shows_products_when_the_search_bar_is_empty()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category);
        $brand = $this->createBrand($category);

        $product1 = $this->createProduct($subcategory, $brand, 5);
        $product1->name = 'Xbox';
        $product1->save(); //Actualiza el producto en la base de datos

        $product2 = $this->createProduct($subcategory, $brand, 5);

        Livewire::test(Search::class)
            ->set(['search' => ''])
            ->assertSee('No existe ningún registro con los parámetros especificados')
            ->assertDontSee($product1->name)
            ->assertDontSee($product2->name);
    }
}
