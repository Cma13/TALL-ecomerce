<?php

namespace App\Http\Livewire;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\WithPagination;

class CategoryFilter extends Component
{
    public $category, $subcategoria, $marca;

    public $view = 'grid';

    use WithPagination;

    protected $queryString = ['subcategoria', 'marca'];

    public function updatedSubcategoria()
    {
        $this->resetPage();
    }

    public function updatedMarca()
    {
        $this->resetPage();
    }

    public function render()
    {
        $productsQuery = Product::query()->whereHas('subcategory.category', function(Builder $query){
            $query->where('id', $this->category->id);
        });

        if ($this->subcategoria) {
            $productsQuery = $productsQuery->whereHas('subcategory', function(Builder $query){
                $query->where('slug', $this->subcategoria);
            });
        }

        if ($this->marca) {
            $productsQuery = $productsQuery->whereHas('brand', function(Builder $query){
                $query->where('name', $this->marca);
            });
        }

        $products = $productsQuery->paginate(10);

        return view('livewire.category-filter', compact('products'));
    }

    public function limpiar()
    {
        $this->reset(['subcategoria', 'marca', 'page']);
    }
}
