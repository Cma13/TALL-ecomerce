<?php

namespace Tests\Feature;

use App\Http\Livewire\Admin\ColorProduct;
use App\Http\Livewire\Admin\ColorSize;
use App\Http\Livewire\Admin\CreateProduct;
use App\Http\Livewire\Admin\EditProduct;
use App\Http\Livewire\Admin\ShowProducts;
use App\Http\Livewire\Admin\SizeProduct;
use App\Models\Product;
use App\Models\Size;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;
use Tests\CreateData;
use Illuminate\Support\Str;

class AdminTest extends TestCase
{
    use RefreshDatabase, CreateData;

    /** @test */
    public function the_searchbar_in_the_admin_pages_works()
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
        $product2 = $this->createProduct($subcategory, $brand);
        $product->name = 'Xbox';
        $product->save(); //Para actualizar el producto en la bbdd

        Livewire::test(ShowProducts::class)
            ->set('search', 'bo')
            ->assertSee($product->name)
            ->assertDontSee($product2->name);
    }

    /** @test */
    public function you_can_create_a_product_without_color_or_size_if_you_are_an_admin()
    {
        $user = $this->createAdminUser();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category);
        $brand = $this->createBrand($category);

        $product = $this->createTestProduct();

        Livewire::test(CreateProduct::class)
            ->set([
                'category_id' => $category->id,
                'subcategory_id' => $subcategory->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'description' => $product->description,
                'brand_id' => $brand->id,
                'price' => $product->price,
                'quantity' => 5
            ])
            ->call('save');

        $productCreated = Product::where('id', 1)->first();

        Livewire::test(EditProduct::class, ['product' => $productCreated])
            ->assertSee($category->name)
            ->assertSee($subcategory->name)
            ->assertSet('product.name', $product->name)
            ->assertSet('product.description', $product->description)
            ->assertSee($brand->name)
            ->assertSet('product.price', $product->price);
    }

    /** @test */
    public function you_can_create_a_product_with_color_if_you_are_an_admin()
    {
        $user = $this->createAdminUser();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category, true);
        $brand = $this->createBrand($category);

        $color = $this->createColor();

        $product = new Product([
            'name' => 'Producto de Prueba',
            'slug' => Str::slug('Producto de Prueba'),
            'description' => 'descripcion producto de prueba',
            'price' => 19.99
        ]);

        Livewire::test(CreateProduct::class)
            ->set([
                'category_id' => $category->id,
                'subcategory_id' => $subcategory->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'description' => $product->description,
                'brand_id' => $brand->id,
                'price' => $product->price,
            ])
            ->call('save');

        $productCreated = Product::where('id', 1)->first();

        Livewire::test(EditProduct::class, ['product' => $productCreated])
            ->assertSee($category->name)
            ->assertSee($subcategory->name)
            ->assertSet('product.name', $product->name)
            ->assertSet('product.description', $product->description)
            ->assertSee($brand->name)
            ->assertSet('product.price', $product->price);

        Livewire::test(ColorProduct::class, ['product' => $productCreated])
            ->set([
                'color_id' => $color->id,
                'quantity' => 5,
            ])
            ->call('save');

        Livewire::test(EditProduct::class, ['product' => $productCreated])
            ->assertSee(ucfirst($color->name))
            ->assertSee(5);
    }

    /** @test */
    public function you_can_create_a_product_with_color_and_size_if_you_are_an_admin()
    {
        $user = $this->createAdminUser();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category, true, true);
        $brand = $this->createBrand($category);

        $color = $this->createColor();
        $size = new Size([
            'name' => 'Talla de prueba',
        ]);

        $product = new Product([
            'name' => 'Producto de Prueba',
            'slug' => Str::slug('Producto de Prueba'),
            'description' => 'descripcion producto de prueba',
            'price' => 19.99
        ]);

        Livewire::test(CreateProduct::class)
            ->set([
                'category_id' => $category->id,
                'subcategory_id' => $subcategory->id,
                'name' => $product->name,
                'slug' => $product->slug,
                'description' => $product->description,
                'brand_id' => $brand->id,
                'price' => $product->price,
            ])
            ->call('save');

        $productCreated = Product::where('id', 1)->first();

        Livewire::test(EditProduct::class, ['product' => $productCreated])
            ->assertSee($category->name)
            ->assertSee($subcategory->name)
            ->assertSet('product.name', $product->name)
            ->assertSet('product.description', $product->description)
            ->assertSee($brand->name)
            ->assertSet('product.price', $product->price);

        Livewire::test(SizeProduct::class, ['product' => $productCreated])
            ->set([
                'name' => $size->name,
            ])
            ->call('save')
            ->assertSee($size->name);

        $sizeCreated = Size::where('product_id', $productCreated->id)->first();

        Livewire::test(ColorSize::class, ['size' => $sizeCreated])
            ->set([
                'color_id' => $color->id,
                'quantity' => 5
            ])
            ->call('save')
            ->assertSee(ucfirst($color->name))
            ->assertSee(5);
    }
}
