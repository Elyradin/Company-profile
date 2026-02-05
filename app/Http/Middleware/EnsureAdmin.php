<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();

        if (!$user || $user->role !== 'admin') {
            // For API requests return JSON, for web throw 403
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'Forbidden'], 403);
            }

            abort(403, 'Forbidden');
        }

        return $next($request);
    }
}
