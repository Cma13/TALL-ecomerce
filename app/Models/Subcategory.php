<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'create_at', 'updated_at'];

    public function products()
    {
        return $this->hasMany(Products::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
