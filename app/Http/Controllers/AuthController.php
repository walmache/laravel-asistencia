<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            
            // Check if it's an AJAX request (by header or wants JSON)
            $isAjax = $request->ajax() || 
                     $request->wantsJson() || 
                     $request->expectsJson() ||
                     $request->header('X-Requested-With') === 'XMLHttpRequest' ||
                     $request->header('Accept') === 'application/json';
            
            if ($isAjax) {
                return response()->json([
                    'success' => true,
                    'redirect' => route('dashboard')
                ]);
            }
            
            return redirect()->intended(route('dashboard'));
        }

        // Return JSON error for AJAX requests
        $isAjax = $request->ajax() || 
                 $request->wantsJson() || 
                 $request->expectsJson() ||
                 $request->header('X-Requested-With') === 'XMLHttpRequest' ||
                 $request->header('Accept') === 'application/json';
        
        if ($isAjax) {
            return response()->json([
                'success' => false,
                'message' => 'Las credenciales proporcionadas no coinciden con nuestros registros.'
            ], 422);
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        if ($request->expectsJson() || $request->wantsJson()) {
            if ($request->user()) {
                $request->user()->currentAccessToken()?->delete();
            }
            return response()->json(['message' => 'Logged out successfully']);
        }
        
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}



