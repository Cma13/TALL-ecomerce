<?php

namespace App\Http\Livewire\Admin;

use App\Models\Color;
use App\Models\ColorProduct as Pivot;
use Livewire\Component;

class ColorProduct extends Component
{
    public $product, $colors, $quantity;
    public $color_id = '', $pivot_id, $pivot_color_id, $pivot_quantity;
    public $open = false;

    protected $rules = [
        'color_id' => 'required',
        'quantity' => 'required|numeric'
    ];

    protected $listeners = ['delete'];

    public function save()
    {
        $this->validate();

        $this->product->colors()->attach([
            $this->color_id => [
                'quantity' => $this->quantity
            ]
        ]);

        $this->reset(['color_id', 'quantity']);
        $this->emit('saved');

        $this->product = $this->product->fresh();
    }

    public function edit(Pivot $pivot_id)
    {
        $this->open = true;

        $this->pivot_id = $pivot_id;
        $this->pivot_color_id = $pivot_id->color_id;
        $this->pivot_quantity = $pivot_id->quantity;
    }

    public function update()
    {
        $this->pivot_id->color_id = $this->pivot_color_id;
        $this->pivot_id->quantity = $this->pivot_quantity;

        $this->pivot_id->save();

        $this->open = false;

        $this->product = $this->product->fresh();
    }

    public function delete(Pivot $pivot)
    {
        $pivot->delete();

        $this->product = $this->product->fresh();
    }

    public function mount()
    {
        $this->colors = Color::all();
    }

    public function render()
    {
        $product_colors = $this->product->colors;

        return view('livewire.admin.color-product', compact('product_colors'));
    }
}
