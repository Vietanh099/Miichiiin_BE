<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AuthAdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $admin = Auth::guard('admins')->user();
        if ($admin == null) {
            return \response()->json(403);
        }

        if (Auth::guard('admins')->user()->tokenCan('admins')) {
            return $next($request);
        }

        return \response()->json(403);
    }
}
