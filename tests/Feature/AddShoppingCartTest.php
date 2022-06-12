<?php

namespace Tests\Feature;

use App\Http\Livewire\AddCartItem;
use App\Http\Livewire\AddCartItemColor;
use App\Http\Livewire\AddCartItemSize;
use App\Http\Livewire\DropdownCart;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\CreateData;

class AddShoppingCartTest extends TestCase
{
    use RefreshDatabase, CreateData;

    /** @test */
    public function a_item_without_color_can_be_added_to_cart()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category);
        $brand = $this->createBrand($category);

        $product1 = $this->createProduct($subcategory, $brand);
        $product2 = $this->createProduct($subcategory, $brand);

        Livewire::test(AddCartItem::class, ['product' => $product1])
            ->call('addItem', $product1)
            ->assertStatus(200);
        $this->assertEquals($product1->id, Cart::content()->first()->id);
        $this->assertNotEquals($product2->id, Cart::content()->first()->id);
        $this->assertTrue(Cart::content()->first()->color_id == null);
        $this->assertTrue(Cart::content()->first()->size_id == null);
    }

    /** @test */
    public function a_item_with_color_can_be_added_to_cart()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category, true);
        $brand = $this->createBrand($category);

        $product1 = $this->createProduct($subcategory, $brand, 2, 2, false, true);
        $color = $product1->colors()->first();
        $product2 = $this->createProduct($subcategory, $brand);

        Livewire::test(AddCartItemColor::class, ['product' => $product1])
            ->set('options', ['color' => $color->name])
            ->call('addItem', $product1)
            ->assertStatus(200);
        $this->assertEquals($product1->id, Cart::content()->first()->id);
        $this->assertNotEquals($product2->id, Cart::content()->first()->id);
        $this->assertTrue(Cart::content()->first()->options['color'] == $color->name);
        $this->assertTrue(Cart::content()->first()->size_id == null);
    }

    /** @test */
    public function a_item_with_color_and_size_can_be_added_to_cart()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category, true);
        $brand = $this->createBrand($category);

        $product1 = $this->createProduct($subcategory, $brand, 2, 2, true, true);
        $size = $product1->sizes()->first();
        $color = $size->colors()->first();
        $product2 = $this->createProduct($subcategory, $brand);

        Livewire::test(AddCartItemSize::class, ['product' => $product1])
            ->set('options', [
                'color' => $color->name,
                'size' => $size->name
            ])
            ->call('addItem', $product1)
            ->assertStatus(200);
        $this->assertEquals($product1->id, Cart::content()->first()->id);
        $this->assertNotEquals($product2->id, Cart::content()->first()->id);
        $this->assertTrue(Cart::content()->first()->options['color'] == $color->name);
        $this->assertTrue(Cart::content()->first()->options['size'] == $size->name);
    }

    /** @test */
    public function it_shows_the_added_items_when_clicking_the_shopping_cart()
    {

        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category);
        $brand = $this->createBrand($category);

        $product = $this->createProduct($subcategory, $brand);

        $productColor = $this->createProduct($subcategory, $brand, 2, 2, false, true);
        $color = $productColor->colors()->first();

        $productSize = $this->createProduct($subcategory, $brand, 2, 2, true, true);
        $size = $productSize->sizes()->first();
        $color = $size->colors()->first();

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('addItem', $product)
            ->assertStatus(200);

        Livewire::test(AddCartItemColor::class, ['product' => $productColor])
            ->set('options', ['color' => $color->name])
            ->call('addItem', $productColor)
            ->assertStatus(200);

        Livewire::test(AddCartItemSize::class, ['product' => $productSize])
            ->set('options', [
                'color' => $color->name,
                'size' => $size->name
            ])
            ->call('addItem', $productSize)
            ->assertStatus(200);

        Livewire::test(DropdownCart::class)
            ->call('render')
            ->assertStatus(200)
            //Producto sin color ni talla
            ->assertSee($product->name)
            ->assertSee($product->quantity)
            //Producto con color
            ->assertSee($productColor->name)
            ->assertSee(ucfirst($color->name))
            ->assertSee($color->quantity)
            //Producto con color y talla
            ->assertSee($productSize->name)
            ->assertSee($size->name)
            ->assertSee($size->colors()->pluck('quantity')->first());
    }

    /** @test */
    public function the_number_in_the_red_circle_increments_when_an_item_is_added_to_the_shopping_cart()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category);
        $brand = $this->createBrand($category);

        $product = $this->createProduct($subcategory, $brand);
        $product2 = $this->createProduct($subcategory, $brand);

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('addItem', $product)
            ->assertStatus(200);
        $this->assertEquals($product->id, Cart::content()->first()->id);

        Livewire::test(DropdownCart::class)
            ->call('render')
            ->assertStatus(200)
            ->assertSee(Cart::count());
        $this->assertEquals(Cart::count(), 1);

        Livewire::test(AddCartItem::class, ['product' => $product2])
            ->call('addItem', $product2)
            ->assertStatus(200);
        $this->assertEquals($product2->id, Cart::content()->last()->id);

        Livewire::test(DropdownCart::class)
            ->call('render')
            ->assertStatus(200)
            ->assertSee(Cart::count());
        $this->assertEquals(Cart::count(), 2);
    }

    /** @test */
    public function you_cannot_add_more_qty_than_the_one_in_stock_with_product_without_color_or_size()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category);
        $brand = $this->createBrand($category);

        $product = $this->createProduct($subcategory, $brand, 5);

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->set('qty', 3)
            ->call('addItem', $product)
            ->set('qty', 3)
            ->call('addItem', $product);
        $this->assertEquals($product->id, Cart::content()->first()->id);
        $this->assertEquals(Cart::content()->first()->qty, 5);
    }

    /** @test */
    public function you_cannot_add_more_qty_than_the_one_in_stock_with_product_with_color()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category);
        $brand = $this->createBrand($category);

        $productColor = $this->createProduct($subcategory, $brand, 5, 2, false, true);
        $color = $productColor->colors()->first();

        Livewire::test(AddCartItemColor::class, ['product' => $productColor])
            ->set('options', ['color' => $color->name])
            ->set('qty', 3)
            ->call('addItem', $productColor)
            ->set('options', ['color' => $color->name])
            ->set('qty', 3)
            ->call('addItem', $productColor);
        $this->assertEquals($productColor->id, Cart::content()->first()->id);
        $this->assertEquals(Cart::content()->first()->qty, 5);
    }

    /** @test */
    public function you_cannot_add_more_qty_than_the_one_in_stock_with_product_with_color_and_size()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category);
        $brand = $this->createBrand($category);

        $productSize = $this->createProduct($subcategory, $brand, 5, 2, true, true);
        $size = $productSize->sizes()->first();
        $color = $size->colors()->first();

        Livewire::test(AddCartItemSize::class, ['product' => $productSize])
            ->set('options', [
                'color' => $color->name,
                'size' => $size->name
            ])
            ->set('qty', 3)
            ->call('addItem', $productSize)
            ->set('options', [
                'color' => $color->name,
                'size' => $size->name
            ])
            ->set('qty', 3)
            ->call('addItem', $productSize);
        $this->assertEquals($productSize->id, Cart::content()->first()->id);
        $this->assertEquals(Cart::content()->first()->qty, 5);
    }

    /** @test */
    public function it_shows_the_stock_of_a_product_without_color_size()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category);
        $brand = $this->createBrand($category);

        $product = $this->createProduct($subcategory, $brand, 5);

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->assertSee($product->quantity);
    }

    /** @test */
    public function it_shows_the_stock_of_a_product_with_color()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category, true);
        $brand = $this->createBrand($category);

        $productColor = $this->createProduct($subcategory, $brand, 5, 2, false, true);
        $color1 = $productColor->colors()->first();
        $color2 = $this->createColor();

        $productColor->colors()->attach($color2->id, [
            'quantity' => 7
        ]);

        Livewire::test(AddCartItemColor::class, ['product' => $productColor])
            ->assertSee(12) //La suma de los productos de un color y otro
            ->set(['color_id' => ucfirst($color1->id)])
            ->assertSee(ucfirst($color1->name))
            ->assertSee($color1->quantity)
            ->set(['color_id' => ucfirst($color2->id)])
            ->assertSee(ucfirst($color2->name))
            ->assertSee($color2->quantity);
    }

    /** @test */
    public function it_shows_the_stock_of_a_product_with_color_and_size()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category, true, true);
        $brand = $this->createBrand($category);

        $productSize = $this->createProduct($subcategory, $brand, 5, 2, true, true);
        $size = $productSize->sizes()->first();
        $color1 = $size->colors()->first();
        $color2 = $this->createColor();


        $size->colors()->attach($color2->id, [
            'quantity' => 7
        ]);

        Livewire::test(AddCartItemSize::class, ['product' => $productSize])
            ->assertStatus(200)
            ->assertSee(12)
            ->set([
                'size_id' => $size->id,
                'color_id' => $color1->id,
            ])
            ->assertSee(ucfirst($color1->name))
            ->assertSee($color1->quantity)
            ->set([
                'size_id' => $size->id,
                'color_id' => $color2->id,
            ])
            ->assertSee(ucfirst($color2->name))
            ->assertSee($color2->quantity);
    }
}
