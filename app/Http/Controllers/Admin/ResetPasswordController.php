<?php

namespace App\Http\Controllers\Admin;

use Mail;
use DB;
use Hash;
use Str;
use Carbon\Carbon;
use App\Mail\ForgotPasswordMail;
use App\Http\Livewire\Traits\AlertMessage;
use App\Models\User;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
	use AlertMessage;
    public function forgotPassword()
    {
    	return view('pages.auth.forgot_password');
    }

    public function forgotPasswordSave(Request $request)
    {
    	
		$request->validate([
			'email' => ['required', 'regex:/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,4}$/ix', 'max:255','exists:users,email'],
		]);
		$user = User::where('email', $request->email)->first();
		if(is_null($user))
		{
			$msgAction = 'Email not found! Please try again.';
	        $this->showToastr("success",$msgAction);

	        return redirect()->route('login');
		}

		$token = Str::random(120);
		DB::table('password_resets')
		->where('email', $request->email)->delete();

            DB::table('password_resets')->insert([
		    'email' => $request->email,
		    'token' => $token,
		    'created_at' => Carbon::now()
		]);
            
        $url = route('reset_password',['email'=>$user->email, 'token' =>$token]);
        $data['name'] = $user->name;
        $data['email'] = $user->email;
        $data['url'] = $url;
        Mail::to($user->email)->send(new ForgotPasswordMail($data));
        $msgAction = 'Reset mail send. Please check your email';
        $this->showToastr("success",$msgAction);

        return redirect()->route('login');
	}
    
    public function resetPassword(Request $request)
    {
    	$userData = DB::table('password_resets')->where('email', $request->email)->where('token', $request->token)->first();
    	if($userData)
    	{
    		$user = User::where('email', $request->email)->first();
    		
    		$data['user'] = $user;
    		return view('pages.auth.reset_password', $data);
    	}
    	else
    	{
    		return redirect()->route('login')->with('error', 'Something went wrong');
    	}
    }

    public function resetPasswordSave(Request $request)
	{
		$request->validate([
			'password' => ['required', 'min:6','confirmed'],
			'password_confirmation' => ['required', 'min:6'],
		]);
		$user = User::where('email', $request->email)->first();
		if(is_null($user))
		{
			$msgAction = 'User not found! Please try again.';
	        $this->showToastr("success",$msgAction);
		}
		$user->update(['password' => Hash::make($request->password)]);
		DB::table('password_resets')->where('email', $request->email)->delete();
		$msgAction = 'Password changed successfully';
        $this->showToastr("success",$msgAction);

        return redirect()->route('login');
	}
}
