<?php

namespace Tests\Feature;

use App\Http\Livewire\AddCartItem;
use App\Http\Livewire\CreateOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\CreateData;

class PoliciesTest extends TestCase
{
    use RefreshDatabase, CreateData;

    /** @test */
    public function only_the_user_who_make_the_order_can_access_to_it()
    {
        $user1 = User::factory()->create(['id' => 1]);
        $user2 = User::factory()->create(['id' => 2]);

        $this->actingAs($user1);
        $this->assertAuthenticated();

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

        $this->get('orders/1/payment')
            ->assertStatus(200);

        $this->actingAs($user2)
            ->get('orders/1/payment')
            ->assertStatus(403);

    }
}
