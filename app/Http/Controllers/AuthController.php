<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::guard('admin')->check()) {
            return redirect()->route('products.index');
        }

        return view('admin.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        $credentials = [
            'username'  => $request->username,
            'password'  => $request->password,
            'is_active' => true,
        ];

        $remember = $request->boolean('remember');

        if (Auth::guard('admin')->attempt($credentials, $remember)) {

            Auth::guard('admin')->user()->update([
                'last_login' => now(),
            ]);

            $request->session()->regenerate();

            return redirect()->route('products.index');
        }

        return back()
            ->withInput($request->only('username'))
            ->withErrors([
                'username' => 'Username atau password salah, atau akun tidak aktif.',
            ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}