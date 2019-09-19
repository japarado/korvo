<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Config;
use Tymon\JWTAuth\Facades\JWTAuth;

class EventInspectors
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
        $user = JWTAuth::parseToken()->authenticate();
        if(!in_array($user->role_id, [Config::get('constants.roles.socc'), Config::get('constants.roles.osa')]))
        {
            return response()->json([
                'status' => 'Access denied'
            ]);
        }
        return $next($request);
    }
}
