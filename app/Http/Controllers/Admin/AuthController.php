<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Show dashboard (protected)
    public function dashboard()
    {
        // Ensure admin is authenticated
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        return view('admin.auth.dashboard', [
            'admin' => Auth::guard('admin')->user()
        ]);
    }

    // Show registration form
    public function showRegister()
    {
        return view('admin.auth.register');
    }

    // Handle registration
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|confirmed|min:8',
        ]);

        $admin = Admin::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Automatically log in after registration
        Auth::guard('admin')->login($admin);

        return redirect()->route('admin.showdashboard')
               ->with('success', 'Registration successful!');
    }

    // Show login form
    public function showLogin()
    {

        if (Auth::guard('admin')->check()) {
            return redirect()->route('admin.showdashboard');
        }

        return view('admin.auth.login');
    }

    // Handle login
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:8',
        ]);

        $remember = $request->has('remember');

        if (Auth::guard('admin')->attempt([
            'email' => $request->email,
            'password' => $request->password
        ], $remember)) {
            $request->session()->regenerate();
            return redirect()->intended(route('admin.showdashboard'))
                   ->with('success', 'Login successful!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    // Handle logout
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
               ->with('success', 'You have been logged out!');
    }
}
