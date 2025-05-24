<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function username() {
        return 'username';
    }

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ], [
            'username.required' => 'Please enter your School ID.',
            'password.required' => 'Please enter your password.',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            // Role-based redirection
            $user = Auth::user();
            switch ($user->role) {
                case 'admin':
                    return redirect()->intended(route('admin.dashboard'));
                case 'teacher':
                    return redirect()->intended(route('teachers.dashboard'));
                case 'student':
                    return redirect()->intended(route('student.gradebook'));
                default:
                    return redirect()->intended(route('admin.dashboard'));
            }
        }

        // If authentication fails, return with specific error message
        return back()
            ->withInput($request->only('username', 'remember'))
            ->withErrors([
                'username' => 'These credentials do not match our records.',
            ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
