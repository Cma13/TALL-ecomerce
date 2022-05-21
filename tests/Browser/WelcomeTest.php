<?php

namespace Tests\Browser;

use Facebook\WebDriver\WebDriverBy;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use Tests\CreateData;

class WelcomeTest extends DuskTestCase
{
    use DatabaseMigrations, CreateData;

    /**
     * A Dusk test example.
     *
     * @return void
     */

    /** @test */
    public function it_shows_at_least_five_products()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category);
        $brand = $this->createBrand($category);

        for ($i = 0; $i < 5; $i++) {
            $this->createProduct($subcategory, $brand);
        }

        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->waitFor('.product-item');
            $elements = $browser->driver->findElements(WebDriverBy::className('product-item'));
            $this->assertCount(5, $elements);
            $this->assertNotCount(6, $elements);
        });
    }

    /** @test */
    public function it_shows_only_published_products()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category);
        $brand = $this->createBrand($category);

        for ($i = 0; $i < 10; $i++) {
            if ($i < 7) {
                $this->createProduct($subcategory, $brand);
            } else {
                $this->createProduct($subcategory, $brand, 1, 1);
            }
        }

        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->waitFor('.product-item');
            $elements = $browser->driver->findElements(WebDriverBy::className('product-item'));
            $this->assertCount(7, $elements);
            $this->assertNotCount(8, $elements);
            $this->assertNotCount(10, $elements);
        });
    }

    /** @test */
    public function it_shows_the_details_of_a_category()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category);
        $brand = $this->createBrand($category);

        $this->createProduct($subcategory, $brand);

        $this->browse(function (Browser $browser) use ($category, $subcategory, $brand) {
            $browser->visit('/')
                ->click('@' . $category->name)
                ->assertRouteIs('categories.show', $category)
                ->pause(1500)
                ->assertSee($subcategory->name)
                ->assertSee($brand->name);
        });
    }
}
