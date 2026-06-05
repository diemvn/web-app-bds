<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Filament\Facades\Filament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('public.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'login' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $loginType = filter_var($credentials['login'], FILTER_VALIDATE_EMAIL) ? 'email' : 'phone';

        if (Auth::attempt([$loginType => $credentials['login'], 'password' => $credentials['password']], $request->boolean('remember'))) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->canAccessPanel(Filament::getPanel('admin'))) {
                return redirect()->intended('/admin');
            }

            if ($user->isTenant()) {
                return redirect()->intended(route('tenant.home'));
            }

            return redirect()->intended(route('home'));
        }

        return back()->withErrors(['login' => 'Tài khoản hoặc mật khẩu không đúng.'])->onlyInput('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('auth.login');
    }
}
