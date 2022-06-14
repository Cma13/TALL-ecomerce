<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\CreateData;

class ProductDetailsTest extends DuskTestCase
{
    use DatabaseMigrations, CreateData;

    /** @test */
    public function it_shows_the_details_of_a_product()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category);
        $brand = $this->createBrand($category);

        $product = $this->createProduct($subcategory, $brand);

        $this->browse(function (Browser $browser) use ($brand, $product) {
            $browser->visitRoute('products.show', $product)
                ->assertSee($product->name)
                ->assertSee(ucfirst($brand->name))
                ->assertSee($product->description)
                ->assertSee($product->price)
                ->assertSee($product->quantity)
                ->assertSourceHas('<img src="/storage/' . $product->images->pluck('url')->first() .  '" draggable="false">')
                ->assertSee('+')
                ->assertSee('-')
                ->pause(500)
                ->assertSee('AGREGAR AL CARRITO DE COMPRAS');
        });
    }

    /** @test */
    public function the_increase_and_decrease_buttons_have_limits()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category);
        $brand = $this->createBrand($category);

        $product = $this->createProduct($subcategory, $brand, 2);

        $this->browse(function (Browser $browser) use ($product) {
            $browser->visitRoute('products.show', $product)
                ->assertSee($product->name)
                ->assertSee('1')
                ->assertDisabled('@decrease-button')
                ->assertEnabled('@increase-button')
                ->click('@increase-button')
                ->pause(500)
                ->assertSee('2')
                ->assertDisabled('@increase-button');
        });
    }

    /** @test */
    public function it_shows_the_correct_select_dropdown()
    {
        $category = $this->createCategory();
        $subcategoryColor = $this->createSubcategory($category, true);
        $subcategorySize = $this->createSubcategory($category, true, true);
        $brand = $this->createBrand($category);

        $product1 = $this->createProduct($subcategoryColor, $brand);
        $product2 = $this->createProduct($subcategorySize, $brand);

        //Producto con color, sin talla
        $this->browse(function (Browser $browser) use ($product1, $product2) {
            $browser->visitRoute('products.show', $product1)
                ->assertSee($product1->name)
                ->assertDontSee($product2->name)
                ->assertSee('Color')
                ->assertSourceHas('Seleccionar un color')
                ->assertDontSee('Talla')
                ->assertSourceMissing('Seleccionar una talla');
        });

        //Producto con color y tallas
        $this->browse(function (Browser $browser) use ($product1, $product2) {
            $browser->visitRoute('products.show', $product2)
                ->assertSee($product2->name)
                ->assertDontSee($product1->name)
                ->assertSee('Talla')
                ->assertSourceHas('Seleccione una talla')
                ->assertSee('Color')
                ->assertSourceHas('Seleccione un color');
        });
    }
}
