<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Attendance;
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

    public function registerQR(Request $request, $eventId)
    {
        $user = Auth::user();
        if (!$user) return response()->json(['error' => 'Unauthorized'], 401);
        
        $validated = $request->validate(['qr_token' => 'required|string']);
        $userId = $request->user_id;
        
        $attendance = Attendance::updateOrCreate(
            ['event_id' => $eventId, 'user_id' => $userId],
            ['check_in_at' => now(), 'method' => 'qr', 'status' => 'present', 'metadata' => ['device' => $request->userAgent(), 'ip' => $request->ip(), 'qr_token' => $validated['qr_token']]]
        );
        
        return response()->json(['success' => true, 'attendance' => $attendance]);
    }

    public function registerBarcode(Request $request, $eventId)
    {
        $user = Auth::user();
        if (!$user) return response()->json(['error' => 'Unauthorized'], 401);
        
        $validated = $request->validate(['barcode_data' => 'required|string']);
        $userId = $request->user_id;
        
        $attendance = Attendance::updateOrCreate(
            ['event_id' => $eventId, 'user_id' => $userId],
            ['check_in_at' => now(), 'method' => 'barcode', 'status' => 'present', 'metadata' => ['device' => $request->userAgent(), 'ip' => $request->ip(), 'barcode_data' => $validated['barcode_data']]]
        );
        
        return response()->json(['success' => true, 'attendance' => $attendance]);
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
