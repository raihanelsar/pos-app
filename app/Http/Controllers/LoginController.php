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
            'email'    => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            $roleName = $user->role_name;

            // Tentukan URL redirect berdasarkan role
            switch ($roleName) {
                case 'admin':
                    $redirectUrl = route('dashboard');
                    break;
                case 'kasir':
                    $redirectUrl = route('dashboard');
                    break;
                case 'pimpinan':
                    $redirectUrl = route('dashboard');
                    break;
                default:
                    $redirectUrl = route('dashboard');
                    break;
            }

            return response()->json([
                'success'     => true,
                'message'     => 'Login berhasil',
                'role'        => $roleName,
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
