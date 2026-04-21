<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRematesAdminAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->session()->get('remates_admin_auth') === true) {
            return $next($request);
        }

        return redirect()->route('admin.remates.login');
    }
}
