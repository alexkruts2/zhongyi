<?php

namespace App\Http\Middleware;

use App\admin;
use App\doctor;
use Closure;

class AdminAuth
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
        $arr_url = parse_url($request->url());
        $path = $arr_url['path'];
        if (!empty(auth()->guard('admin')->id())){
            if (is_null(admin::find(auth()->guard('admin')->id()))) {
                return redirect()->route('login');
            }
            return $next($request);
        }else{
            $doctor_id = auth()->guard('doctor')->id();
            if(!empty($doctor_id)){
                if(checkAuthority($doctor_id,$path))
                    return $next($request);
                else
                    abort(403, '未经授权的行动.');
            }else
                return redirect()->route('login');
        }
    }
}
