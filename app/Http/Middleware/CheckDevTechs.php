<?php

namespace App\Http\Middleware;

use Closure;

class CheckDevTechs
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
        return $next($request);
        // return response()->json(['error' => 'parametro incorreto'], 400);
    }
}
