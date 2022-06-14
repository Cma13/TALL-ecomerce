<?php

namespace Tests;

use App\Models\Brand;
use App\Models\Category;
use App\Models\City;
use App\Models\Color;
use App\Models\Department;
use App\Models\District;
use App\Models\Image;
use App\Models\Product;
use App\Models\Size;
use App\Models\Subcategory;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;

trait CreateData
{
    public function createCategory()
    {
        return Category::factory()->create();
    }

    public function createSubcategory($category, $color = false, $size = false)
    {
        return Subcategory::factory()->create([
            'category_id' => $category->id,
            'color' => $color,
            'size' => $size
        ]);
    }

    public function createBrand($category)
    {
        $brand = Brand::factory()->create();
        $category->brands()->attach($brand->id);
        return $brand;
    }

    public function createUser($name)
    {
        return User::factory()->create(['name' => $name]);
    }

    public function createColor()
    {
        return Color::factory()->create();
    }

    public function createSize($product)
    {
        return Size::factory()->create(['product_id' => $product->id]);
    }

    public function createProduct($subcategory, $brand, $quantity = 5, $status = 2, $size = false, $color = false, $images = 1)
    {
        $product = Product::factory()->create([
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand->id,
            'quantity' => $quantity,
            'status' => $status
        ]);

        if ($color && $size) {
            $product->quantity = null;
            $productColor = $this->createColor();
            $productSize = $this->createSize($product);
            $productSize->colors()->attach($productColor->id, ['quantity' => $quantity]);
        } elseif ($color && !$size) {
            $product->quantity = null;
            $productColor = $this->createColor();
            $product->colors()->attach($productColor->id, ['quantity' => $quantity]);
        }

        Image::factory($images)->create([
            'imageable_id' => $product->id,
            'imageable_type' => Product::class
        ]);

        return $product;
    }

    public function createDepartment()
    {
        return Department::factory()->create();
    }

    public function createCity($name, $department)
    {
        return City::factory()->create([
            'name' => $name,
            'department_id' => $department->id
        ]);
    }

    public function createDistrict($name, $city)
    {
        return District::factory()->create([
            'name' => $name,
            'city_id' => $city->id
        ]);
    }

    public function createDefault()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category);
        $brand = $this->createBrand($category);
        $this->createUser('Prueba');
        $product = $this->createProduct($subcategory, $brand);
    }

    public function createAdminUser()
    {
        $admin = Role::create(['name' => 'admin']);
        $user = User::factory()->create()->assignRole($admin);

        return $user;
    }

    public function createTestProduct()
    {
        $product = new Product([
            'name' => 'Producto de Prueba',
            'slug' => Str::slug('Producto de Prueba'),
            'description' => 'descripcion producto de prueba',
            'price' => 19.99
        ]);

        return $product;
    }
}
