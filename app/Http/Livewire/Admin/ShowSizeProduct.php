<?php

namespace App\Http\Livewire\Admin;

use App\Models\Size;
use Livewire\Component;

class ShowSizeProduct extends Component
{
    public $product, $sizes;

    public function mount()
    {
        $this->sizes = Size::where('product_id', $this->product->id)->get();
    }

    public function render()
    {
        return view('livewire.admin.show-size-product');
    }
}
