<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AzureMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        foreach ($guards as $guard) {
            if (!isset($_COOKIE['access_token']) && $guard == 'guest') {
                return $next($request);
            } elseif (!isset($_COOKIE['access_token']) && $guard == 'auth') {
                return response()->json(['message' => 'Unauthenticated'], 403);
            } elseif (isset($_COOKIE['access_token']) && $guard == 'guest') {
                return response()->json(['message' => 'Unauthenticated'], 403);
            }
        }

        return $next($request);
    }
}
