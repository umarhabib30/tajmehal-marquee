<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::query()->orderBy('name')->get();

        return view('admin.users.index', [
            'heading' => 'Users & permissions',
            'title' => 'Admin users',
            'active' => 'users',
            'users' => $users,
        ]);
    }

    public function create()
    {
        return view('admin.users.create', [
            'heading' => 'Users & permissions',
            'title' => 'Create user',
            'active' => 'users',
            'modules' => config('admin_modules.modules', []),
            'actions' => config('admin_modules.actions', []),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'],
            'role' => User::ROLE_STAFF,
            'permissions' => User::normalizePermissionsArray($request->input('permissions', [])),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User created.');
    }

    public function edit(User $user)
    {
        $this->guardSuperAdminTarget($user);

        return view('admin.users.edit', [
            'heading' => 'Users & permissions',
            'title' => 'Edit user',
            'active' => 'users',
            'editUser' => $user,
            'modules' => config('admin_modules.modules', []),
            'actions' => config('admin_modules.actions', []),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $this->guardSuperAdminTarget($user);

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'permissions' => User::normalizePermissionsArray($request->input('permissions', [])),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User updated.');
    }

    public function resetPassword(Request $request, User $user)
    {
        $this->guardSuperAdminTarget($user);

        $validated = $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user->update([
            'password' => $validated['password'],
        ]);

        return redirect()->route('admin.users.edit', $user)->with('success', 'Password reset for '.$user->name.'.');
    }

    public function destroy(User $user)
    {
        $this->guardSuperAdminTarget($user);

        if ($user->id === Auth::id()) {
            return redirect()->route('admin.users.index')->withErrors(['delete' => 'You cannot delete your own account.']);
        }

        if ($user->isSuperAdmin() && User::where('role', User::ROLE_SUPER_ADMIN)->count() <= 1) {
            return redirect()->route('admin.users.index')->withErrors(['delete' => 'The last super admin cannot be deleted.']);
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User deleted.');
    }

    private function guardSuperAdminTarget(User $user): void
    {
        if ($user->isSuperAdmin()) {
            abort(403);
        }
    }
}
