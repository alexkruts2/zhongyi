<?php

namespace App\Http\Middleware;

use App\User;
use Closure;

class JWTAuthorization
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
        $self = user();
        if ($self instanceof User) {
            return $next($request);
        } else {
            return $this->renderError('您还没有登录。', UNAUTHORIZED_REQUEST);
        }
    }

    public function renderError($message, $code)
    {
        _log(
            '用户未登录',
            [
                'get_jwt_token' => get_jwt_token(),
            ],
            \Request::all()
        );
        return response()->json([
            'message' => $message,
            'code' => $code,
            'data' => '验证未通过'
        ], $code);
    }
}
