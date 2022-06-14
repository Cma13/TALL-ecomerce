<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\CreateData;

class CategoryProductsTest extends DuskTestCase
{
    use DatabaseMigrations, CreateData;

    /** @test */
    public function brands_and_subcategory_filters_works_in_show_category()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category);
        $brand = $this->createBrand($category);

        $this->createProduct($subcategory, $brand);

        $this->browse(function (Browser $browser) use ($category, $subcategory, $brand) {
            $browser->visitRoute('categories.show', $category)
                ->assertSee($category->name)
                ->clickLink($subcategory->name)
                ->clickLink($brand->name)
                ->pause(1000)
                ->assertQueryStringHas('subcategoria', $subcategory->slug)
                ->assertQueryStringHas('marca', $brand->name);
        });
    }

    /** @test */
    public function can_access_to_the_details_of_a_product()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category);
        $brand = $this->createBrand($category);

        $product = $this->createProduct($subcategory, $brand);

        $this->browse(function (Browser $browser) use ($category, $subcategory, $brand, $product) {
            $browser->visitRoute('categories.show', $category)
                ->assertSee($category->name)
                ->clickLink($product->name)
                ->assertRouteIs('products.show', $product);
        });
    }
}
