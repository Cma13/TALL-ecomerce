<?php

namespace App\Models;

use App\QueryFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    const BORRADOR = 1;
    const PUBLICADO = 2;

    protected $guarded = ['id', 'created_at', 'updated_at'];

    //Accesores
    public function getStockAttribute()
    {
        if ($this->subcategory->size) {
            return ColorSize::whereHas('size.product', function(Builder $query){
                $query->where('id', $this->id);
            })->sum('quantity');
        } elseif ($this->subcategory->color) {
            return ColorProduct::whereHas('product', function(Builder $query){
                $query->where('id', $this->id);
            })->sum('quantity');
        } else {
            return $this->quantity;
        }
    }

    public function getSalesAttribute()
    {
        $id = $this->id;
        $orders = Order::select('content')->where('status', 2)->get();
        $i = 0;

        foreach($orders as $order) {
            $orders[$i] = json_decode($order->content, true);
            $i++;
        }
        $products = $orders->collapse();
        $counter = 0;
        foreach ($products as $product) {

            if ($product['id'] == $id) {
                $counter = $counter + $product['qty'];
            };
        }
        return $counter;
    }

    public function getUnconfirmedAttribute()
    {
        $id = $this->id;
        $orders = Order::select('content')->where('status', 1)->get();
        $i = 0;

        foreach($orders as $order) {
            $orders[$i] = json_decode($order->content, true);
            $i++;
        }
        $products = $orders->collapse();
        $counter = 0;
        foreach ($products as $product) {

            if ($product['id'] == $id) {
                $counter = $counter + $product['qty'];
            };
        }
        return $counter;
    }

    //Relaciones
    public function sizes()
    {
        return $this->hasMany(Size::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class);
    }

    public function colors()
    {
        return $this->belongsToMany(Color::class)->withPivot('quantity', 'id');
    }

    public function images()
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function scopeFilterBy($query, QueryFilter $filters, array $data)
    {
        return $filters->applyTo($query, $data);
    }
}
