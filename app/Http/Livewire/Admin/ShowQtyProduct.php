<?php

namespace App\Http\Livewire\Admin;

use App\Models\ColorProduct;
use App\Models\Size;
use Livewire\Component;

class ShowQtyProduct extends Component
{
    public $product;

    public function mount()
    {
        if($this->product->subcategory->size) {
            $this->sizes = Size::where('product_id', $this->product->id)->get();
            $this->product = array_sum($this->sizes->pluck('colors')->collapse()->pluck('pivot')->pluck('quantity')->all());
        } else {
            $this->product = array_sum(ColorProduct::where('product_id', $this->product->id)->pluck('quantity')->all());
        }
    }

    public function render()
    {
        return view('livewire.admin.show-qty-product');
    }
}
