<?php

namespace App\Http\Controllers;

use App\Constants\UserConst;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('_admin.auth.login');
    }

    public function doLogin(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return $this->redirectByRole(Auth::user());
        }

        return back()->withErrors([
            'login_error' => 'Username atau Password tidak sesuai, periksa kembali',
        ])->onlyInput('username');
    }

    private function redirectByRole($user)
    {
        switch ($user->access_type) {
            case UserConst::SUPERADMIN:
                return redirect()->route('admin.dashboard');
            case UserConst::TOOLSMAN:
                return redirect()->route('toolsman.dashboard');
            case UserConst::USER:
                return redirect()->route('user.dashboard');
            default:
                return redirect()->route('login');
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
