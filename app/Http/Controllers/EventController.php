<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('organization')->paginate(15);
        return view('events.index', compact('events'));
    }

    public function create()
    {
        $organizations = Organization::all();
        $users = User::all();
        return view('events.create', compact('organizations', 'users'));
    }

    public function store(Request $request)
    {
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
        
        // Attach users to the event
        if (isset($validated['user_ids'])) {
            $event->users()->attach($validated['user_ids']);
        }

        return redirect()->route('events.index')->with('success', 'Event created successfully.');
    }

    public function show($id)
    {
        $event = Event::with(['organization', 'users', 'attendances'])->findOrFail($id);
        return view('events.show', compact('event'));
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);
        $organizations = Organization::all();
        $users = User::all();
        $eventUsers = $event->users->pluck('id')->toArray();
        
        return view('events.edit', compact('event', 'organizations', 'users', 'eventUsers'));
    }

    public function update(Request $request, $id)
    {
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
        
        // Sync users with the event
        if (isset($validated['user_ids'])) {
            $event->users()->sync($validated['user_ids']);
        } else {
            $event->users()->detach();
        }

        return redirect()->route('events.index')->with('success', 'Event updated successfully.');
    }

    public function destroy($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();

        return redirect()->route('events.index')->with('success', 'Event deleted successfully.');
    }

    public function assignUsers(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        
        $validated = $request->validate([
            'user_ids' => 'required|array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $event->users()->syncWithoutDetaching($validated['user_ids']);

        return response()->json(['success' => true, 'message' => 'Users assigned to event successfully.']);
    }

    public function removeUser($eventId, $userId)
    {
        $event = Event::findOrFail($eventId);
        $event->users()->detach($userId);

        return response()->json(['success' => true, 'message' => 'User removed from event successfully.']);
    }
}