<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->role_id == 1) { // Admin
            return view('dashboard.dashboard-admin');
        } elseif ($user->role_id == 2) { // Kasir
            return view('dashboard.dashboard-kasir');
        } elseif ($user->role_id == 3) { // Pimpinan
            return view('dashboard.dashboard-pimpinan');
        } else {
            return redirect()->route('login');
        }
    }
}
