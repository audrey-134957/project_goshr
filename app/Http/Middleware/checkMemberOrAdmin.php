<?php

namespace App\Http\Middleware;

use Closure;

class checkMemberOrAdmin
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

        if (auth()->check() && auth()->user()->role_id === NULL || $user && $user->role_id !== NULL) {
            return $next($request);
        }

        return redirect()->back(); 
    }
}
