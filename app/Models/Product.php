<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $guarded = ['id', 'created_at', 'updated_at'];
    const BORRADOR = 1;
    const PUBLICADO = 2;

    public function brands()
    {
        return $this->belongsTo(Brands::class);
    }

    public function subcategories()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function colors()
    {
        return $this->belongsToMany(Colors::class);
    }

    public function sizes()
    {
        return $this->hasMany(Sizes::class);
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
