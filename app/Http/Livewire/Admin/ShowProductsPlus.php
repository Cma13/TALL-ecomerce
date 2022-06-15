<?php

namespace App\Http\Livewire\Admin;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use App\Models\Subcategory;
use App\ProductFilter;
use Illuminate\Support\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class ShowProductsPlus extends Component
{
    use WithPagination;

    public $search, $pages = 9;
    public $columns = ['Nombre', 'Categoría', 'Subcategoría', 'Marca', 'Fecha de creación', 'Estado', 'Colores', 'Tallas', 'Stock', 'Precio', 'Vendidos', 'Sin Confirmar'];
    public $selectedColumns = [];
    public $sortColumn = 'products.name';
    public $sortDirection = 'asc';
    public $selectedCategories = [];
    public $selectedSubcateries = [];
    public $selectedBrands = [];
    public $minPriceFilter = 0;
    public $maxPriceFilter = 100;
    public $fromFilter;
    public $toFilter;
    public $selectedColors = [];
    public $selectedSizes = [];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updatedPages($value)
    {
        $this->pages = $value;
    }

    public function updatedMinPriceFilters($value)
    {
        $this->minPriceFilters = $value;
    }

    public function updatedMaxPriceFilters($value)
    {
        $this->maxPriceFilter = $value;
    }

    public function updatedFromFilter($value)
    {
        $this->fromFilter = $value;
    }

    public function updatedToFilter($value)
    {
        $this->toFilter = $value;
    }

    public function showColumn($column)
    {
        return in_array($column, $this->selectedColumns);
    }

    public function sortColumns($column)
    {
        $this->sortColumn = ProductFilter::transSortColumn($column);

        if ($this->sortDirection === 'asc') {
            $this->sortDirection = 'desc';
        } elseif ($this->sortDirection === 'desc') {
            $this->sortDirection = 'asc';
        }
    }

    public function getCategories()
    {
        $this->selectedCategories = Category::all()->pluck('id');
    }

    public function getSubcategories() 
    {
        $this->selectedSubcategories = Subcategory::all()->pluck('id');
    }

    public function getBrands()
    {
        $this->selectedBrands = Brand::all()->pluck('id');
    }

    public function isColored($column = 'Nombre')
    {
        $column = ProductFilter::transSortColumn($column);

        if ($column === $this->sortColumn) {
            return true;
        }
    }

    public function show($column)
    {
        if ($column === 'Colores' || $column === 'Tallas' || $column === 'Estado' || $column === 'Stock') {
            return 'hidden';
        }
    }

    protected function getProducts(ProductFilter $productFilter)
    {
        $products = Product::query()
            ->filterBy($productFilter, [
                'search' => $this->search,
                'order' => [$this->sortColumn, $this->sortDirection],
                'categories' =>  $this->selectedCategories,
                'subcategories' => $this->selectedSubcategories,
                'brands' => $this->selectedBrands,
                'prices' => [$this->minPriceFilter, $this->maxPriceFilter],
                'dates' => [$this->fromFilter, $this->toFilter],
                'colors' => $this->selectedColors,
                'sizes' => $this->selectedSizes
            ])
            ->paginate($this->pages);

        $products->appends($productFilter->valid());

        return $products;
    }

    public function mount()
    {
        $this->selectedColumns = $this->columns;

        $this->getCategories();
        $this->getSubcategories();
        $this->getBrands();
    }

    public function render(ProductFilter $productFilter)
    {
        return view('livewire.admin.show-products-plus', [
            'products' => $this->getProducts($productFilter),
            'categories' => Category::all(),
            'subcategories' => Subcategory::all(),
            'brands' => Brand::all(),
            'colors' => Color::all(),
            'sizes' => Size::all()->pluck('name')->unique(),
        ])->layout('layouts.admin');
    }
}
