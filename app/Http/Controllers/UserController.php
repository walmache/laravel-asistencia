<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()?->hasRole(['admin', 'coordinator'])) abort(403);
            return $next($request);
        });
    }

    public function index()
    {
        return view('users.index', ['users' => User::paginate(15)]);
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,coordinator,user',
        ]);

        User::create(array_merge($validated, ['password' => Hash::make($validated['password'])]));
        return redirect()->route('users.index')->with('success', 'Usuario creado exitosamente.');
    }

    public function edit($id)
    {
        return view('users.edit', ['user' => User::findOrFail($id)]);
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:admin,coordinator,user',
        ]);

        if ($request->filled('password')) $validated['password'] = Hash::make($validated['password']);
        else unset($validated['password']);

        $user->update($validated);
        return redirect()->route('users.index')->with('success', 'Usuario actualizado exitosamente.');
    }

    public function destroy($id)
    {
        User::findOrFail($id)->delete();
        return redirect()->route('users.index')->with('success', 'Usuario eliminado exitosamente.');
    }
}
