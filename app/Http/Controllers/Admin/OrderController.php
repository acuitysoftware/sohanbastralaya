<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function __construct()
    {
        ini_set('memory_limit','512M');
        ini_set('post_max_size','200M');
        ini_set('max_execution_time', 3000);
    }
    public function orderList()
    {
    	$data['title'] = 'Order List';
    	return view('pages.order.order_list', $data);
    }

    public function orderReport()
    {
    	$data['title'] = 'Order Report';
    	return view('pages.order.order_report', $data);
    }

    public function subAdminOrder()
    {
    	$data['title'] = 'Sub Admin Order';
    	return view('pages.order.sub_admin_order', $data);
    }

    public function subAdminOrderView($id)
    {
        $data['title'] = 'Sub Admin Order View';
        $data['id'] = $id;
        return view('pages.order.sub_admin_order_view', $data);
    }
}
