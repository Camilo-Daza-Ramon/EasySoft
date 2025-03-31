<?php

namespace App\Http\Middleware;

use Closure;

class CheckStatusUser
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

        $user = $request->user();
        if ($user) {
            if (auth()->user()->estado == 'INACTIVO') {
                auth()->logout();
            }
        }
        return $next($request);
    }
}
