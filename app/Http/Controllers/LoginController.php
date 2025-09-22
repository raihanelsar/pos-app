<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function authLogin(Request $request)
    {
        $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required', 'string', 'min:6'],
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Tentukan URL redirect berdasarkan role_id
            switch ((int) $user->role_id) {
                case 1: // Admin
                    $redirectUrl = route('admin.dashboard');
                    break;
                case 2: // Kasir
                    $redirectUrl = route('kasir.dashboard');
                    break;
                case 3: // Pimpinan
                    $redirectUrl = route('pimpinan.dashboard');
                    break;
                default:
                    $redirectUrl = route('dashboard');
                    break;
            }

            return response()->json([
                'success'     => true,
                'message'     => 'Login berhasil',
                'role'        => $user->role_name, // ambil accessor dari model
                'redirectUrl' => $redirectUrl,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Email atau password salah!',
        ], 401);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
