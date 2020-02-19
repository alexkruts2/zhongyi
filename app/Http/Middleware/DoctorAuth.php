<?php

namespace App\Http\Middleware;

use App\doctor;
use Closure;
use Illuminate\Support\Facades\Auth;

class DoctorAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
//        return $next($request);
            if(!empty(auth()->guard('doctor')->id())){
                if (is_null(doctor::find(auth()->guard('doctor')->id()))) {
                    return redirect()->route('login');
                }else{
                    return $next($request);
                }
            }else{
                if (!empty(auth()->guard('admin')->id())) {
                    if (is_null(admin::find(auth()->guard('admin')->id()))) {
                        return redirect()->route('login');
                    }
                    return $next($request);
                }
                return redirect()->route('login');
            }
    }
}
