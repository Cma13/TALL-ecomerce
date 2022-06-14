<?php

namespace Tests\Feature;

use App\Http\Livewire\AddCartItem;
use App\Http\Livewire\AddCartItemColor;
use App\Http\Livewire\AddCartItemSize;
use App\Http\Livewire\ShoppingCart;
use App\Http\Livewire\UpdateCartItem;
use App\Http\Livewire\UpdateCartItemColor;
use App\Http\Livewire\UpdateCartItemSize;
use App\Listeners\MergeTheCart;
use App\Models\User;
use Gloudemans\Shoppingcart\Cart as ShoppingcartCart;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Auth\Events\Login;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\CreateData;

class ShoppingCartTest extends TestCase
{
    use RefreshDatabase, CreateData;

    /** @test */
    public function it_shows_all_the_items_in_the_cart()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category);
        $brand = $this->createBrand($category);

        $product = $this->createProduct($subcategory, $brand);

        $productColor = $this->createProduct($subcategory, $brand, 2, 2, false, true);
        $color = $productColor->colors()->first();

        $productSize = $this->createProduct($subcategory, $brand, 2, 2, true, true);
        $size = $productSize->sizes()->first();
        $colorSize = $size->colors()->first();

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('addItem', $product);

        Livewire::test(AddCartItemColor::class, ['product' => $productColor])
            ->set('options', [
                'color' => $color->name,
                'colorId' => $color->id
            ])
            ->call('addItem', $productColor);

        Livewire::test(AddCartItemSize::class, ['product' => $productSize])
            ->set('options', [
                'color' => $colorSize->name,
                'colorId' => $colorSize->id,
                'size' => $size->name,
                'sizeId' => $size->id
            ])
            ->call('addItem', $productSize);

        Livewire::test(ShoppingCart::class)
            ->assertSee($product->name)
            ->assertSee($productColor->name)
            ->assertSee($productSize->name);
    }

    /** @test */
    public function it_can_change_the_qty_of_a_product_in_the_shopping_cart()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category);
        $brand = $this->createBrand($category);

        $product = $this->createProduct($subcategory, $brand);

        $productColor = $this->createProduct($subcategory, $brand, 2, 2, false, true);
        $color = $productColor->colors()->first();

        $productSize = $this->createProduct($subcategory, $brand, 2, 2, true, true);
        $size = $productSize->sizes()->first();
        $colorSize = $size->colors()->first();

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('addItem', $product);

        Livewire::test(AddCartItemColor::class, ['product' => $productColor])
            ->set('options', [
                'color' => $color->name,
                'colorId' => $color->id
            ])
            ->call('addItem', $productColor);

        Livewire::test(AddCartItemSize::class, ['product' => $productSize])
            ->set('options', [
                'color' => $colorSize->name,
                'colorId' => $colorSize->id,
                'size' => $size->name,
                'sizeId' => $size->id
            ])
            ->call('addItem', $productSize);

        $items = Cart::content();

        Livewire::test(ShoppingCart::class)
            ->assertSee($product->name)
            ->assertSee($productColor->name)
            ->assertSee($productSize->name);

        $this->assertEquals($items->first()->qty, 1);
        $this->assertEquals($items->where('id', $productColor->id)->first()->qty, 1);
        $this->assertEquals($items->last()->qty, 1);

        Livewire::test(UpdateCartItem::class, ['rowId' => $items->first()->rowId])
            ->call('increment');

        Livewire::test(UpdateCartItemColor::class, ['rowId' => $items->where('id', $productColor->id)->first()->rowId])
            ->call('increment');

        Livewire::test(UpdateCartItemSize::class, ['rowId' => $items->last()->rowId])
            ->call('increment');

        Livewire::test(ShoppingCart::class)
            ->assertSee($product->name)
            ->assertSee($productColor->name)
            ->assertSee($productSize->name);

        $this->assertEquals($items->first()->qty, 2);
        $this->assertEquals($items->where('id', $productColor->id)->first()->qty, 2);
        $this->assertEquals($items->last()->qty, 2);

        Livewire::test(UpdateCartItem::class, ['rowId' => $items->first()->rowId])
            ->call('decrement');

        Livewire::test(UpdateCartItemColor::class, ['rowId' => $items->where('id', $productColor->id)->first()->rowId])
            ->call('decrement');

        Livewire::test(UpdateCartItemSize::class, ['rowId' => $items->last()->rowId])
            ->call('decrement');

        Livewire::test(ShoppingCart::class)
            ->assertSee($product->name)
            ->assertSee($productColor->name)
            ->assertSee($productSize->name);

        $this->assertEquals($items->first()->qty, 1);
        $this->assertEquals($items->where('id', $productColor->id)->first()->qty, 1);
        $this->assertEquals($items->last()->qty, 1);
    }

    /** @test */
    public function it_can_delete_an_item_in_the_shopping_cart()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category);
        $brand = $this->createBrand($category);

        $product = $this->createProduct($subcategory, $brand);

        $productColor = $this->createProduct($subcategory, $brand, 2, 2, false, true);
        $color = $productColor->colors()->first();

        $productSize = $this->createProduct($subcategory, $brand, 2, 2, true, true);
        $size = $productSize->sizes()->first();
        $colorSize = $size->colors()->first();

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('addItem', $product);

        Livewire::test(AddCartItemColor::class, ['product' => $productColor])
            ->set('options', [
                'color' => $color->name,
                'colorId' => $color->id
            ])
            ->call('addItem', $productColor);

        Livewire::test(AddCartItemSize::class, ['product' => $productSize])
            ->set('options', [
                'color' => $colorSize->name,
                'colorId' => $colorSize->id,
                'size' => $size->name,
                'sizeId' => $size->id
            ])
            ->call('addItem', $productSize);

        $items = Cart::content();

        Livewire::test(ShoppingCart::class)
            ->assertSee($product->name)
            ->assertSee($productColor->name)
            ->assertSee($productSize->name)
            ->call('delete', $items->first()->rowId)
            ->assertDontSee($product->name);
    }

    /** @test */
    public function it_can_destroy_the_shopping_cart()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category);
        $brand = $this->createBrand($category);

        $product = $this->createProduct($subcategory, $brand);

        $productColor = $this->createProduct($subcategory, $brand, 2, 2, false, true);
        $color = $productColor->colors()->first();

        $productSize = $this->createProduct($subcategory, $brand, 2, 2, true, true);
        $size = $productSize->sizes()->first();
        $colorSize = $size->colors()->first();

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('addItem', $product);

        Livewire::test(AddCartItemColor::class, ['product' => $productColor])
            ->set('options', [
                'color' => $color->name,
                'colorId' => $color->id
            ])
            ->call('addItem', $productColor);

        Livewire::test(AddCartItemSize::class, ['product' => $productSize])
            ->set('options', [
                'color' => $colorSize->name,
                'colorId' => $colorSize->id,
                'size' => $size->name,
                'sizeId' => $size->id
            ])
            ->call('addItem', $productSize);

        Livewire::test(ShoppingCart::class)
            ->assertSee($product->name)
            ->assertSee($productColor->name)
            ->assertSee($productSize->name)
            ->call('destroy')
            ->assertDontSee($product->name)
            ->assertDontSee($productColor->name)
            ->assertDontSee($productSize->name)
            ->assertSee('TU CARRITO DE COMPRAS ESTÁ VACÍO');
    }

    /** @test */
    public function the_shopping_cart_is_saved_in_the_database_when_the_user_logouts()
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();

        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category);
        $brand = $this->createBrand($category);

        $product = $this->createProduct($subcategory, $brand);
        $product2 = $this->createProduct($subcategory, $brand);

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('addItem', $product);

        Livewire::test(AddCartItem::class, ['product' => $product2])
            ->call('addItem', $product2);

        $cartBefore = Cart::content();

        $this->post(route('logout'));

        $this->assertDatabaseCount('shoppingcart', 1);

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $listener = new MergeTheCart; //Creamos el listener
        $event = new Login('web', $user, true); //Creamos el evento para que el listener lo pueda recibir
        $this->actingAs($user);
        $listener->handle($event); //Se ejecuta el evento

        $cartAfter = Cart::content();

        $this->assertEquals($cartBefore, $cartAfter);
    }
}
