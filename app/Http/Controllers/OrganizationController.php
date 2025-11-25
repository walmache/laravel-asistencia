<?php

namespace App\Http\Controllers;

use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()?->hasRole('admin')) abort(403);
            return $next($request);
        });
    }

    public function index()
    {
        return view('organizations.index', ['organizations' => Organization::paginate(15)]);
    }

    public function create()
    {
        return view('organizations.create');
    }

    public function store(Request $request)
    {
        Organization::create($request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]));
        return redirect()->route('organizations.index')->with('success', 'Organización creada exitosamente.');
    }

    public function show($id)
    {
        return view('organizations.show', ['organization' => Organization::with('events')->findOrFail($id)]);
    }

    public function edit($id)
    {
        return view('organizations.edit', ['organization' => Organization::findOrFail($id)]);
    }

    public function update(Request $request, $id)
    {
        Organization::findOrFail($id)->update($request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]));
        return redirect()->route('organizations.index')->with('success', 'Organización actualizada exitosamente.');
    }

    public function destroy($id)
    {
        Organization::findOrFail($id)->delete();
        return redirect()->route('organizations.index')->with('success', 'Organización eliminada exitosamente.');
    }
}
