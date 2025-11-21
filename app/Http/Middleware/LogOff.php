<?php

namespace App\Http\Middleware;

use Closure;

class LogOff
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
        if(session()->has('logoff')){
            return redirect()->route('password.confirm');
        }
        return $next($request);
    }
}
