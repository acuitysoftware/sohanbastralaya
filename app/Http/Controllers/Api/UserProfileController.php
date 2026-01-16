<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;


class UserProfileController extends Controller
{
    public function user()
    {
    	$user = User::find(Auth::user()->id);
        if(!is_null($user)) { 
            return response()->json([
            	"status" => true, 
            	"message" => 'User Profile', 
            	"data" => $user
            ]);
        }

        else {
            return response()->json(["status" => false, "message" => "Whoops! no user found"]);
        }   
    }

    public function updateAddress(Request $request)
    {
        $validator = Validator::make($request->all(), [
            "address" =>  "required",
            "latitute" =>  "required|numeric",
            "longitute" =>  "required|numeric",
        ]);

        if($validator->fails()) {
            return response()->json(["validation_errors" => $validator->errors()]);
        }   

        $user = User::find(Auth::user()->id);
        if(!is_null($user)) { 

            if($user->address)
            {
                return response()->json(["status" => false, "message" => "Whoops! Address already updated"]);    
            }
            else{
                
                $user->update([
                    'address' => $request->address,
                    'latitute' => $request->latitute,
                    'longitute' => $request->longitute,
                ]);
                $userData = User::find($user->id);
                return response()->json([
                    "status" => true, 
                    "message" => 'User Profile', 
                    "data" => $userData
                ]);
            }
            
        }

        else {
            return response()->json(["status" => false, "message" => "Whoops! no user found"]);
        }   
    }
}
