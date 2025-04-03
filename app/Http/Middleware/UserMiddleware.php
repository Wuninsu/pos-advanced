<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!empty(Auth::check())) {
            if (Auth::user()->role == 'user') {
                return $next($request);
            } else {
                Auth::logout();
                return redirect('/')->with("errorMsg", "Access Denied. You are not allowed to view this resource.");
                // abort('400');
            }
        } else {
            Auth::logout();
            return redirect(url('/'));
        }
    }
}
