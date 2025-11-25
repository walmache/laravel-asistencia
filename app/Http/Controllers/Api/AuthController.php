<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $credentials = $request->only('email', 'password');
        $remember = $request->boolean('remember', false);

        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();
            $token = $user->createToken('auth_token', ['*'], now()->addDays(30))->plainTextToken;
            
            // Also establish web session for web routes
            $request->session()->regenerate();
            
            // Ensure session is saved and authenticated
            $request->session()->put('authenticated', true);
            $request->session()->save();

            return response()->json([
                'user' => $user->makeHidden(['password', 'remember_token']),
                'token' => $token,
                'token_type' => 'Bearer',
            ]);
        }

        return response()->json(['error' => 'Invalid credentials'], 401);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}

