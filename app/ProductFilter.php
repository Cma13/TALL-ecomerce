<?php

namespace App;

use App\Models\Product;

class ProductFilter
{

    public static function queryProducts($search, $pages, $sortColumn, $sortDirection)
    {
        switch ($sortColumn) {
            case 'Nombre':
                $sortColumn = 'products.name';
                break;
            case 'Categoría':
                $sortColumn = 'categoryName';
                break;
            case 'Subcategoría':
                $sortColumn = 'subcategoryName';
                break;
            case 'Marca':
                $sortColumn = 'brandName';
                break;
            case 'Fecha de creación':
                $sortColumn = 'created_at';
                break;
            case 'Precio':
                $sortColumn = 'price';
                break;
            default:
                # code...
                break;
        }

        return $products = Product::query()
            ->where('products.name', 'LIKE', '%' . $search . '%')
            ->join('subcategories', 'subcategories.id', '=', 'products.subcategory_id')
            ->join('categories', 'categories.id', '=', 'subcategories.category_id')
            ->join('brands', 'brands.id', '=', 'products.brand_id')
            ->select('products.*', 'categories.name as categoryName', 'subcategories.name as subcategoryName', 'brands.name as brandName')
            ->orderBy($sortColumn, $sortDirection)
            ->paginate($pages);
    }
}
