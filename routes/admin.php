<?php

use Illuminate\Support\Facades\Route;
use App\Http\Livewire\Admin\ShowProducts;

Route::get('/', ShowProducts::class)->name('admin.index');
Route::get('products/{id}/edit', function(){})->name('admin.products.edit');
Route::get('products/create', function(){})->name('admin.products.create');