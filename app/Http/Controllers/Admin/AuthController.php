<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\TwoFactorCodeMail;
use App\Models\BlockedIp;
use App\Models\LoginLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check() && Auth::user()->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $email = $request->input('email');
        $ip = $request->ip();

        if (BlockedIp::isBlocked($ip) || LoginLog::isBlocked($email, $ip)) {
            LoginLog::log('password', 'blocked', $email);
            return back()->withErrors(['email' => 'Too many failed attempts. Try again in 6 hours.'])->onlyInput('email');
        }

        $user = User::where('email', $email)->first();

        if (!$user || !Hash::check($request->input('password'), $user->password)) {
            LoginLog::log('password', 'failed', $email);
            return back()->withErrors(['email' => 'Invalid credentials.'])->onlyInput('email');
        }

        if ($user->role !== 'admin' || $user->status != 1) {
            LoginLog::log('password', 'failed', $email);
            return back()->withErrors(['email' => 'Access denied.'])->onlyInput('email');
        }

        if (!$user->two_factor_enabled) {
            LoginLog::log('password', 'success', $email);
            Auth::login($user, $request->boolean('remember'));
            $request->session()->regenerate();
            return redirect()->route('admin.dashboard');
        }

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $request->session()->put('2fa_user_id', $user->id);
        $request->session()->put('2fa_code', $code);
        $request->session()->put('2fa_expires_at', now()->addMinutes(10)->timestamp);
        $request->session()->put('2fa_remember', $request->boolean('remember'));

        LoginLog::log('password', 'success', $email);

        Mail::to($user->email)->send(new TwoFactorCodeMail($code, $user->name));

        return redirect()->route('admin.2fa.show');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('admin.login');
    }
}
