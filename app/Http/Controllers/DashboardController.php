<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Models\Attendance;
use App\Models\Organization;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get events based on user role
        if (!$user) {
            return redirect()->route('landing');
        }
        
        if ($user->hasRole('admin')) {
            // Admin sees all events
            $events = Event::with(['organization', 'users'])->latest()->take(10)->get();
        } elseif ($user->hasRole('coordinator')) {
            // Coordinator sees only events they are assigned to
            $events = $user->events()->with(['organization', 'users'])->latest()->take(10)->get();
        } elseif ($user->hasRole('user')) {
            // Regular user sees only events they are assigned to
            $events = $user->events()->with(['organization', 'users'])->latest()->take(10)->get();
        } else {
            $events = collect();
        }

        // Get statistics
        $total_users = User::count();
        $total_attendances = Attendance::count();
        $total_organizations = Organization::count();

        // Pass user to view explicitly
        return view('dashboard', compact('events', 'total_users', 'total_attendances', 'total_organizations'));
    }
}

