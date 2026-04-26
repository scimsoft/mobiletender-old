<?php

namespace App\Http\Middleware;
use Closure;

class IsWaiter
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
        if(auth()->user())if(auth()->user()->isWaiter()) {
            return $next($request);
        }
        return redirect('login');
    }
}
