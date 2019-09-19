<?php

namespace App\Http\Middleware;

use App\Event;
use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;

class EventOwner
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
        $event = Event::find($request->route('id'));
        $user = JWTAuth::parseToken()->authenticate();
        if($event->organization_id != $user->id)
        {
            return response()->json([
                'status' => "Access denied. You do not own this resource"
            ]);
        }

        return $next($request);
    }
}
