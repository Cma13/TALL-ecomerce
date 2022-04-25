<?php

namespace App\Http\Livewire;

use Livewire\Component;

class AddCartItemColor extends Component
{
    public $product, $colors, $color_id = "";
    public $quantity = 0, $qty = 1;

    public function mount()
    {
        $this->colors = $this->product->colors;
    }

    public function decrement()
    {
        $this->qty = $this->qty - 1;
    }

    public function increment()
    {
        $this->qty = $this->qty + 1;
    }

    public function render()
    {
        return view('livewire.add-cart-item-color');
    }

    public function updatedColorId($colorSelected)
    {
        $this->quantity = $this->product->colors->find($colorSelected)->pivot->quantity;
    }
}
