<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Menampilkan halaman login
    public function login()
    {
        return view('login');
    }

    // Proses login via AJAX
    public function authLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $role = Auth::user()->role;

            // Tentukan URL redirect berdasarkan role
            if ($role === 'admin') {
                $redirectUrl = route('dashboard');
            } elseif ($role === 'kasir') {
                $redirectUrl = route('transactions.index');
            } elseif ($role === 'pimpinan') {
                $redirectUrl = route('reports.daily');
            } else {
                $redirectUrl = route('dashboard');
            }

            return response()->json([
                'success' => true,
                'role' => $role,
                'redirectUrl' => $redirectUrl,
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Email atau password salah!',
        ], 401);
    }

    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
