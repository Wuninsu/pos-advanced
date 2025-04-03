<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CommonMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        if (!empty(auth('web')->check())) {
            return $next($request);
        } else {
            auth('web')->logout();
            return redirect(url('/'))->with("errorMsg", "Access Denied. You are not allowed to view this resource.");
        }
    }
}
