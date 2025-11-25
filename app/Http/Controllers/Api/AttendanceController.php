<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AttendanceController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        if ($user && $user->hasRole('admin')) {
            $events = Event::with(['organization', 'attendances'])->get();
        } elseif ($user && $user->hasRole('coordinator')) {
            $events = $user->events()->with(['organization', 'attendances'])->get();
        } elseif ($user) {
            $events = $user->events()->with(['organization', 'attendances'])->get();
        } else {
            $events = Event::with(['organization', 'attendances'])->get();
        }

        return response()->json($events);
    }

    public function showEvent($id)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $event = Event::with(['organization', 'users', 'attendances'])->findOrFail($id);

        if ($user->hasRole('user') && !$event->users->contains($user->id)) {
            return response()->json(['error' => 'You do not have access to this event.'], 403);
        }

        return response()->json($event);
    }

    public function registerManual(Request $request, $eventId)
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $event = Event::findOrFail($eventId);
        
        if (!$user->hasRole(['admin', 'coordinator']) && $user->id != $request->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'status' => 'sometimes|in:present,absent,justified',
        ]);
        
        $attendance = Attendance::updateOrCreate(
            [
                'event_id' => $eventId,
                'user_id' => $validated['user_id'],
            ],
            [
                'check_in_at' => now(),
                'method' => 'manual',
                'status' => $validated['status'] ?? 'present',
                'metadata' => [
                    'device' => $request->userAgent(),
                    'ip' => $request->ip(),
                    'registered_by' => $user->id,
                ],
            ]
        );
        
        return response()->json($attendance, 201);
    }

    public function getStatistics()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $stats = [
            'total_events' => Event::count(),
            'total_users' => \App\Models\User::count(),
            'total_attendances' => Attendance::count(),
            'total_organizations' => \App\Models\Organization::count(),
        ];

        return response()->json($stats);
    }
}


