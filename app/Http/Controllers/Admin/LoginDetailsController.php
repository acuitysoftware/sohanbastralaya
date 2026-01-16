<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginDetailsController extends Controller
{
    public function loginDetails()
    {
    	$data['title'] = 'Login Details';
    	return view('pages.sub_admin.login_list', $data);
    }
}
