<?php

namespace Tests\Feature;

use App\Http\Livewire\AddCartItem;
use App\Http\Livewire\AddCartItemColor;
use App\Http\Livewire\AddCartItemSize;
use App\Http\Livewire\CreateOrder;
use App\Http\Livewire\PaymentOrder;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\CreateData;

class CreateOrderTest extends TestCase
{
    use RefreshDatabase, CreateData;

    /** @test */
    public function it_redirects_to_login_page_when_trying_to_create_an_order_unauthenticated()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category);
        $brand = $this->createBrand($category);

        $product = $this->createProduct($subcategory, $brand);

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('addItem', $product);

        $response = $this->get('orders/create');
        $response->assertRedirect('login');
    }

    /** @test */
    public function you_can_create_an_order_when_authenticated()
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

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('addItem', $product);

        $response = $this->get('orders/create');
        $response->assertStatus(200)
            ->assertSee($product->name);
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

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('addItem', $product);

        $this->post(route('logout'));

        $this->assertDatabaseCount('shoppingcart', 1);
    }

    /** @test */
    public function it_shows_the_dropdown_when_selecting_home_delivery()
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

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('addItem', $product);

        Livewire::test(CreateOrder::class)
            ->set('envio_type', 2)
            ->assertSee('Departamento')
            ->assertSee('Ciudad')
            ->assertSee('Distrito')
            ->assertSee('Dirección')
            ->assertSee('Referencia');
    }

    /** @test */
    public function it_creates_the_order_and_redirects_to_the_correct_page()
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

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('addItem', $product);

        Livewire::test(CreateOrder::class)
            ->set([
                'contact' => 'Carlos',
                'phone' => '633622744'
            ])
            ->call('create_order');

        $response = $this->get('orders/1/payment');
        $response->assertStatus(200)
            ->assertSee($product->name)
            ->assertSee('Carlos')
            ->assertSee('633622744');
        $this->assertEmpty(Cart::content());
    }

    /** @test */
    public function the_dropdowns_are_updated_correctly_according_to_the_previous_one()
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

        $department = $this->createDepartment();
        $city = $this->createCity('CiudadPrueba', $department);
        $district = $this->createDistrict('DistritoPrueba', $city);

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('addItem', $product);

        Livewire::test(CreateOrder::class)
            ->set('envio_type', 2)
            ->assertSee('Departamento')
            ->assertSee($department->name)
            ->assertDontSee($city->name)
            ->assertDontSee($district->name)
            ->set('department_id', $department->id)
            ->assertSee($city->name)
            ->assertDontSee($district->name)
            ->set('city_id', $city->id)
            ->assertSee($district->name);
    }

    /** @test */
    public function you_can_access_to_your_orders_via_dropdown_menu()
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category);
        $brand = $this->createBrand($category);

        $product = $this->createProduct($subcategory, $brand);

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('addItem', $product);

        $this->get('orders/create')
            ->assertStatus(200)
            ->assertSee($product->name);

        Livewire::test(CreateOrder::class)
            ->set([
                'contact' => 'Carlos',
                'phone' => '633622744'
            ])
            ->call('create_order');

        $order = Order::query()->where('user_id', $user->id)->first();

        $this->get(route('orders.index'))
            ->assertSee('Pedidos recientes')
            ->assertSee(today()->format('d/m/Y'))
            ->assertSee($order->total);
    }

    /** @test */
    public function the_stock_changes_when_you_add_a_product_without_color_or_size_to_the_cart()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category);
        $brand = $this->createBrand($category);

        $product = $this->createProduct($subcategory, $brand);

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->assertSee($product->quantity)
            ->call('addItem', $product)
            ->assertSee($product->quantity - 1);
    }

    /** @test */
    public function the_stock_changes_when_you_add_a_product_with_color_to_the_cart()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category, true);
        $brand = $this->createBrand($category);

        $product = $this->createProduct($subcategory, $brand, 5, 2, false, true);
        $color = $product->colors()->first();
        $color2 = $this->createColor();

        $product->colors()->attach($color2->id, [
            'quantity' => 7
        ]);

        Livewire::test(AddCartItemColor::class, ['product' => $product])
            ->assertSee($product->stock)
            ->set('options', [
                'color' => $color->name,
                'colorId' => $color->id
            ])
            ->assertSee($color->pivot->quantity)
            ->call('addItem', $product)
            ->assertSee($color->pivot->quantity - 1);
    }

    /** @test */
    public function the_stock_changes_when_you_add_a_product_with_color_and_size_to_the_cart()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category, true, true);
        $brand = $this->createBrand($category);

        $product = $this->createProduct($subcategory, $brand, 2, 2, true, true);
        $size = $product->sizes()->first();
        $color = $size->colors()->first();
        $color2 = $this->createColor();


        $size->colors()->attach($color2->id, [
            'quantity' => 7
        ]);

        Livewire::test(AddCartItemSize::class, ['product' => $product])
            ->assertSee($product->stock)
            ->set('options', [
                'color' => $color->name,
                'colorId' => $color->id,
                'size' => $size->name,
                'sizeId' => $size->id
            ])
            ->assertSee($size->colors->first()->pivot->quantity)
            ->call('addItem', $product)
            ->assertSee($size->colors->first()->pivot->quantity - 1);
    }

    /** @test */
    public function the_stock_changes_in_the_database_when_an_order_is_created_with_a_product_without_color_or_size()
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

        $product = $this->createProduct($subcategory, $brand); //quantity = 5, de manera predeterminada en CreateData

        Livewire::test(AddCartItem::class, ['product' => $product])
            ->call('addItem', $product);

        Livewire::test(CreateOrder::class)
            ->set([
                'contact' => 'Carlos',
                'phone' => '633622744'
            ])
            ->call('create_order');

        $productDb = Product::where('id', $product->id)->first();

        $this->assertEquals($productDb->stock, 4);
    }

    /** @test */
    public function the_stock_changes_in_the_database_when_an_order_is_created_with_a_product_with_color()
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();

        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category, true);
        $brand = $this->createBrand($category);

        $product = $this->createProduct($subcategory, $brand, 5, 2, false, true); //quantity = 5, de manera predeterminada en CreateData
        $color = $product->colors()->first();

        Livewire::test(AddCartItemColor::class, ['product' => $product])
            ->set('options', [
                'color' => $color->name,
                'colorId' => $color->id
            ])
            ->call('addItem', $product);

        Livewire::test(CreateOrder::class)
            ->set([
                'contact' => 'Carlos',
                'phone' => '633622744'
            ])
            ->call('create_order');

        $productDb = Product::where('id', $product->id)->first();

        $this->assertEquals($productDb->stock, 4);
    }

    /** @test */
    public function the_stock_changes_in_the_database_when_an_order_is_created_with_a_product_with_color_and_size()
    {
        $user = User::factory()->create();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $this->assertAuthenticated();

        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category, true, true);
        $brand = $this->createBrand($category);

        $product = $this->createProduct($subcategory, $brand, 5, 2, true, true); //quantity = 5, de manera predeterminada en CreateData
        $size = $product->sizes()->first();
        $color = $size->colors()->first();

        Livewire::test(AddCartItemColor::class, ['product' => $product])
            ->set('options', [
                'color' => $color->name,
                'colorId' => $color->id,
                'size' => $size->name,
                'sizeId' => $size->id
            ])
            ->call('addItem', $product);

        Livewire::test(CreateOrder::class)
            ->set([
                'contact' => 'Carlos',
                'phone' => '633622744'
            ])
            ->call('create_order');

        $productDb = Product::where('id', $product->id)->first();

        $this->assertEquals($productDb->stock, 4);
    }
}
