<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// @codingStandardsIgnoreStart
class RedirectIfAuthenticated
{
    // @codingStandardsIgnoreEnd
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request   Request object
     * @param \Closure                 $next      Next request
     * @param string|null              ...$guards Guards String
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
