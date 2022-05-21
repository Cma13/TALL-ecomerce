<?php

namespace Tests;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Image;
use App\Models\Product;
use App\Models\Size;
use App\Models\Subcategory;
use App\Models\User;

trait CreateData
{
    public function createCategory($name = false)
    {
        if ($name) {
            return Category::factory()->create(['name' => $name]);
        } else {
            return Category::factory()->create();
        }
    }

    public function createSubcategory($category, $name = false, $color = false, $size = false)
    {
        if($name) {
            return Subcategory::factory()->create([
                'category_id' => $category->id,
                'name' => $name,
                'color' => $color,
                'size' => $size
            ]);
        } else {
            return Subcategory::factory()->create([
                'category_id' => $category->id,
                'color' => $color,
                'size' => $size
            ]);
        }
        
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

    public function createColor($product)
    {
        $color = Color::factory()->create();
        $product->colors()->attach($color->id);
        return $color;
    }

    public function createSize($product)
    {
        return Size::factory()->create(['product_id' => $product->id]);
    }

    public function createProduct($subcategory, $brand, $quantity = 5, $status = 2, $size = false , $color = false, $images = 1)
    {
        $product = Product::factory()->create([
            'subcategory_id' => $subcategory->id,
            'brand_id' => $brand->id,
            'quantity' => $quantity,
            'status' => $status
        ]);

        if($color && $size) {
            $product->quantity = null;
            $productColor = $this->createColor($product);
            $productSize = $this->createSize($product);
            $productSize->colors()->attach($productColor->id, ['quantity' => $quantity]);
        } elseif ($color && !$size) {
            $product->quantity = null;
            $productColor = $this->createColor($product);
            $product->colors()->attach($productColor->id, ['quantity' => $quantity]);
        }

        Image::factory($images)->create([
            'imageable_id' => $product->id,
            'imageable_type' => Product::class
        ]);

        return $product;
    }

    public function createDefault()
    {
        $category = $this->createCategory();
        $subcategory = $this->createSubcategory($category);
        $brand = $this->createBrand($category);
        $this->createUser('Prueba');
        $product = $this->createProduct($subcategory, $brand);
    }

}