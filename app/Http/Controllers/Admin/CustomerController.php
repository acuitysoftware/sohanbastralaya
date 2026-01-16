<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Permission;
use Str;
class CustomerController extends Controller
{
    public function customerList()
    {
        /*$all = Permission::all();
        foreach ($all as $key => $value) {
            $value->update(['permission' => Str::slug($value->name)]);
        }*/
    	$data['title'] = 'Customer List';
    	return view('pages.customer.list', $data);
    }
    public function index()
    {
        /*$all = Permission::all();
        foreach ($all as $key => $value) {
            $value->update(['permission' => Str::slug($value->name)]);
        }*/
    	$data['title'] = 'Customer List';
    	return view('pages.customer.customers', $data);
    }

    public function customerView($contact)
    {
        $data['title'] = 'Customer View';
        $data['contact'] = $contact;
        return view('pages.customer.customer_view', $data);
    }
    public function customerDetails($contact)
    {
        $data['title'] = 'Customer View';
        $data['contact'] = $contact;
        return view('pages.customer.customer_details', $data);
    }

    public function customerCardList()
    {
    	$data['title'] = 'Customer Card List';
    	return view('pages.customer.customer_card_list', $data);
    }
}
