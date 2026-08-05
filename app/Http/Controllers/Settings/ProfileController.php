<?php

namespace App\Http\Controllers\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('settings.profile', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request): JsonResponse|RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique($user->getTable())->ignore($user->getKey())],
        ]);

        $user->fill($validated);
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
        $user->save();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully.',
            ]);
        }

        return to_route('settings.profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): JsonResponse|RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();
        Auth::guard('web')->logout();
        Auth::guard('admin')->logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'redirect' => route('home'),
            ]);
        }

        return to_route('home');
    }
}
