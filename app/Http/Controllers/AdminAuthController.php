<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminAuthController extends Controller
{
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

        return redirect()->route('admin.remates.index');
    }

    public function logout(Request $request): RedirectResponse
    {
        $request->session()->forget('remates_admin_auth');
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.remates.login');
    }
}
