<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\UpdateCustomerRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        return view('admin.users.index');
    }

    public function data(Request $request): View
    {
        $query = User::query()->latest();

        if ($request->filled('status_filter') && $request->status_filter !== 'all') {
            $query->where('is_active', $request->status_filter === 'active');
        }

        if ($search = trim((string) $request->input('search'))) {
            $query->where(fn ($builder) => $builder
                ->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%"));
        }

        $users = $query->paginate(10)->withQueryString();

        return view('partials.admin_users_table', compact('users'));
    }

    public function edit(User $user): View
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(UpdateCustomerRequest $request, User $user): RedirectResponse
    {
        $user->update($request->validated());

        return redirect()->route('admin.users.edit', $user)->with('success', __('Customer updated successfully.'));
    }

    public function toggleStatus(User $user): JsonResponse
    {
        $user->update(['is_active' => ! $user->is_active]);

        return response()->json([
            'message' => $user->is_active ? __('Customer account activated.') : __('Customer account suspended.'),
            'is_active' => $user->is_active,
        ]);
    }
}
