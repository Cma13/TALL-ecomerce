<?php

namespace Tests\Feature;

use App\Http\Livewire\AddCartItem;
use App\Http\Livewire\CreateOrder;
use App\Models\Order;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\CreateData;
use Carbon\Carbon;
use Carbon\Carbonite;

class CaducityTest extends TestCase
{
    use RefreshDatabase, CreateData;

    /** @test */
    public function it_deletes_the_order_if_it_has_not_been_confirmed_within_ten_minutes()
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

        $this->get('/')
            ->assertSee('Usted tiene 1 ordenes pendientes');
        
        $orderBefore = Order::where('id', 1)->first();

        $this->assertEquals($orderBefore->status, 1);

        $this->travel(12)->minutes();
        $this->artisan('schedule:run');

        $orderAfter = Order::where('id', 1)->first();

        $this->assertEquals($orderAfter->status, 5);
    }
}
