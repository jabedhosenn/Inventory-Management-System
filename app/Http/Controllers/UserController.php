<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function dashboard()
    {
        if (Auth::check()) {
            if (Auth::user()->user_type === 'admin') {
                return view('admin.dashboard');
            } else if (Auth::user()->user_type == 'user') {
                return view('dashboard');
            } else {
                return redirect()->route('login');
            }
        }
        return redirect()->route('login');
    }
}
