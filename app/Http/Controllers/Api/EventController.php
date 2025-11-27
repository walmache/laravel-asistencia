<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user && $user->hasRole('admin')) {
            $events = Event::with(['organization', 'users', 'attendances'])->latest()->get();
        } elseif ($user && $user->hasRole('coordinator')) {
            $events = $user->events()->with(['organization', 'users', 'attendances'])->latest()->get();
        } elseif ($user) {
            $events = $user->events()->with(['organization', 'users', 'attendances'])->latest()->get();
        } else {
            $events = Event::with(['organization', 'users', 'attendances'])->latest()->get();
        }
        
        return response()->json($events);
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->hasRole(['admin', 'coordinator'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

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
        
        if (isset($validated['user_ids'])) {
            $event->users()->attach($validated['user_ids']);
        }

        return response()->json($event->load(['organization', 'users']), 201);
    }

    public function show($id)
    {
        $user = Auth::user();
        $event = Event::with(['organization', 'users', 'attendances'])->findOrFail($id);

        if ($user && $user->hasRole('user') && !$event->users->contains($user->id)) {
            return response()->json(['error' => 'You do not have access to this event.'], 403);
        }

        return response()->json($event);
    }

    public function update(Request $request, $id)
    {
        $user = Auth::user();
        if (!$user || !$user->hasRole(['admin', 'coordinator'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

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
        
        if (isset($validated['user_ids'])) {
            $event->users()->sync($validated['user_ids']);
        } else {
            $event->users()->detach();
        }

        return response()->json($event->load(['organization', 'users']));
    }

    public function destroy($id)
    {
        $user = Auth::user();
        if (!$user || !$user->hasRole(['admin', 'coordinator'])) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $event = Event::findOrFail($id);
        $event->delete();

        return response()->json(['message' => 'Event deleted successfully'], 200);
    }
}







