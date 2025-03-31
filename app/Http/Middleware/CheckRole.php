<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;

use Closure;
use Entrust;

class CheckRole
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

            $roles = array_slice(func_get_args(), 2);

            if (Entrust::hasRole($roles)) {
                return $next($request);
            }else{

                abort(403);
            }
        

        //tambien sirve
        /*if (auth()->check() && auth()->user()->hasRole($roles))
        {
            return $next($request);
        }else{
            abort(403);
        }*/


        //return redirect('/home');

        //return $next($request);
    }
}
