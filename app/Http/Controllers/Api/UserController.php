<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user || !$user->hasRole(['admin', 'coordinator'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $users = User::paginate(15);
        return response()->json($users);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->hasRole(['admin', 'coordinator'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,coordinator,user',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        unset($validated['password_confirmation']);

        $newUser = User::create($validated);
        return response()->json($newUser, 201);
    }

    public function show($id)
    {
        $user = Auth::user();
        if (!$user || !$user->hasRole(['admin', 'coordinator'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $targetUser = User::findOrFail($id);
        return response()->json($targetUser);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user || !$user->hasRole(['admin', 'coordinator'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $targetUser = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,coordinator,user',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }
        unset($validated['password_confirmation']);

        $targetUser->update($validated);
        return response()->json($targetUser);
    }

    public function destroy($id)
    {
        $user = Auth::user();
        if (!$user || !$user->hasRole(['admin', 'coordinator'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $targetUser = User::findOrFail($id);
        $targetUser->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}








