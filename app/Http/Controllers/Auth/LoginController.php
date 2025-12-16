<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
        return Auth::user()->role === 'admin'
            ? redirect()->route('admin.dashboard')
            : redirect()->route('student.home');
    }


        return view('auth.login');
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'login'    => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        $login = $data['login'];
        $password = $data['password'];
        $remember = $request->boolean('remember');

        // If it's an email, try email; otherwise, try name (username)
        $attempts = [];

        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            $attempts[] = ['email' => $login, 'password' => $password];
        }

        // Always also try "name" as username
        $attempts[] = ['name' => $login, 'password' => $password];

        foreach ($attempts as $credentials) {
            if (Auth::attempt($credentials, $remember)) {
                $request->session()->regenerate();

                // Later we’ll switch based on role (admin vs student)
                // Redirect based on role
                $user = Auth::user();

                if ($user->role === 'admin') {
                    return redirect()->intended(route('admin.dashboard'));
                }

                return redirect()->intended(route('student.home'));

            }
        }

        return back()->withErrors([
            'login' => 'Invalid username/email or password.',
        ])->onlyInput('login');
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
