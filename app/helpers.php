<?php

use App\Models\Color;
use App\Models\Product;
use App\Models\Size;
use Gloudemans\Shoppingcart\Facades\Cart;

function quantity($productId, $colorId = null, $sizeId = null)
{
    $product = Product::find($productId);

    if ($sizeId) {
        $size = Size::find($sizeId);

        $quantity = $size->colors->find($colorId)->pivot->quantity;
    } elseif($colorId) {
        $quantity = $product->colors->find($colorId)->pivot->quantity;
    } else {
        $quantity = $product->quantity;
    }

    return $quantity;
}

function qtyAdded($productId, $colorId = null, $sizeId = null)
{
    $cart = Cart::content();

    $item = $cart->where('id', $productId)
                ->where('options.colorId', $colorId)
                ->where('options.sizeId', $sizeId)->first();

    if ($item) {
        return $item->qty;
    } else {
        return 0;
    }
}

function qtyAvailable($productId, $colorId = null, $sizeId = null)
{
    return quantity($productId, $colorId, $sizeId) - qtyAdded($productId, $colorId, $sizeId);
}