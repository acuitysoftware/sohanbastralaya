<?php

namespace App\Http\Controllers\Admin;
use Route;
use Auth;
use App\Models\Permission;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
{

    public function productList()
    {
    	$data['title'] = 'Product List';
    	return view('pages.product.list', $data);
    }

    public function limitedProduct()
    {
    	$data['title'] = 'Limited Product List';
    	return view('pages.product.limited_product_list', $data);
    }
    public function outOfProduct()
    {
    	$data['title'] = 'Out of Product List';
    	return view('pages.product.out_of_product_list', $data);
    }

    public function uplodaProductImage()
    {
        $data['title'] = 'Upload Product Image';
        return view('pages.product.upload_product_image', $data);
    }

    public function productOrder($order_id)
    {
        $data['title'] = 'Product Order';
        $data['order_id'] = $order_id;
        return view('pages.product.product_order', $data);
    }
}
