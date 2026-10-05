
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\TwoFactorCodeMail;
use App\Models\BlockedIp;
use App\Models\LoginLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class TwoFactorController extends Controller
{
    public function show(Request $request)
    {
        if (!$request->session()->has('2fa_user_id')) {
            return redirect()->route('admin.login');
        }

        if (BlockedIp::isBlocked($request->ip()) || LoginLog::isBlocked(null, $request->ip())) {
            $request->session()->forget(['2fa_user_id', '2fa_code', '2fa_expires_at', '2fa_remember']);
            return redirect()->route('admin.login')->withErrors(['email' => 'Too many failed attempts. Try again later.']);
        }

        $email = User::find($request->session()->get('2fa_user_id'))?->email;
        $masked = $email ? $this->maskEmail($email) : '';

        return view('admin.auth.two-factor', compact('masked'));
    }

    public function verify(Request $request)
    {
        $request->validate(['code' => ['required', 'digits:6']]);

        $userId = $request->session()->get('2fa_user_id');
        $sessionCode = $request->session()->get('2fa_code');
        $expiresAt = $request->session()->get('2fa_expires_at');
        $remember = $request->session()->get('2fa_remember', false);

        if (!$userId || !$sessionCode) {
            return redirect()->route('admin.login');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('admin.login');
        }

        if (BlockedIp::isBlocked($request->ip()) || LoginLog::isBlocked($user->email, $request->ip())) {
            LoginLog::log('2fa', 'blocked', $user->email);
            $request->session()->forget(['2fa_user_id', '2fa_code', '2fa_expires_at', '2fa_remember']);
            return redirect()->route('admin.login')->withErrors(['email' => 'Too many failed attempts. Try again in 6 hours.']);
        }

        if (now()->timestamp > $expiresAt) {
            LoginLog::log('2fa', 'failed', $user->email);
            return back()->withErrors(['code' => 'Code expired. Please request a new one.']);
        }

        if ($request->input('code') !== $sessionCode) {
            LoginLog::log('2fa', 'failed', $user->email);
            return back()->withErrors(['code' => 'Invalid code.']);
        }

        LoginLog::log('2fa', 'success', $user->email);

        Auth::login($user, $remember);
        $request->session()->regenerate();
        $request->session()->forget(['2fa_user_id', '2fa_code', '2fa_expires_at', '2fa_remember']);

        return redirect()->route('admin.dashboard');
    }

    public function resend(Request $request)
    {
        $userId = $request->session()->get('2fa_user_id');
        if (!$userId) {
            return redirect()->route('admin.login');
        }

        $user = User::find($userId);
        if (!$user) {
            return redirect()->route('admin.login');
        }

        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $request->session()->put('2fa_code', $code);
        $request->session()->put('2fa_expires_at', now()->addMinutes(10)->timestamp);

        Mail::to($user->email)->send(new TwoFactorCodeMail($code, $user->name));

        return back()->with('status', 'A new code has been sent to your email.');
    }

    private function maskEmail(string $email): string
    {
        [$name, $domain] = explode('@', $email);
        $visible = substr($name, 0, 2);
        $masked = str_repeat('*', max(strlen($name) - 2, 1));
        return $visible . $masked . '@' . $domain;
    }
}
