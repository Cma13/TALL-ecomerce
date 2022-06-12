<?php

namespace Tests\Feature;

use App\Http\Livewire\AddCartItemColor;
use App\Http\Livewire\CreateOrder;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\CreateData;

class CaducityTest extends TestCase
{
    use RefreshDatabase, CreateData;

    /** @test */
    public function it_deletes_the_order_if_it_has_not_beem_confirmed_within_ten_minutes()
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
    }
}
