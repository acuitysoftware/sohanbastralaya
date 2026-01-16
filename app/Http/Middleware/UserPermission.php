<?php

namespace App\Http\Middleware;

use Auth;
use Route;
use Closure;
use App\Models\Permission;
use Illuminate\Http\Request;

class UserPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();
        if ($user->type == 'A') 
        {
            return $next($request);
        }
        else
        {
            $route_name = Route::currentRouteName();
            
            if(in_array($route_name, $user->permissions()->pluck('route_name')->toArray()))
            {
                return $next($request);
            }else
            {
                if($route_name == 'add_to_cart')
                {
                    return redirect()->back();
                }
                Auth::logout();
                return redirect('/')->with('error', 'You are not granted user.please contact to admin');
            }
        }
    }
}
