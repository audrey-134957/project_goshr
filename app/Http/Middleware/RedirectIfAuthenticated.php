<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle($request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check() && auth()->user()->role_id === NULL) {
                // return redirect(RouteServiceProvider::HOME);
                return redirect()->route('projects.index');
            }elseif(Auth::guard($guard)->check() && auth()->user()->role_id !== NULL){
                return redirect()->route('admin.indexUsers');
            }
        }

        return $next($request);
    }
}
