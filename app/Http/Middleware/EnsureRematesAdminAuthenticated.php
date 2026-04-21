<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureRematesAdminAuthenticated
{
    private const ADMIN_AUTH_COOKIE = 'remates_admin_auth';

    public function handle(Request $request, Closure $next): Response
    {
        if ($request->session()->get('remates_admin_auth') === true) {
            return $next($request);
        }

        if ($request->cookie(self::ADMIN_AUTH_COOKIE) === '1') {
            $request->session()->put('remates_admin_auth', true);

            return $next($request);
        }

        return redirect()->route('admin.remates.login');
    }
}
