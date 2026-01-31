<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function dashboard()
    {
    	$data['title'] = 'Dashboard';
    	return view('pages.dashboard', $data);
    }
    public function siteSettings()
    {
    	$data['title'] = 'Site Settings';
    	return view('pages.site_setting.site-settings', $data);
    }

    public function changePassword()
    {
    	$data['title'] = 'Change Password';
    	return view('pages.site_setting.change_password', $data);
    }
}
