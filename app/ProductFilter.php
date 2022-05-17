<?php

namespace App;

use App\Models\Product;
use Illuminate\Validation\Rule;

class ProductFilter extends QueryFilter
{
    public static function transSortColumn($column)
    {
        switch ($column) {
            case 'Nombre':
                $column = 'products.name';
                break;
            case 'Categoría':
                $column = 'categoryName';
                break;
            case 'Subcategoría':
                $column = 'subcategoryName';
                break;
            case 'Marca':
                $column = 'brandName';
                break;
            case 'Fecha de creación':
                $column = 'created_at';
                break;
            case 'Precio':
                $column = 'price';
                break;
            default:
                # code...
                break;
        }

        return $column;
    }

    public function rules(): array
    {
        return [
            'search' => 'filled',
            'order' => ['required', 'array', Rule::in(['products.name', 'categoryName', 'status', 'subcategoryName', 'brandName', 'quantity', 'colors', 'price', 'created_at', 'updated_at', 'asc', 'desc'])],
            'categories' => 'array|exists:categories,id',
            'subcategories' => 'array|exists:subcategories,id',
            'brands' => 'array|exists:brands,id',
            'prices' => 'array|min:0|max:100',
            'dates' => 'array'
        ];
    }

    public function search($query, $search)
    {
        return $query->where(function ($query) use ($search) {
            $query->where('products.name', 'LIKE', '%' . $search . '%');
        });
    }

    public function order($query, $options)
    {
        $options[0] = $this->transSortColumn($options[0]);


        return $query->join('subcategories', 'subcategories.id', '=', 'products.subcategory_id')
            ->join('categories', 'categories.id', '=', 'subcategories.category_id')
            ->join('brands', 'brands.id', '=', 'products.brand_id')
            ->select('products.*', 'categories.name as categoryName', 'subcategories.name as subcategoryName', 'brands.name as brandName')
            ->orderBy($options[0], $options[1]);
    }

    public function categories($query, $selectedCategories)
    {
        return $query->where(function ($query) use ($selectedCategories) {
            $query->join('subcategories', 'subcategories.id', '=', 'products.subcategory_id')
                ->join('categories', 'categories.id', '=', 'subcategories.category_id')
                ->select('categories.id')
                ->whereIn('categories.id',  $selectedCategories);
        });
    }

    public function subcategories($query, $selectedSubcategories)
    {
        return $query->whereIn('subcategory_id', $selectedSubcategories);
    }

    public function brands($query, $selectedBrands)
    {
        return $query->whereIn('brand_id', $selectedBrands);
    }

    public function prices($query, $priceFilters)
    {
        return $query->whereBetween('price', [$priceFilters[0], $priceFilters[1]]);
    }

    public function dates($query, $dateFilters)
    {
        if ($dateFilters[0] != null && $dateFilters[1] != null) {
            return $query->whereBetween('products.created_at', [$dateFilters[0], $dateFilters[1]]);
        }
    }
}
