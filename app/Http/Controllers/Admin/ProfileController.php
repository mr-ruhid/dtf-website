<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LoginLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        $user = Auth::user();
        return view('admin.profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:30'],
        ]);

        $user->update($data);

        return back()->with('status', 'Profile updated successfully.')->with('tab', 'profile');
    }

    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => ['required'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        if (!Hash::check($request->input('current_password'), $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.'])->with('tab', 'password');
        }

        $user->update([
            'password' => Hash::make($request->input('password')),
        ]);

        LoginLog::log('password', 'success', $user->email);

        return back()->with('status', 'Password updated successfully.')->with('tab', 'password');
    }

    public function toggleTwoFactor(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'password' => ['required'],
        ]);

        if (!Hash::check($request->input('password'), $user->password)) {
            return back()->withErrors(['password' => 'Password is incorrect.'])->with('tab', 'security');
        }

        $user->update([
            'two_factor_enabled' => !$user->two_factor_enabled,
        ]);

        $message = $user->two_factor_enabled
            ? 'Two-factor authentication enabled.'
            : 'Two-factor authentication disabled.';

        return back()->with('status', $message)->with('tab', 'security');
    }
}
