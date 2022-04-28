<?php

namespace App\Http\Livewire;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class AddCartItemColor extends Component
{
    public $product, $colors;
    public $qty = 1;
    public $quantity = 0;
    public $color_id = '';
    public $options = [
        'sizeId' => null,
    ];

    public function mount()
    {
        $this->colors = $this->product->colors;

        $this->options['image'] = Storage::url($this->product->images->first()->url);
    }

    public function updatedColorId($value)
    {
        $color = $this->product->colors->find($value);
        $this->quantity = qtyAvailable($this->product->id, $this->color_id);
        $this->options['color'] = $color->name;
        $this->options['colorId'] = $color->id;
    }

    public function decrement()
    {
        $this->qty--;
    }

    public function increment()
    {
        $this->qty++;
    }

    public function addItem()
    {
        Cart::add([
            'id' => $this->product->id,
            'name' => $this->product->name,
            'qty' => $this->qty,
            'price' => $this->product->price,
            'weight' => 550,
            'options' => $this->options
        ]);

        $this->quantity = qtyAvailable($this->product->id, $this->color_id);
        $this->emitTo('dropdown-cart', 'render');
        $this->reset('qty');

    }

    public function render()
    {
        return view('livewire.add-cart-item-color');
    }
}
