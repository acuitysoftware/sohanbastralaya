<?php

namespace App\Http\Controllers\Api;

use App\Models\TempData;
use App\Models\User;
use App\Models\LoginDetails;
use Illuminate\Support\Facades\Auth;
use Validator;
use DB;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "email" =>  "required|exists:users",
            "password" =>  "required",
        ]);

        
        if ($validator->errors()->has('email')){
            return response()->json(['success'=>false ,"message" =>  $validator->errors()->get('email')[0]]);
        }
        if ($validator->errors()->has('password')){
            return response()->json(['success'=>false ,"message" =>  $validator->errors()->get('password')[0]]);
        }


        
            
        $today = date('Y-m-d');
    	if(Auth::attempt(['email' => $request->email, 'password' => $request->password]))
        { 
            $user = Auth::user(); 
            if($user->status == '0')
            {
                return response()->json([
                    'success'=>false ,
                    'message'=>'Account not active.Please contact to admin',
                ]);
            }
            $user = User::find(Auth::user()->id);
            $token =  $user->createToken('sohan')->accessToken; 
   
            return response()->json([
                'success' => true,
                'message' => 'Login Successfully',
                'token' => $token,
                'data' => $user,
            ]);
            
        } 
        else{ 
            return response()->json([
                'success'=>false ,
                'message'=>'Email not found! Please try again' ,
            ]);
        }
        
    }

    public function shiftChecking(Request $request)
    {
        $today = date('Y-m-d');
        $user = Auth::user(); 
        $userData = LoginDetails::where('user_id', $user->id)->where('date', $today)->first();
        if($userData)
        {
            $morning_shift = LoginDetails::where('user_id', $user->id)->where('date', $today)->where('shift', 1)->first();
            $evening_shift = LoginDetails::where('user_id', $user->id)->where('date', $today)->where('shift', 2)->first();
            if($morning_shift)
            {
                if($morning_shift && $morning_shift->logout_time)
                {
                    if($evening_shift && $evening_shift->logout_time)
                    {
                        $data['morning_shift'] = false;
                        $data['evening_shift'] = false;
                        return response()->json([
                            'success'=>true ,
                            'data'=> $data,
                        ]);
                    }
                    else{
                        $data['morning_shift'] = false;
                        $data['evening_shift'] = true;
                        return response()->json([
                            'success'=>true ,
                            'data'=> $data,
                        ]);
                    }
                    
                }
                else{
                    $data['morning_shift'] = true;
                    $data['evening_shift'] = false;
                    return response()->json([
                        'success'=>true ,
                        'data'=> $data,
                    ]);
                }
                
            }
            else{
                 
                 if($evening_shift && $evening_shift->logout_time)
                {
                    if($morning_shift && $morning_shift->logout_time)
                    {
                        $data['morning_shift'] = false;
                        $data['evening_shift'] = false;
                        return response()->json([
                            'success'=>true ,
                            'data'=> $data,
                        ]);
                    }
                    else{
                        $data['morning_shift'] = false;
                        $data['evening_shift'] = true;
                        return response()->json([
                            'success'=>true ,
                            'data'=> $data,
                        ]);
                    }
                }
                else{
                    $data['morning_shift'] = false;
                    $data['evening_shift'] = true;
                    return response()->json([
                        'success'=>true ,
                        'data'=> $data,
                    ]);
                }
                
            }
        }
        else{
            $data['morning_shift'] = true;
            $data['evening_shift'] = true;
            return response()->json([
                'success'=>true ,
                'data'=> $data,
            ]);
        }
    }


    public function shiftInOutChecking(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "shift" =>  "required|in:1,2",
        ]);

        if ($validator->errors()->has('shift')){
            return response()->json(['success'=>false ,"message" =>  $validator->errors()->get('shift')[0]]);
        }
        

        $today = date('Y-m-d');
        $user = Auth::user();

        $shiftChecking = LoginDetails::where('user_id', $user->id)->where('date', $today)->where('shift', $request->shift)->first();

        if($shiftChecking)
        {
            
            if(is_null($shiftChecking->logout_time))
            {
                $data['shift_in'] = false;
                $data['shift_out'] = true;
                return response()->json([
                    'success'=>true ,
                    'data'=> $data,
                ]);
            }
            else{
                $data['shift_in'] = false;
                $data['shift_out'] = false;
                return response()->json([
                    'success'=>true ,
                    'data'=> $data,
                ]);
            }
        }
        else{
            $data['shift_in'] = true;
            $data['shift_out'] = false;
            return response()->json([
                'success'=>true ,
                'data'=> $data,
            ]);
        }


    }
    public function shiftInOut(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "shift" =>  "required|in:1,2",
            "latitute" =>  "required|numeric",
            "longitute" =>  "required|numeric",
        ]);

        if ($validator->errors()->has('shift')){
            return response()->json(['success'=>false ,"message" =>  $validator->errors()->get('shift')[0]]);
        }
        if ($validator->errors()->has('latitute')){
            return response()->json(['success'=>false ,"message" =>  $validator->errors()->get('latitute')[0]]);
        }
        if ($validator->errors()->has('longitute')){
            return response()->json(['success'=>false ,"message" =>  $validator->errors()->get('longitute')[0]]);
        }

        $today = date('Y-m-d');
        $user = Auth::user();

        if($user->latitute && $user->longitute)
        {
            TempData::create([
                'user_id' => $user->id,
                'latitute' => $request->latitute,
                'longitute' => $request->longitute,
            ]);
            $latitute = $request->latitute;
            $longitute = $request->longitute;
            $address_check = User::where('id', $user->id)->select('id','name','address', 'latitute', 'longitute', DB::raw("6371 * acos(cos(radians(" . $latitute . ")) 
            * cos(radians(latitute)) 
            * cos(radians(longitute) - radians(" . $longitute . ")) 
            + sin(radians(" .$latitute. ")) 
            * sin(radians(latitute))) AS distance"))->having('distance','<', env('SET_DISTANCE',0.300))->first();

            /*$address_check = User::where('id', $user->id)->select('id','name','address', 'latitute', 'longitute', DB::raw("6371 * acos(cos(radians(" . $latitute . ")) 
            * cos(radians(latitute)) 
            * cos(radians(longitute) - radians(" . $longitute . ")) 
            + sin(radians(" .$latitute. ")) 
            * sin(radians(latitute))) AS distance"))->having('distance','<', env('SET_DISTANCE',0.050))->first();*/

            if(is_null($address_check))
            {
                return response()->json([
                    'success'=>false ,
                    'message'=>'Location not matched',
                ]);
            }


            
            $shiftChecking = LoginDetails::where('user_id', $user->id)->where('date', $today)->where('shift', $request->shift)->first();
            if($shiftChecking)
            {
                $shiftChecking->update(['logout_time' => date('H:i:s')]);

                $data['shift_in'] = false;
                $data['shift_out'] = false;
                return response()->json([
                    'success' => true,
                    'message' => 'Shift out Successfully',
                    'data' => $data
                ]);
            }
            else{

                $new_data = LoginDetails::create([
                                'user_id' => $user->id,
                                'date' => $today,
                                'shift' => $request->shift,
                                'login_time' => date('H:i:s'),
                                'latitute' => $request->latitute,
                                'longitute' => $request->longitute,
                            ]);
                $data['shift_in'] = false;
                $data['shift_out'] = true;
                return response()->json([
                    'success' => true,
                    'message' => 'Shift in Successfully',
                    'data' => $data
                ]);
            }
        } 
        else{ 
            return response()->json([
                'success'=>false ,
                'message'=>'Please set your address' ,
            ]);
        } 
    }

    
}
