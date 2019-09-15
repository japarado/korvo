<?php

namespace App\Http\Middleware;

use App\Event;
use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Config;

class ApproveEvent
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
        $event = Event::find($request->route('id'));
        if($user->role_id == Config::get('constants.roles.socc'))
        {
        }
        return $next($request);
    }
}
