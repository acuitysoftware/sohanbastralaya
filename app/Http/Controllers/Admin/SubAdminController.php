<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubAdminController extends Controller
{
    public function index()
    {
    	$data['title'] = 'Sub Admin';
    	return view('pages.sub_admin.list', $data);
    }
}
