<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('user') || session('user')['role'] !== 'admin') {
            return redirect()->route('login')->with('error', 'Admin access required.');
        }
        return $next($request);
    }
}
