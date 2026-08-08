<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class GuestMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (session('user')) {
            $role = session('user')['role'];
            return redirect($role === 'admin' ? '/admin/dashboard' : '/student/attendance');
        }
        return $next($request);
    }
}
