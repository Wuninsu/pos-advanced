<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (auth('web')->check()) {
            if (in_array(auth('web')->user()->role, $roles)) {
                return $next($request);
            } else {
                return redirect('/')->with("errorMsg", "Access Denied. You are not allowed to view this resource.");
            }
        }

        return redirect('/')->with("errorMsg", "You must be logged in.");
    }
}
