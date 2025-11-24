<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\User;
use App\Models\Attendance;
use App\Services\FaceRecognitionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Picqer\Barcode\BarcodeGeneratorPNG;

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
        
        if ($user->hasRole('admin')) {
            $events = Event::with(['organization', 'attendances'])->get();
        } elseif ($user->hasRole('coordinator')) {
            $events = $user->events()->with(['organization', 'attendances'])->get();
        } else {
            $events = collect(); // Regular users don't see all events
        }

        return view('attendance.index', compact('events'));
    }

    public function showEvent($id)
    {
        $event = Event::with(['organization', 'users', 'attendances'])->findOrFail($id);
        $users = $event->users;
        $attendances = $event->attendances;
        
        return view('attendance.event', compact('event', 'users', 'attendances'));
    }

    public function registerManual(Request $request, $eventId)
    {
        $user = Auth::user();
        $event = Event::findOrFail($eventId);
        
        // Check if user has permission to register attendance
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
        
        return response()->json(['success' => true, 'attendance' => $attendance]);
    }

    public function registerQR(Request $request, $eventId)
    {
        $user = Auth::user();
        $event = Event::findOrFail($eventId);
        
        // Verify that the QR token is valid (in a real app, you'd validate a JWT token)
        $validated = $request->validate([
            'qr_token' => 'required|string',
        ]);
        
        // In a real implementation, you would validate the JWT token here
        // For demo purposes, we'll assume the token is valid and contains user_id
        
        // Extract user ID from the token (simplified for this example)
        $userId = $request->user_id; // This should come from decoded JWT in real app
        
        $attendance = Attendance::updateOrCreate(
            [
                'event_id' => $eventId,
                'user_id' => $userId,
            ],
            [
                'check_in_at' => now(),
                'method' => 'qr',
                'status' => 'present',
                'metadata' => [
                    'device' => $request->userAgent(),
                    'ip' => $request->ip(),
                    'qr_token' => $validated['qr_token'],
                ],
            ]
        );
        
        return response()->json(['success' => true, 'attendance' => $attendance]);
    }

    public function registerBarcode(Request $request, $eventId)
    {
        $user = Auth::user();
        $event = Event::findOrFail($eventId);
        
        $validated = $request->validate([
            'barcode_data' => 'required|string',
        ]);
        
        // In a real implementation, you would decode the barcode to get user ID
        // For demo purposes, we'll assume the barcode contains user_id
        $userId = $request->user_id; // This should come from decoded barcode in real app
        
        $attendance = Attendance::updateOrCreate(
            [
                'event_id' => $eventId,
                'user_id' => $userId,
            ],
            [
                'check_in_at' => now(),
                'method' => 'barcode',
                'status' => 'present',
                'metadata' => [
                    'device' => $request->userAgent(),
                    'ip' => $request->ip(),
                    'barcode_data' => $validated['barcode_data'],
                ],
            ]
        );
        
        return response()->json(['success' => true, 'attendance' => $attendance]);
    }

    public function registerFace(Request $request, $eventId)
    {
        $user = Auth::user();
        $event = Event::findOrFail($eventId);
        
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
        
        // Store the captured image temporarily
        $image = $request->file('image');
        $imagePath = $image->store('temp', 'local');
        
        // Call the Python face recognition service
        $result = $this->faceRecognitionService->verifyFace($eventId, storage_path('app/' . $imagePath));
        
        // Clean up the temporary image
        Storage::delete($imagePath);
        
        if (isset($result['error'])) {
            return response()->json([
                'success' => false,
                'error' => $result['message'] ?? 'Face recognition service unavailable'
            ], 500);
        }
        
        if (!$result['match']) {
            return response()->json([
                'success' => false,
                'message' => 'No face match found'
            ], 400);
        }
        
        // Register attendance for the matched user
        $attendance = Attendance::updateOrCreate(
            [
                'event_id' => $eventId,
                'user_id' => $result['user_id'],
            ],
            [
                'check_in_at' => now(),
                'method' => 'face_recognition',
                'status' => 'present',
                'metadata' => [
                    'device' => $request->userAgent(),
                    'ip' => $request->ip(),
                    'confidence' => $result['confidence'],
                    'threshold_used' => $result['threshold_used'],
                ],
            ]
        );
        
        return response()->json([
            'success' => true,
            'attendance' => $attendance,
            'user_id' => $result['user_id'],
            'confidence' => $result['confidence']
        ]);
    }

    public function generateQRCode($eventId)
    {
        $event = Event::findOrFail($eventId);
        
        // Generate a JWT token for this event (simplified for this example)
        // In a real app, you would generate a proper JWT
        $qrData = json_encode([
            'event_id' => $event->id,
            'timestamp' => now()->toISOString(),
            'valid_for' => 3600 // Valid for 1 hour
        ]);
        
        $renderer = new ImageRenderer(
            new RendererStyle(200),
            new SvgImageBackEnd()
        );
        $writer = new Writer($renderer);
        $qrCode = $writer->writeString($qrData);
        
        // Save QR code to storage
        $fileName = 'qrcodes/event_' . $event->id . '_' . time() . '.svg';
        Storage::put($fileName, $qrCode);
        
        $event->update(['qr_code_path' => $fileName]);
        
        return response($qrCode)->header('Content-Type', 'image/svg+xml');
    }

    public function generateBarcode($eventId)
    {
        $event = Event::findOrFail($eventId);
        $generator = new BarcodeGeneratorPNG();
        
        // Generate a unique barcode for this event
        $barcodeData = 'EVT' . str_pad($event->id, 8, '0', STR_PAD_LEFT);
        
        $barcode = $generator->getBarcode($barcodeData, $generator::TYPE_CODE_128);
        
        // Save barcode to storage
        $fileName = 'barcodes/event_' . $event->id . '_' . time() . '.png';
        Storage::put($fileName, $barcode);
        
        $event->update(['barcode_code' => $barcodeData]);
        
        return response($barcode)->header('Content-Type', 'image/png');
    }

    public function uploadFaceImage(Request $request)
    {
        $user = Auth::user();
        
        $request->validate([
            'face_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'consent' => 'required|boolean',
        ]);
        
        if (!$request->consent) {
            return response()->json(['error' => 'Consent required for face processing'], 400);
        }
        
        // Update user's consent
        $user->update(['consent_face_processing' => true]);
        
        // Store the face image
        $image = $request->file('face_image');
        $imagePath = $image->store('face_images', 'public');
        
        // Extract face embedding using Python service
        $result = $this->faceRecognitionService->extractEmbedding(storage_path('app/public/' . $imagePath));
        
        if (isset($result['error'])) {
            return response()->json([
                'error' => $result['message'] ?? 'Face embedding extraction failed'
            ], 500);
        }
        
        // Update user with face embedding and image path
        $user->update([
            'face_embedding' => $result['embedding'],
            'face_image_path' => $imagePath,
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Face image uploaded and embedding extracted successfully'
        ]);
    }
}