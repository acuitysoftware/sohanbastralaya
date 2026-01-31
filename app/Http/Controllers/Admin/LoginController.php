<?php

namespace App\Http\Controllers\Admin;

use Auth;
use Cookie;
use Hash;
use App\Models\Admin;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function loginView()
    {
    	if(Auth::check())
	    {
	        return redirect()->route('product_index');
	    }
    	return view('pages.auth.login');
    }
    public function dashboard()
    {
    	return view('pages.dashboard');
    }

   

    public function login(Request $request)
    {
        
      $credentials = $request->validate([
            'email' => 'required|email',
            'password' =>'required'
        ]);
       $remember_me  = ( !empty( $request->remember_me ) )? TRUE : FALSE;
       if($remember_me)
       {
            Cookie::queue('adminUser', $request->email, 1440);
            Cookie::queue('adminPwd', $request->password, 1440);
       }
        
        if (Auth::attempt($credentials, $remember_me)) 
        {
        	$user = Auth::user();
			if ($user->status == '0') 
			{
			Auth::logout();
			return redirect()->back()->with('error', 'User not active! Contact admin.');
			}
			Auth::login($user, $remember_me);
			Auth::user()->update(['last_login' => date("Y-m-d H:i:s")]);
			return redirect()->route('dashboard')->with('success', 'LoggedIn Successfully');
        }
        else
        {
          return redirect()->back()->with('error','Invalid credentials');
        }

    }

    public function logout(Request $request)
    {
        Auth::logout();
        return redirect('/');
    }


}
