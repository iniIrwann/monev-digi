<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm(): \Illuminate\View\View
    {
        return view('page.login.login');
    }
    public function authenticate(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'username' => ['required', 'string', 'max:255'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            if ($user->role === 'desa') {
                return redirect()->route('dashboard.index')->with('success', 'Login berhasil sebagai Desa!');
            } elseif ($user->role === 'kecamatan') {
                return redirect()->route('targetKec.index')->with('success', 'Login berhasil sebagai Kecamatan!');
            }

            Auth::logout();
            return redirect()->route('login.view')->with('error', 'Role tidak dikenali.');
        }

        // Pindahkan ke sini
        return back()->with('error', 'Login gagal! Periksa kembali username dan password Anda.')
            ->onlyInput('username');
    }
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.view')->with('success', 'Logout Berhasil!');
    }
}
