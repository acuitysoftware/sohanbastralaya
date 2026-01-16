<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\CartItem;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductCartController extends Controller
{
    public function addToCart()
    {
    	$data['cart_items'] = CartItem::orderBy('id','DESC')->get();
    	$data['title'] = 'Product Cart';
    	return view('pages.product.add_to_cart', $data);
    }
}
