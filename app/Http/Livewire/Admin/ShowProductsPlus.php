<?php

namespace App\Http\Livewire\Admin;

use App\Models\Product;
use App\ProductFilter;
use Livewire\Component;
use Livewire\WithPagination;

class ShowProductsPlus extends Component
{
    use WithPagination;

    public $search, $pages = 9;
    public $columns = ['Nombre', 'Categoría', 'Subcategoría', 'Marca', 'Fecha de creación', 'Estado', 'Colores', 'Tallas', 'Stock', 'Precio'];
    public $selectedColumns = [];
    public $sortColumn = 'products.name';
    public $sortDirection = 'asc';

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPages($value)
    {
        $this->pages = $value;
    }

    public function showColumn($column)
    {
        return in_array($column, $this->selectedColumns);
    }

    public function sortProducts($sortColumn, $sortDirection)
    {
        $this->sortColumn = $sortColumn;
        $this->sortDirection = $sortDirection;
    }

    public function isColored($column = 'Nombre', $direction = 'asc')
    {
        if ($column === $this->sortColumn && $direction === $this->sortDirection) {
            return 'text-orange-600';
        }
    }

    public function show($column)
    {
        if ($column === 'Colores' || $column === 'Tallas' || $column === 'Estado' || $column === 'Stock') {
            return 'hidden';
        }
    }

    public function mount()
    {
        $this->selectedColumns = $this->columns;
    }

    public function render()
    {
        $products = ProductFilter::queryProducts($this->search, $this->pages, $this->sortColumn, $this->sortDirection);

        return view('livewire.admin.show-products-plus', compact('products'))->layout('layouts.admin');
    }
}
