<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;


class LoginController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * @throws ValidationException
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember');

        $authenticated = false;

        // Try admin guard first
        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            // Also log into the default web guard so auth middleware passes
            Auth::login(Auth::guard('admin')->user(), $remember);
            $authenticated = true;
        }

        // If not admin, try as regular user
        if (!$authenticated && Auth::attempt($credentials, $remember)) {
            $authenticated = true;
        }

        if ($authenticated) {
            $request->session()->regenerate();

            // Determine destination: admins -> dashboard, others -> customer dashboard
            if (Auth::guard('admin')->check()) {
                return redirect()->intended(route('dashboard'));
            }

            return redirect()->intended(route('customer.dashboard'));
        }

        throw ValidationException::withMessages([
            'email' => trans('auth.failed'),
        ]);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
