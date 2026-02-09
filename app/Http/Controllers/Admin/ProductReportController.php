<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductReportController extends Controller
{
    public function __construct()
    {
        ini_set('memory_limit','512M');
        ini_set('max_execution_time', 3000);
    }
    public function productReport()
    {
    	$data['title'] = 'Product Report';
    	return view('pages.product_report.product_report', $data);
    }

    public function stockReport()
    {
    	$data['title'] = 'Stock Report';
    	return view('pages.product_report.stock_report', $data);
    }

    public function stockInserted()
    {
        $data['title'] = 'Stock Inserted';
        return view('pages.product_report.stock_inserted', $data);
    }

    public function productSellingOrder()
    {
    	$data['title'] = 'Product Selling Order';
    	return view('pages.product_report.product_selling_order', $data);
    }

    public function purchaseReport()
    {
    	$data['title'] = 'Purchase Report';
    	return view('pages.product_report.purchase_report', $data);
    }

    public function userPurchaseReport()
    {
        $data['title'] = 'User Purchase Report';
        return view('pages.product_report.user_purchase_report', $data);
    }
    public function dueOrderReport()
    {
        $data['title'] = 'Due Amount Report';
        return view('pages.product_report.due_order_report', $data);
    }
}
