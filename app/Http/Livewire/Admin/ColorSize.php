<?php

namespace App\Http\Livewire\Admin;

use App\Models\Color;
use App\Models\ColorSize as CSPivot;
use Livewire\Component;

class ColorSize extends Component
{
    public $size, $colors, $quantity, $pivot, $open = false;
    public $color_id, $pivot_color_id, $pivot_quantity;

    protected $rules = [
        'color_id' => 'required',
        'quantity' => 'required|numeric',
    ];

    protected $listeners = ['delete'];

    public function mount()
    {
        $this->colors = Color::all();
    }

    public function save()
    {
        $this->validate();

        $pivot = CSPivot::where('color_id', '=', $this->color_id)
            ->where('size_id', '=', $this->size->id)
            ->first();

        if ($pivot) {
            $pivot->quantity = $pivot->quantity + $this->quantity;
            $pivot->save();
        } else {
            $this->size->colors()->attach([
                $this->color_id => [
                    'quantity' => $this->quantity,
                ]
            ]);
        }

        $this->reset(['color_id', 'quantity']);
        $this->emit('saved');

        $this->size = $this->size->fresh();
    }

    public function edit(CSPivot $pivot)
    {
        $this->open = true;

        $this->pivot = $pivot;
        $this->pivot_color_id = $pivot->color_id;
        $this->pivot_quantity = $pivot->quantity;
    }

    public function update()
    {
        $this->pivot->color_id = $this->pivot_color_id;
        $this->pivot->quantity = $this->pivot_quantity;

        $this->pivot->save();

        $this->size = $this->size->fresh();

        $this->reset('open');
    }

    public function delete(CSPivot $pivot)
    {
        $pivot->delete();
        $this->size = $this->size->fresh();
    }

    public function render()
    {
        $sizeColors = $this->size->colors;

        return view('livewire.admin.color-size', compact('sizeColors'));
    }
}
