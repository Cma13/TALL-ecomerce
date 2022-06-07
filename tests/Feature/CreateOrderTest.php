<?php

namespace Tests\Feature;

use App\Http\Livewire\AddCartItem;
use App\Http\Livewire\CreateOrder;
use App\Http\Livewire\PaymentOrder;
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
        $city = $this->createCity('CiudadPrueba',$department);
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
}
