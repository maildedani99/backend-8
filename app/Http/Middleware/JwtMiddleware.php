<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Exception;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;

class JwtMiddleware extends BaseMiddleware
{

    public function handle($request, Closure $next)
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                return response()->json(['status' => 'Token is Invalid' , 'detail' => $e->getMessage(), 'error'=> $e]);
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                return response()->json(['status' => 'Token is Expired','detail' => $e->getMessage(), 'error'=> $e ]);
            }else{
                return response()->json(['status' => 'Authorization Token not found' , 'detail' => $e->getMessage(), 'error'=> $e]);
            }
        }
        return $next($request);
    }
}
