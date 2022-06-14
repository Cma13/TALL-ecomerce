<?php

namespace Tests\Feature;

use App\Http\Livewire\Admin\ColorProduct;
use App\Http\Livewire\Admin\ColorSize;
use App\Http\Livewire\Admin\CreateProduct;
use App\Http\Livewire\Admin\SizeProduct;
use App\Models\Product;
use App\Models\Size;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Tests\TestCase;
use Tests\CreateData;
use Illuminate\Support\Str;
use Livewire\Livewire;

class CreateProductValidationTest extends TestCase
{
    use RefreshDatabase, CreateData;

    /** @test */
    public function the_required_validations_for_creating_a_product_works()
    {
        $user = $this->createAdminUser();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertAuthenticated();

        $category = $this->createCategory();
        $this->createSubcategory($category);
        $this->createBrand($category);

        Livewire::test(CreateProduct::class)
            ->call('save')
            ->assertHasErrors([
                'category_id' => 'required',
                'subcategory_id' => 'required',
                'name' => 'required',
                'slug' => 'required',
                'description' => 'required',
                'brand_id' => 'required',
                'price' => 'required',
            ]);
    }

    /** @test */
    public function the_quantity_validation_when_a_product_has_no_color_or_size_works()
    {
        $user = $this->createAdminUser();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertAuthenticated();

        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category);
        $this->createBrand($category);

        Livewire::test(CreateProduct::class)
            ->set([
                'subcategory_id' => $subcategory->id,
            ])
            ->call('save')
            ->assertHasErrors([
                'category_id' => 'required',
                'name' => 'required',
                'slug' => 'required',
                'description' => 'required',
                'brand_id' => 'required',
                'price' => 'required',
                'quantity' => 'required',
            ]);
    }

    /** @test */
    public function the_quantity_of_a_product_with_no_color_or_size_must_be_numeric()
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
                'quantity' => 'text'
            ])
            ->call('save')
            ->assertHasErrors([
                'quantity' => 'numeric',
            ]);
    }

    /** @test */
    public function the_slug_of_a_product_must_be_unique()
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

        $testProduct = new Product([
            'name' => 'Producto de Prueba',
            'slug' => Str::slug('Producto de Prueba'),
            'description' => 'descripcion producto de prueba',
            'price' => 19.99
        ]);

        Livewire::test(CreateProduct::class)
            ->set([
                'category_id' => $category->id,
                'subcategory_id' => $subcategory->id,
                'name' => $testProduct->name,
                'slug' => $testProduct->slug,
                'description' => $testProduct->description,
                'brand_id' => $brand->id,
                'price' => $testProduct->price,
            ])
            ->call('save')
            ->assertHasErrors([
                'slug' => 'unique:products'
            ]);
    }

    /** @test */
    public function the_price_of_a_product_must_be_numeric()
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

        $product = new Product([
            'name' => 'Producto de Prueba',
            'slug' => Str::slug('Producto de Prueba'),
            'description' => 'descripcion producto de prueba',
            'price' => 'text'
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
            ->call('save')
            ->assertHasErrors([
                'price' => 'numeric',
            ]);
    }

    /** @test */
    public function the_required_validations_when_a_product_has_color_works()
    {
        $user = $this->createAdminUser();

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);
        $this->assertAuthenticated();

        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category, true);
        $brand = $this->createBrand($category);

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

        Livewire::test(ColorProduct::class, ['product' => $productCreated])
            ->call('save')
            ->assertHasErrors([
                'color_id' => 'required',
                'quantity' => 'required',
            ]);
    }

    /** @test */
    public function the_quantity_of_a_product_with_color_must_be_numeric()
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

        Livewire::test(ColorProduct::class, ['product' => $productCreated])
            ->set([
                'color_id' => $color->id,
                'quantity' => 'text',
            ])
            ->call('save')
            ->assertHasErrors([
                'quantity' => 'numeric',
            ]);
    }

    /** @test */
    public function the_required_validations_when_a_product_has_color_and_size_works()
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

        Livewire::test(SizeProduct::class, ['product' => $productCreated])
            ->call('save')
            ->assertHasErrors([
                'name' => 'required'
            ]);
    }

    /** @test */
    public function the_required_validations_for_color_when_a_product_has_color_and_size_works()
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

        Livewire::test(SizeProduct::class, ['product' => $productCreated])
            ->set([
                'name' => $size->name,
            ])
            ->call('save')
            ->assertSee($size->name);

        $sizeCreated = Size::where('product_id', $productCreated->id)->first();

        Livewire::test(ColorSize::class, ['size' => $sizeCreated])
            ->call('save')
            ->assertHasErrors([
                'color_id' => 'required',
                'quantity' => 'required',
            ]);
        }

    /** @test */
    public function the_quantity_of_a_product_with_color_and_size_must_be_numeric()
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
                'quantity' => 'text'
            ])
            ->call('save')
            ->assertHasErrors([
                'quantity' => 'numeric',
            ]);
        }
}
