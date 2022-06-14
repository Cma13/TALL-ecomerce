<?php

namespace App\Http\Livewire\Admin;

use App\Models\ColorSize;
use App\Models\Size;
use Livewire\Component;

class ShowSizeColorsProduct extends Component
{
    public $product, $sizes, $colors;

    public function mount()
    {
        $this->sizes = Size::where('product_id', $this->product->id)->get();
        $this->colors = array_unique($this->sizes->pluck('colors')->collapse()->pluck('name')->all());
    }

    public function render()
    {
        return view('livewire.admin.show-size-colors-product');
    }
}
