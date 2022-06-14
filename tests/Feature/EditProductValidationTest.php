<?php

namespace Tests\Feature;

use App\Http\Livewire\Admin\EditProduct;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\CreateData;
use Illuminate\Support\Str;

class EditProductValidationTest extends TestCase
{
    use RefreshDatabase, CreateData;

    /** @test */
    public function the_required_validations_for_editing_a_product_works()
    {
        $user = $this->createAdminUser();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category);
        $brand = $this->createBrand($category);

        $product = $this->createProduct($subcategory, $brand);

        Livewire::test(EditProduct::class, ['product' => $product])
            ->set([
                'category_id' => '',
                'product.subcategory_id' => '',
                'product.name' => '',
                'product.slug' => '',
                'product.description' => '',
                'product.brand_id' => '',
                'product.price' => null,
                'product.quantity' => null,
            ])
            ->call('save')
            ->assertHasErrors([
                'category_id' => 'required',
                'product.subcategory_id' => 'required',
                'product.name' => 'required',
                'product.slug' => 'required',
                'product.description' => 'required',
                'product.brand_id' => 'required',
                'product.price' => 'required',
            ]);
    }

    /** @test */
    public function the_required_validations_for_editing_a_product_without_color_or_size_works()
    {
        $user = $this->createAdminUser();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category);
        $brand = $this->createBrand($category);

        $product = $this->createProduct($subcategory, $brand);

        Livewire::test(EditProduct::class, ['product' => $product])
            ->set([
                'product.quantity' => null
            ])
            ->call('save')
            ->assertHasErrors([
                'product.quantity' => 'required'
            ]);
    }

    /** @test */
    public function the_slug_of_a_product_must_be_unique_when_editing()
    {
        $user = $this->createAdminUser();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertAuthenticated();

        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category);
        $brand = $this->createBrand($category);

        $product = $this->createProduct($subcategory, $brand);
        $product->name = 'Producto de Prueba';
        $product->slug = Str::slug('Producto de Prueba');
        $product->save();

        $product2 = $this->createProduct($subcategory, $brand);
        $product2->slug = Str::slug('Producto de Prueba2');
        $product2->save();

        Livewire::test(EditProduct::class, ['product' => $product])
            ->set([
                'product.slug' => $product2->slug
            ])
            ->call('save')
            ->assertHasErrors([
                'product.slug' => 'unique'
            ]);
    }

    /** @test */
    public function the_price_of_a_product_must_be_numeric_when_editing()
    {
        $user = $this->createAdminUser();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertAuthenticated();

        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category);
        $brand = $this->createBrand($category);

        $product = $this->createProduct($subcategory, $brand);

        Livewire::test(EditProduct::class, ['product' => $product])
            ->set([
                'product.price' => 'text'
            ])
            ->call('save')
            ->assertHasErrors([
                'product.price' => 'numeric'
            ]);
    }

    /** @test */
    public function the_quantity_of_a_product_without_color_or_size_must_be_numeric_when_editing()
    {
        $user = $this->createAdminUser();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category);
        $brand = $this->createBrand($category);

        $product = $this->createProduct($subcategory, $brand);

        Livewire::test(EditProduct::class, ['product' => $product])
            ->set([
                'product.quantity' => 'text'
            ])
            ->call('save')
            ->assertHasErrors([
                'product.quantity' => 'numeric'
            ]);
    }
}
