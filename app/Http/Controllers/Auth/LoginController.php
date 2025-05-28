<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User;

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
            'username.required' => 'Please enter your ID Number.',
            'password.required' => 'Please enter your password.',
        ]);

        // Check if the username exists
        $user = User::where('username', $request->username)->first();
        if (!$user) {
            return back()
                ->withInput($request->only('username', 'remember'))
                ->withErrors([
                    'username' => 'The ID number you entered is incorrect.',
                ]);
        }

        // If username exists, try to authenticate
        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()
                ->withInput($request->only('username', 'remember'))
                ->withErrors([
                    'password' => 'The password you entered is incorrect.',
                ]);
        }

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
            case 'principal':
                return redirect()->intended(route('admin.dashboard'));
            default:
                return redirect()->intended(route('admin.dashboard'));
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }
}
