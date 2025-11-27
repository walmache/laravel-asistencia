<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Attendance;
use App\Models\EventRegistration;
use App\Services\FaceRecognitionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AttendanceController extends Controller
{
    protected $faceRecognitionService;

    public function __construct(FaceRecognitionService $faceRecognitionService)
    {
        $this->faceRecognitionService = $faceRecognitionService;
    }

    public function index()
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');
        
        $events = match(true) {
            $user->hasRole('admin') => Event::with(['organization', 'attendances'])->get(),
            $user->hasRole('coordinator') => $user->events()->with(['organization', 'attendances'])->get(),
            $user->hasRole('user') => $user->events()->with(['organization', 'attendances'])->get(),
            default => collect()
        };

        return $user->hasRole('user') 
            ? view('attendance.user-events', compact('events'))
            : view('attendance.index', compact('events'));
    }

    public function showEvent($id)
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');
        
        $event = Event::with(['organization', 'users', 'attendances'])->findOrFail($id);
        if ($user->hasRole('user') && !$event->users->contains($user->id)) abort(403);
        
        return view('attendance.event', [
            'event' => $event,
            'users' => $event->users,
            'attendances' => $event->attendances
        ]);
    }

    public function registerManual(Request $request, $eventId)
    {
        $user = Auth::user();
        if (!$user) return response()->json(['error' => 'Unauthorized'], 401);
        
        $event = Event::findOrFail($eventId);
        if (!$user->hasRole(['admin', 'coordinator']) && $user->id != $request->user_id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        
        $validated = $request->validate(['user_id' => 'required|exists:users,id', 'status' => 'sometimes|in:present,absent,justified']);
        
        $attendance = Attendance::updateOrCreate(
            ['event_id' => $eventId, 'user_id' => $validated['user_id']],
            ['check_in_at' => now(), 'method' => 'manual', 'status' => $validated['status'] ?? 'present', 'metadata' => ['device' => $request->userAgent(), 'ip' => $request->ip(), 'registered_by' => $user->id]]
        );
        
        return response()->json(['success' => true, 'attendance' => $attendance]);
    }

    /**
     * Registro rápido de asistencia desde la tabla de participantes
     * Permite registrar con cualquier método (manual, qr, barcode)
     */
    public function quickRegister(Request $request, $eventId)
    {
        $user = Auth::user();
        if (!$user) return response()->json(['error' => 'Unauthorized'], 401);
        
        if (!$user->hasRole(['admin', 'coordinator'])) {
            return response()->json(['error' => 'No tiene permisos para registrar asistencia'], 403);
        }
        
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'method' => 'required|in:manual,qr,barcode,face',
            'status' => 'sometimes|in:present,absent,justified'
        ]);
        
        // Verificar que el usuario esté inscrito en el evento
        $event = Event::findOrFail($eventId);
        if (!$event->users->contains($validated['user_id'])) {
            return response()->json(['error' => 'El usuario no está inscrito en este evento'], 400);
        }
        
        // Verificar si ya tiene asistencia registrada
        $existingAttendance = Attendance::where('event_id', $eventId)
            ->where('user_id', $validated['user_id'])
            ->first();
            
        if ($existingAttendance) {
            return response()->json(['error' => 'El usuario ya tiene asistencia registrada'], 400);
        }
        
        $attendance = Attendance::create([
            'event_id' => $eventId,
            'user_id' => $validated['user_id'],
            'check_in_at' => now(),
            'method' => $validated['method'],
            'status' => $validated['status'] ?? 'present',
            'metadata' => [
                'device' => $request->userAgent(),
                'ip' => $request->ip(),
                'registered_by' => $user->id,
                'registered_by_name' => $user->name,
                'quick_register' => true
            ]
        ]);
        
        return response()->json(['success' => true, 'attendance' => $attendance]);
    }

    /**
     * Anular/eliminar una asistencia registrada
     */
    public function destroy($attendanceId)
    {
        $user = Auth::user();
        if (!$user) return response()->json(['error' => 'Unauthorized'], 401);
        
        if (!$user->hasRole(['admin', 'coordinator'])) {
            return response()->json(['error' => 'No tiene permisos para anular asistencias'], 403);
        }
        
        $attendance = Attendance::findOrFail($attendanceId);
        $attendance->delete();
        
        return response()->json(['success' => true, 'message' => 'Asistencia anulada correctamente']);
    }

    /**
     * Registra asistencia escaneando código QR único del participante
     * 
     * El código QR está vinculado a una inscripción específica (usuario + evento)
     * y solo puede ser usado una vez (o por sesión en eventos con múltiples sesiones)
     */
    public function registerQR(Request $request, $eventId)
    {
        $user = Auth::user();
        if (!$user) return response()->json(['error' => 'Unauthorized'], 401);
        
        $validated = $request->validate([
            'qr_code' => 'required|string|uuid',
            'session_id' => 'nullable|exists:event_sessions,id',
        ]);
        
        // Buscar la inscripción por código QR
        $registration = EventRegistration::findByQrCode($validated['qr_code']);
        
        if (!$registration) {
            return response()->json([
                'success' => false,
                'error' => 'Código QR no válido o no encontrado.'
            ], 404);
        }
        
        // Verificar que el código pertenece a este evento
        if ($registration->event_id != $eventId) {
            return response()->json([
                'success' => false,
                'error' => 'Este código QR no corresponde a este evento.'
            ], 400);
        }
        
        // Usar el código y registrar asistencia
        $result = $registration->useCode('qr', $validated['session_id'] ?? null);
        
        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'error' => $result['message']
            ], 400);
        }
        
        return response()->json([
            'success' => true,
            'message' => $result['message'],
            'attendance' => $result['attendance'],
            'user' => $registration->user->only(['id', 'name', 'email']),
        ]);
    }

    /**
     * Registra asistencia escaneando código de barras único del participante
     */
    public function registerBarcode(Request $request, $eventId)
    {
        $user = Auth::user();
        if (!$user) return response()->json(['error' => 'Unauthorized'], 401);
        
        $validated = $request->validate([
            'barcode' => 'required|string|max:15',
            'session_id' => 'nullable|exists:event_sessions,id',
        ]);
        
        // Buscar la inscripción por código de barras
        $registration = EventRegistration::findByBarcode($validated['barcode']);
        
        if (!$registration) {
            return response()->json([
                'success' => false,
                'error' => 'Código de barras no válido o no encontrado.'
            ], 404);
        }
        
        // Verificar que el código pertenece a este evento
        if ($registration->event_id != $eventId) {
            return response()->json([
                'success' => false,
                'error' => 'Este código de barras no corresponde a este evento.'
            ], 400);
        }
        
        // Usar el código y registrar asistencia
        $result = $registration->useCode('barcode', $validated['session_id'] ?? null);
        
        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'error' => $result['message']
            ], 400);
        }
        
        return response()->json([
            'success' => true,
            'message' => $result['message'],
            'attendance' => $result['attendance'],
            'user' => $registration->user->only(['id', 'name', 'email']),
        ]);
    }

    /**
     * Escanea cualquier código (QR o barras) y detecta automáticamente el tipo
     */
    public function scanCode(Request $request, $eventId)
    {
        $user = Auth::user();
        if (!$user) return response()->json(['error' => 'Unauthorized'], 401);
        
        $validated = $request->validate([
            'code' => 'required|string',
            'session_id' => 'nullable|exists:event_sessions,id',
        ]);
        
        $code = trim($validated['code']);
        $sessionId = $validated['session_id'] ?? null;
        
        // Detectar tipo de código
        // UUID (36 chars con guiones) = QR
        // Formato EXXXXUXXXXAAAA (15 chars) = Barcode
        $registration = null;
        $method = 'unknown';
        
        if (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i', $code)) {
            // Es un UUID (código QR)
            $registration = EventRegistration::findByQrCode($code);
            $method = 'qr';
        } elseif (preg_match('/^E\d{4}U\d{4}[A-Z0-9]{4}$/', $code)) {
            // Es un código de barras
            $registration = EventRegistration::findByBarcode($code);
            $method = 'barcode';
        } else {
            // Intentar buscar en ambos campos
            $registration = EventRegistration::where('qr_code', $code)
                ->orWhere('barcode', $code)
                ->first();
            $method = $registration ? ($registration->qr_code === $code ? 'qr' : 'barcode') : 'unknown';
        }
        
        if (!$registration) {
            return response()->json([
                'success' => false,
                'error' => 'Código no válido o no encontrado.'
            ], 404);
        }
        
        // Verificar que el código pertenece a este evento
        if ($registration->event_id != $eventId) {
            return response()->json([
                'success' => false,
                'error' => 'Este código no corresponde a este evento.'
            ], 400);
        }
        
        // Usar el código y registrar asistencia
        $result = $registration->useCode($method, $sessionId);
        
        if (!$result['success']) {
            return response()->json([
                'success' => false,
                'error' => $result['message']
            ], 400);
        }
        
        return response()->json([
            'success' => true,
            'message' => $result['message'],
            'attendance' => $result['attendance'],
            'user' => $registration->user->only(['id', 'name', 'email']),
            'method_detected' => $method,
        ]);
    }

    public function registerFace(Request $request, $eventId)
    {
        $user = Auth::user();
        if (!$user) return response()->json(['success' => false, 'error' => 'Unauthorized'], 401);
        
        $request->validate(['image' => 'required|image|mimes:jpeg,png,jpg|max:2048']);
        
        $imagePath = $request->file('image')->store('temp', 'local');
        $result = $this->faceRecognitionService->verifyFace($eventId, storage_path('app/' . $imagePath));
        Storage::delete($imagePath);
        
        if (isset($result['error'])) {
            return response()->json(['success' => false, 'error' => $result['message'] ?? 'Face recognition service unavailable'], 500);
        }
        
        if (!$result['match']) {
            return response()->json(['success' => false, 'message' => 'No face match found'], 400);
        }
        
        $attendance = Attendance::updateOrCreate(
            ['event_id' => $eventId, 'user_id' => $result['user_id']],
            ['check_in_at' => now(), 'method' => 'face_recognition', 'status' => 'present', 'metadata' => ['device' => $request->userAgent(), 'ip' => $request->ip(), 'confidence' => $result['confidence'], 'threshold_used' => $result['threshold_used']]]
        );
        
        return response()->json(['success' => true, 'attendance' => $attendance, 'user_id' => $result['user_id'], 'confidence' => $result['confidence']]);
    }

    public function generateQRCode($eventId)
    {
        if (!Auth::user()?->hasRole(['admin', 'coordinator'])) abort(403);
        
        $event = Event::findOrFail($eventId);
        $qrData = json_encode(['event_id' => $event->id, 'timestamp' => now()->toISOString(), 'valid_for' => 3600]);
        
        $renderer = new \BaconQrCode\Renderer\Image\SvgImageBackEnd();
        $writer = new \BaconQrCode\Writer(new \BaconQrCode\Renderer\ImageRenderer(new \BaconQrCode\Renderer\RendererStyle\RendererStyle(200), $renderer));
        $qrCode = $writer->writeString($qrData);
        
        $fileName = 'qrcodes/event_' . $event->id . '_' . time() . '.svg';
        Storage::put($fileName, $qrCode);
        $event->update(['qr_code_path' => $fileName]);
        
        return response($qrCode)->header('Content-Type', 'image/svg+xml');
    }

    public function generateBarcode($eventId)
    {
        if (!Auth::user()?->hasRole(['admin', 'coordinator'])) abort(403);
        
        $event = Event::findOrFail($eventId);
        $barcodeData = 'EVT' . str_pad($event->id, 8, '0', STR_PAD_LEFT);
        
        $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
        $barcode = $generator->getBarcode($barcodeData, $generator::TYPE_CODE_128);
        
        $fileName = 'barcodes/event_' . $event->id . '_' . time() . '.png';
        Storage::put($fileName, $barcode);
        $event->update(['barcode_code' => $barcodeData]);
        
        return response($barcode)->header('Content-Type', 'image/png');
    }

    public function uploadFaceImage(Request $request)
    {
        $user = Auth::user();
        if (!$user) return response()->json(['error' => 'Unauthorized'], 401);
        
        $request->validate(['face_image' => 'required|image|mimes:jpeg,png,jpg|max:2048', 'consent' => 'required|boolean']);
        if (!$request->consent) return response()->json(['error' => 'Consent required'], 400);
        
        $user->update(['consent_face_processing' => true]);
        $imagePath = $request->file('face_image')->store('face_images', 'public');
        $result = $this->faceRecognitionService->extractEmbedding(storage_path('app/public/' . $imagePath));
        
        if (isset($result['error'])) {
            return response()->json(['error' => $result['message'] ?? 'Face embedding extraction failed'], 500);
        }
        
        $user->update(['face_embedding' => $result['embedding'], 'face_image_path' => $imagePath]);
        return response()->json(['success' => true, 'message' => 'Face image uploaded and embedding extracted successfully']);
    }
}
