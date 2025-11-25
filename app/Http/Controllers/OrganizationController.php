<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends Controller
{
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('landing');
        }
        
        if (!Auth::user()?->hasRole('admin')) {
            abort(403);
        }
        
        return view('organizations.index', ['organizations' => Organization::paginate(15)]);
    }

    public function create()
    {
        if (!Auth::check()) {
            return redirect()->route('landing');
        }
        
        if (!Auth::user()?->hasRole('admin')) {
            abort(403);
        }
        
        return view('organizations.create');
    }

    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('landing');
        }
        
        if (!Auth::user()?->hasRole('admin')) {
            abort(403);
        }
        
        Organization::create($request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]));
        return redirect()->route('organizations.index')->with('success', 'Organización creada exitosamente.');
    }

    public function show($id)
    {
        if (!Auth::check()) {
            return redirect()->route('landing');
        }
        
        if (!Auth::user()?->hasRole('admin')) {
            abort(403);
        }
        
        return view('organizations.show', ['organization' => Organization::with('events')->findOrFail($id)]);
    }

    public function edit($id)
    {
        if (!Auth::check()) {
            return redirect()->route('landing');
        }
        
        if (!Auth::user()?->hasRole('admin')) {
            abort(403);
        }
        
        return view('organizations.edit', ['organization' => Organization::findOrFail($id)]);
    }

    public function update(Request $request, $id)
    {
        if (!Auth::check()) {
            return redirect()->route('landing');
        }
        
        if (!Auth::user()?->hasRole('admin')) {
            abort(403);
        }
        
        Organization::findOrFail($id)->update($request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]));
        return redirect()->route('organizations.index')->with('success', 'Organización actualizada exitosamente.');
    }

    public function destroy($id)
    {
        if (!Auth::check()) {
            return redirect()->route('landing');
        }
        
        if (!Auth::user()?->hasRole('admin')) {
            abort(403);
        }
        
        Organization::findOrFail($id)->delete();
        return redirect()->route('organizations.index')->with('success', 'Organización eliminada exitosamente.');
    }
}
