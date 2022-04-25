<?php

namespace App\Http\Livewire;

use App\Models\Size;
use Livewire\Component;

class AddCartItemSize extends Component
{
    public $product, $sizes, $color_id = "";
    public $size_id = "", $colors = [];
    public $quantity = 0, $qty = 1;

    public function updatedSizeId($sizeSelected)
    {
        $size = Size::find($sizeSelected);

        $this->colors = $size->colors;
    }
    
    public function updatedColorId($colorSelected)
    {
        $size = Size::find($this->size_id);
        $this->quantity = $size->colors->find($colorSelected)->pivot->quantity;
    }

    public function decrement()
    {
        $this->qty = $this->qty - 1;
    }

    public function increment()
    {
        $this->qty = $this->qty + 1;
    }

    public function mount()
    {
        $this->sizes = $this->product->sizes;
    }

    public function render()
    {
        return view('livewire.add-cart-item-size');
    }
}
