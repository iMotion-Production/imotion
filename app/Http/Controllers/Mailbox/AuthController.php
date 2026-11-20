<?php

namespace App\Http\Controllers\Mailbox;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AuthController extends Controller
{
    public const LINK_EXPIRED = 'This link has expired or was replaced. Ask iMotion for a new one.';

    public const LINK_MISSING = 'Ask iMotion for a new set-password link.';

    public function showLogin(): View
    {
        return view('mailbox.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials)) {
            return back()->withErrors(['email' => 'These credentials do not match our records.'])->onlyInput('email');
        }

        $request->session()->regenerate();

        return redirect()->intended(route('mailbox.index'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('mailbox.login');
    }

    public function showReset(Request $request, string $token): View
    {
        return view('mailbox.reset-password', ['token' => $token, 'email' => (string) $request->query('email', '')]);
    }

    public function resetWithoutToken(): RedirectResponse
    {
        return redirect()->route('mailbox.login')->with('status', self::LINK_MISSING);
    }

    public function reset(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => ['required', 'string'],
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:10', 'confirmed'],
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();
                event(new PasswordReset($user));
            }
        );

        if ($status === Password::INVALID_TOKEN || $status === Password::INVALID_USER) {
            return back()->withErrors(['email' => self::LINK_EXPIRED])->onlyInput('email');
        }

        if ($status !== Password::PASSWORD_RESET) {
            return back()->withErrors(['email' => __($status)])->onlyInput('email');
        }

        return redirect()->route('mailbox.login')->with('status', __($status));
    }
}
