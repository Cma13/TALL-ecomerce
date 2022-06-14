<?php

namespace App\Http\Livewire\Admin;

use App\Models\ColorProduct;
use App\Models\Product;
use App\Models\Subcategory;
use Livewire\Component;

class ShowColorsProduct extends Component
{
    public $product, $products;


    public function mount()
    {
        $this->products = ColorProduct::where('product_id', $this->product->id)->get();
    }

    public function render()
    {
        return view('livewire.admin.show-colors-product');
    }
}
