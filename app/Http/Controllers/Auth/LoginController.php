<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller {
    public function showLoginForm() {
        return view('auth.login');
    }

    public function login(Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            
            // Check if business info exists; if not, redirect to setup
            $businessExists = \App\Models\Business::exists();
            $redirectUrl = $businessExists ? route('admin.dashboard') : route('setup.business');

            return response()->json([
                'message' => 'Login successful',
                'redirect_url' => $redirectUrl
            ]);
        }

        return response()->json([
            'errors' => ['email' => [__('auth.failed')]]
        ], 422);
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}