<?php

namespace App\Http\Middleware;

use App\Helpers\ActivityLogger;
use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

class OnlineUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!empty(auth('web')->check())) {
            $expireTime = Carbon::now()->addMinutes(1);
            Cache::put('OnlineUser' . auth('web')->user()->id, true, $expireTime);
            $getUserInfo = User::find(auth('web')->user()->id);
            $getUserInfo->updated_at = date('Y-m-d H:i:s');
            $getUserInfo->save();
            ActivityLogger::log('Visited: ' . $request->path());
            return $next($request);
        } else {
            auth('web')->logout();
            return redirect(url('/'))->with("errorMsg", "Access Denied. You are not allowed to view this resource.");
        }
    }
}
