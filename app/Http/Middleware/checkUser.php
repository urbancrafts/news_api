<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Illuminate\Http\Request;

class checkUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $guard="api")
    {
        if (!Auth::check()) {
            return response()->json("Unauthorized Access Please Login.", 422); 
        }

        if(!auth()->guard($guard)->check()) {
            return response()->json("Forbidden, Only store owners have access to this page.", 403); 
            
            // return redirect(route('admin.login'));
        }

        return $next($request);
        //return $next($request);
    }
}
