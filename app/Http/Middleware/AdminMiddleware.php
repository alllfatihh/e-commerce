<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            if ($request->expectsJson() || $request->is('api/*') || ($request->is('admin/*') && $request->isMethod('POST'))) {
                return response()->json(['error' => 'Unauthenticated.'], 401);
            }

            return redirect()->guest('/login');
        }

        if (! auth()->user()->isAdmin()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['error' => 'Unauthorized. Admin access required.'], 403);
            }
            abort(403, 'Akses Ditolak: Anda tidak memiliki izin untuk mengakses panel Admin.');
        }

        return $next($request);
    }
}
