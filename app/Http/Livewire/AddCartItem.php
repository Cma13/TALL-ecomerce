<?php

namespace App\Http\Livewire;

use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;

class AddCartItem extends Component
{
    public $product;
    public $quantity;
    public $qty = 1;
    public $options = [
        'colorId' => null,
        'sizeId' => null,
    ];

    public function mount()
    {
        $this->quantity = qtyAvailable($this->product->id);

        $this->options['image'] = Storage::url($this->product->images->first()->url);
    }

    public function decrement()
    {
        $this->qty--;
    }

    public function increment()
    {
        $this->qty++;
    }

    public function checkQty($qty)
    {
        if ($qty >= qtyAvailable($this->product->id)) {
            return qtyAvailable($this->product->id);
        } else {
            return $qty;
        }
    }

    public function addItem()
    {
        Cart::add([
            'id' => $this->product->id,
            'name' => $this->product->name,
            'qty' => $this->checkQty($this->qty),
            'price' => $this->product->price,
            'weight' => 550,
            'options' => $this->options
        ]);

        $this->quantity = qtyAvailable($this->product->id);

        $this->reset('qty');

        $this->emitTo('dropdown-cart', 'render');
    }

    public function render()
    {
        return view('livewire.add-cart-item');
    }
}
