<?php

namespace App\Http\Livewire\Admin;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Subcategory;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class EditProduct extends Component
{
    public $product, $categories, $subcategories, $brands;
    public $category_id;

    protected $rules = [
        'category_id' => 'required',
        'product.subcategory_id' => 'required',
        'product.name' => 'required',
        'product.slug' => 'required|unique:products,slug',
        'product.description' => 'required',
        'product.brand_id' => 'required',
        'product.price' => 'required|numeric',
        'product.quantity' => 'numeric'
    ];

    public function updatedCategoryId($value)
    {
        $this->subcategories = Subcategory::where('category_id', $value)->get();

        $this->brands = Brand::whereHas('categories', function (Builder $query) use ($value) {
            $query->where('category_id', $value);
        })->get();
        // $this->reset(['subcategory_id', 'brand_id']);
        $this->product->subcategory_id = '';
        $this->product->brand_id = '';
    }

    public function getSubcategoryProperty()
    {
        return Subcategory::find($this->product->subcategory_id);
    }

    public function updatedProductName($value)
    {
        $this->product->slug = Str::slug($value);
    }

    public function save()
    {
        $rules = $this->rules;
        $rules['product.slug'] = 'required|unique:products,slug,' . $this->product->id;


        if ($this->product->subcategory_id) {
            if (!$this->subcategory->color && !$this->subcategory->size) {
                $rules['product.quantity'] = 'required|numeric';
            }
        }
        
        $this->validate($rules);

        $this->product->save();

        $this->emit('saved');

    }

    public function mount(Product $product)
    {
        $product = $this->product;

        $this->categories = Category::all();

        $this->category_id = $product->subcategory->category->id;
    
        $this->subcategories = Subcategory::where('category_id', $this->category_id)->get();

        $this->brands = Brand::whereHas('categories', function (Builder $query){
            $query->where('category_id', $this->category_id);
        })->get();
    }

    public function render()
    {
        return view('livewire.admin.edit-product')->layout('layouts.admin');
    }
}
