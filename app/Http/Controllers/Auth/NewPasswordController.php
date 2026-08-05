<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'token' => ['required'],
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        try {
            // Here we will attempt to reset the user's password.
            $status = Password::reset(
                $request->only('email', 'password', 'password_confirmation', 'token'),
                function (User $user) use ($request) {
                    $user->forceFill([
                        'password' => Hash::make($request->password),
                        'remember_token' => Str::random(60),
                    ])->save();

                    event(new PasswordReset($user));
                }
            );

            $response = [
                'success' => $status === Password::PASSWORD_RESET,
                'message' => __($status)
            ];

            if ($request->ajax() || $request->wantsJson()) {
                if ($status === Password::PASSWORD_RESET) {
                    $response['redirect'] = route('login.form');
                } else {
                    $response['errors'] = ['email' => [__($status)]];
                }
                return response()->json($response, $status === Password::PASSWORD_RESET ? 200 : 422);
            }

            // For non-AJAX requests, maintain the original redirect behavior
            return $status === Password::PASSWORD_RESET
                ? to_route('login.form')->with('status', __($status))
                : back()->withInput($request->only('email'))
                    ->withErrors(['email' => __($status)]);
                    
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to reset password. Please try again.'
                ], 500);
            }
            
            throw $e;
        }
    }
}
