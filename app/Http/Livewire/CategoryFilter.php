<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryFilter extends Component
{
    use WithPagination;

    public $category, $subcategoryFilter, $brandFilter;

    public function limpiar()
    {
        $this->reset(['subcategoryFilter', 'brandFilter']);
    }

    public function render()
    {
        $productsQuery = Product::query()->whereHas('subcategory.category', function (Builder $query) {
            $query->where('id', $this->category->id);
        });

        if ($this->subcategoryFilter) {
            $productsQuery = $productsQuery->whereHas('subcategory', function (Builder $query) {
                $query->where('name', $this->subcategoryFilter);
            });
        }

        if ($this->brandFilter) {
            $productsQuery = $productsQuery->whereHas('brand', function (Builder $query) {
                $query->where('name', $this->brandFilter);
            });
        }

        $products = $productsQuery->paginate(8);

        return view('livewire.category-filter', compact('products'));
    }
}
