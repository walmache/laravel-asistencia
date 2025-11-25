<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Organization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');
        if (!$user->hasRole(['admin', 'coordinator'])) abort(403);
        
        $events = $user->hasRole('admin') 
            ? Event::with('organization')->paginate(15)
            : $user->events()->with('organization')->paginate(15);
        
        return view('events.index', compact('events'));
    }

    public function create()
    {
        if (!Auth::user()?->hasRole(['admin', 'coordinator'])) abort(403);
        return view('events.create', [
            'organizations' => Organization::all(),
            'users' => \App\Models\User::all()
        ]);
    }

    public function store(Request $request)
    {
        if (!Auth::user()?->hasRole(['admin', 'coordinator'])) abort(403);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'organization_id' => 'required|exists:organizations,id',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
            'status' => 'required|in:scheduled,ongoing,finished',
            'face_threshold' => 'numeric|min:0|max:1',
            'allow_face_checkin' => 'boolean',
            'user_ids' => 'array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $event = Event::create($validated);
        if (isset($validated['user_ids'])) $event->users()->attach($validated['user_ids']);

        return redirect()->route('events.index')->with('success', 'Evento creado exitosamente.');
    }

    public function show($id)
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');
        
        $event = Event::with(['organization', 'users', 'attendances'])->findOrFail($id);
        if ($user->hasRole('user') && !$event->users->contains($user->id)) abort(403);
        
        return view('events.show', compact('event'));
    }

    public function edit($id)
    {
        if (!Auth::user()?->hasRole(['admin', 'coordinator'])) abort(403);
        
        return view('events.edit', [
            'event' => Event::findOrFail($id),
            'organizations' => Organization::all(),
            'users' => \App\Models\User::all(),
            'eventUsers' => Event::findOrFail($id)->users->pluck('id')->toArray()
        ]);
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()?->hasRole(['admin', 'coordinator'])) abort(403);
        
        $event = Event::findOrFail($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'organization_id' => 'required|exists:organizations,id',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
            'status' => 'required|in:scheduled,ongoing,finished',
            'face_threshold' => 'numeric|min:0|max:1',
            'allow_face_checkin' => 'boolean',
            'user_ids' => 'array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $event->update($validated);
        isset($validated['user_ids']) ? $event->users()->sync($validated['user_ids']) : $event->users()->detach();

        return redirect()->route('events.index')->with('success', 'Evento actualizado exitosamente.');
    }

    public function destroy($id)
    {
        if (!Auth::user()?->hasRole(['admin', 'coordinator'])) abort(403);
        Event::findOrFail($id)->delete();
        return redirect()->route('events.index')->with('success', 'Evento eliminado exitosamente.');
    }

    public function assignUsers(Request $request, $eventId)
    {
        if (!Auth::user()?->hasRole(['admin', 'coordinator'])) abort(403);
        Event::findOrFail($eventId)->users()->syncWithoutDetaching($request->validate(['user_ids' => 'required|array', 'user_ids.*' => 'exists:users,id'])['user_ids']);
        return response()->json(['success' => true]);
    }

    public function removeUser($eventId, $userId)
    {
        if (!Auth::user()?->hasRole(['admin', 'coordinator'])) abort(403);
        Event::findOrFail($eventId)->users()->detach($userId);
        return response()->json(['success' => true]);
    }
}
