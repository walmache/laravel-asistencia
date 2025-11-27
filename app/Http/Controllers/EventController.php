<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Category;
use App\Models\Organization;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');
        if (!$user->hasRole(['admin', 'coordinator'])) abort(403);
        
        $events = $user->hasRole('admin') 
            ? Event::with('organization', 'category')->paginate(15)
            : $user->events()->with('organization', 'category')->paginate(15);
        
        return view('events.index', compact('events'));
    }

    public function create()
    {
        if (!Auth::user()?->hasRole(['admin', 'coordinator'])) abort(403);
        return view('events.create', [
            'organizations' => Organization::all(),
            'categories' => Category::where('is_active', true)->get(),
            'users' => User::all()
        ]);
    }

    public function store(Request $request)
    {
        if (!Auth::user()?->hasRole(['admin', 'coordinator'])) abort(403);
        
        // Normalizar checkboxes (HTML no envía 'false', solo omite el campo)
        $request->merge([
            'is_free' => $request->has('is_free'),
            'provides_certificate' => $request->has('provides_certificate'),
            'waitlist_enabled' => $request->has('waitlist_enabled'),
            'requires_approval' => $request->has('requires_approval'),
            'is_public' => $request->has('is_public'),
            'is_open_enrollment' => $request->has('is_open_enrollment'),
            'featured' => $request->has('featured'),
            'allow_face_checkin' => $request->has('allow_face_checkin'),
        ]);

        $validated = $request->validate([
            // Información Básica
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'required|string',
            'organization_id' => 'nullable|exists:organizations,id',
            'category_id' => 'nullable|exists:categories,id',
            'event_type' => 'required|in:presencial,virtual,hibrido',
            'status' => 'required|in:borrador,publicado,cancelado,completado',
            
            // Fechas
            'start_date' => 'required|date|after:now',
            'end_date' => 'required|date|after:start_date',
            'registration_start' => 'required|date|before:start_date',
            'registration_deadline' => 'required|date|after:registration_start|before_or_equal:start_date',
            'early_bird_deadline' => 'nullable|date|before:registration_deadline',
            
            // Precios
            'is_free' => 'boolean',
            'price' => 'required_if:is_free,false|nullable|numeric|min:0',
            'currency' => 'required|string|size:3',
            'early_bird_price' => 'nullable|numeric|min:0',
            'group_price' => 'nullable|numeric|min:0',
            'max_group_size' => 'nullable|integer|min:2',
            
            // Certificación
            'provides_certificate' => 'boolean',
            'certificate_type' => 'nullable|required_if:provides_certificate,true|string',
            'certificate_hours' => 'nullable|required_if:provides_certificate,true|integer|min:1',
            'min_attendance_percentage' => 'required|integer|min:0|max:100',
            
            // Ubicación
            'location_type' => 'required|in:presencial,virtual,hibrido',
            'physical_address' => 'nullable|required_if:location_type,presencial,hibrido|string',
            'room_number' => 'nullable|string',
            'virtual_platform' => 'nullable|string',
            'virtual_link' => 'nullable|required_if:location_type,virtual,hibrido|url',
            'virtual_password' => 'nullable|string',
            
            // Capacidad
            'capacity' => 'nullable|integer|min:1',
            'waitlist_enabled' => 'boolean',
            'max_waitlist' => 'nullable|required_if:waitlist_enabled,true|integer|min:1',
            'requires_approval' => 'boolean',
            
            // Contacto
            'contact_email' => 'required|email',
            'contact_phone' => 'nullable|string',
            
            // Configuración extra
            'is_public' => 'boolean',
            'is_open_enrollment' => 'boolean',
            'featured' => 'boolean',
            'published_at' => 'nullable|date',
            'face_threshold' => 'numeric|min:0|max:1',
            'allow_face_checkin' => 'boolean',
            
            // Políticas
            'cancellation_policy' => 'nullable|string',
            'refund_policy' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            
            // Multimedia
            'featured_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'brochure_file' => 'nullable|mimes:pdf|max:5120',
            
            // Usuarios asignados
            'user_ids' => 'array',
            'user_ids.*' => 'exists:users,id',
        ]);

        // Manejar subida de imagen principal
        if ($request->hasFile('featured_image')) {
            $validated['featured_image'] = $request->file('featured_image')->store('events/images', 'public');
        }
        
        // Manejar subida de brochure
        if ($request->hasFile('brochure_file')) {
            $validated['brochure_file'] = $request->file('brochure_file')->store('events/brochures', 'public');
        }

        $event = Event::create($validated);
        
        if (isset($validated['user_ids'])) {
            $event->users()->attach($validated['user_ids']);
        }

        return redirect()->route('events.index')->with('success', 'Evento creado exitosamente.');
    }

    public function show($id)
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');
        
        $event = Event::with(['organization', 'category', 'users', 'attendances'])->findOrFail($id);
        if ($user->hasRole('user') && !$event->users->contains($user->id)) abort(403);
        
        return view('events.show', compact('event'));
    }

    public function edit($id)
    {
        if (!Auth::user()?->hasRole(['admin', 'coordinator'])) abort(403);
        
        return view('events.edit', [
            'event' => Event::findOrFail($id),
            'organizations' => Organization::all(),
            'categories' => Category::all(),
            'users' => User::all(),
            'eventUsers' => Event::findOrFail($id)->users->pluck('id')->toArray()
        ]);
    }

    public function update(Request $request, $id)
    {
        if (!Auth::user()?->hasRole(['admin', 'coordinator'])) abort(403);
        
        $event = Event::findOrFail($id);
        
        // Normalizar checkboxes
        $request->merge([
            'is_free' => $request->has('is_free'),
            'provides_certificate' => $request->has('provides_certificate'),
            'waitlist_enabled' => $request->has('waitlist_enabled'),
            'requires_approval' => $request->has('requires_approval'),
            'is_public' => $request->has('is_public'),
            'is_open_enrollment' => $request->has('is_open_enrollment'),
            'featured' => $request->has('featured'),
            'allow_face_checkin' => $request->has('allow_face_checkin'),
        ]);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_description' => 'nullable|string|max:500',
            'description' => 'required|string',
            'organization_id' => 'nullable|exists:organizations,id',
            'category_id' => 'nullable|exists:categories,id',
            'event_type' => 'required|in:presencial,virtual,hibrido',
            'status' => 'required|in:borrador,publicado,cancelado,completado',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'registration_start' => 'required|date',
            'registration_deadline' => 'required|date',
            'early_bird_deadline' => 'nullable|date',
            'is_free' => 'boolean',
            'price' => 'required_if:is_free,false|nullable|numeric|min:0',
            'currency' => 'required|string|size:3',
            'early_bird_price' => 'nullable|numeric|min:0',
            'group_price' => 'nullable|numeric|min:0',
            'max_group_size' => 'nullable|integer|min:2',
            'provides_certificate' => 'boolean',
            'certificate_type' => 'nullable|required_if:provides_certificate,true|string',
            'certificate_hours' => 'nullable|required_if:provides_certificate,true|integer|min:1',
            'min_attendance_percentage' => 'required|integer|min:0|max:100',
            'location_type' => 'required|in:presencial,virtual,hibrido',
            'physical_address' => 'nullable|required_if:location_type,presencial,hibrido|string',
            'room_number' => 'nullable|string',
            'virtual_platform' => 'nullable|string',
            'virtual_link' => 'nullable|required_if:location_type,virtual,hibrido|url',
            'virtual_password' => 'nullable|string',
            'capacity' => 'nullable|integer|min:1',
            'waitlist_enabled' => 'boolean',
            'max_waitlist' => 'nullable|required_if:waitlist_enabled,true|integer|min:1',
            'requires_approval' => 'boolean',
            'contact_email' => 'required|email',
            'contact_phone' => 'nullable|string',
            'is_public' => 'boolean',
            'is_open_enrollment' => 'boolean',
            'featured' => 'boolean',
            'published_at' => 'nullable|date',
            'face_threshold' => 'numeric|min:0|max:1',
            'allow_face_checkin' => 'boolean',
            'cancellation_policy' => 'nullable|string',
            'refund_policy' => 'nullable|string',
            'terms_conditions' => 'nullable|string',
            'user_ids' => 'array',
            'user_ids.*' => 'exists:users,id',
        ]);

        $event->update($validated);
        
        if (isset($validated['user_ids'])) {
            $event->users()->sync($validated['user_ids']);
        } else {
            $event->users()->detach();
        }

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
