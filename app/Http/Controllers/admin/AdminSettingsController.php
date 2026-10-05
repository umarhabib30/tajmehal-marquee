<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class AdminSettingsController extends Controller
{
    public function index()
    {
        return view('admin.settings.index', [
            'user' => Auth::user(),
            'heading' => 'Account Settings',
            'active' => 'settings',
            'title' => 'Settings',
        ]);
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        Auth::user()->update([
            'password' => $validated['password'],
        ]);

        return redirect()
            ->route('admin.settings.index')
            ->with('success', 'Password updated successfully.');
    }
}
