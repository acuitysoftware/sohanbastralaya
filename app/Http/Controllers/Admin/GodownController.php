<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GodownController extends Controller
{
    public function godownStock()
    {
    	$data['title'] = 'Godown Stock';
    	return view('pages.godown.godown_stock', $data);
    }

    
}
