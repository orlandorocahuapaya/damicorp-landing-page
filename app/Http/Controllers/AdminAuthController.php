<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
    private const ADMIN_AUTH_COOKIE = 'remates_admin_auth';

    public function showLogin(): View
    {
        return view('admin.remates.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'password' => ['required', 'string'],
        ]);

        if (! hash_equals(config('remates.admin_password'), $validated['password'])) {
            return back()->withErrors(['password' => 'Contrasena incorrecta.'])->onlyInput();
        }

        $request->session()->put('remates_admin_auth', true);
        $request->session()->regenerate();
        Cookie::queue(cookie(
            self::ADMIN_AUTH_COOKIE,
            '1',
            60 * 24 * 30,
            '/',
            config('session.domain'),
            config('session.secure'),
            true,
            false,
            config('session.same_site', 'lax')
        ));

        return redirect()->route('admin.remates.index');
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('remates_admin_auth');
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        Cookie::queue(Cookie::forget(self::ADMIN_AUTH_COOKIE, '/', config('session.domain')));

        return redirect()->route('admin.remates.login');
    }
}
