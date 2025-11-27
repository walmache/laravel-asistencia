@extends('layouts.adminlte')

@section('title', ($event->title ?? 'Evento') . ' - Asistencia')
@section('page-title', 'Registro de Asistencia')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card border border-dark mt-3">
            <div class="card-header bg-secondary bg-opacity-25 border-bottom border-dark d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0"><i class="fas fa-calendar-check me-2"></i>{{ $event->title ?? 'Evento' }}</h3>
                <a href="{{ route('attendance.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Volver
                </a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="mb-1"><strong><i class="fas fa-building me-1 text-muted"></i>Organización:</strong> {{ $event->organization->name ?? 'Evento de Plataforma' }}</p>
                                <p class="mb-1"><strong><i class="fas fa-tags me-1 text-muted"></i>Categoría:</strong> {{ $event->category->name ?? 'Sin categoría' }}</p>
                                <p class="mb-1"><strong><i class="fas fa-map-marker-alt me-1 text-muted"></i>Modalidad:</strong> 
                                    <span class="badge bg-{{ $event->event_type == 'presencial' ? 'primary' : ($event->event_type == 'virtual' ? 'success' : 'info') }}">
                                        {{ ucfirst($event->event_type) }}
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <p class="mb-1"><strong><i class="fas fa-play-circle me-1 text-muted"></i>Inicio:</strong> {{ $event->start_date->format('d/m/Y H:i') }}</p>
                                <p class="mb-1"><strong><i class="fas fa-stop-circle me-1 text-muted"></i>Fin:</strong> {{ $event->end_date->format('d/m/Y H:i') }}</p>
                                <p class="mb-1"><strong><i class="fas fa-toggle-on me-1 text-muted"></i>Estado:</strong> 
                                    <span class="badge bg-{{ $event->status == 'borrador' ? 'secondary' : ($event->status == 'publicado' ? 'success' : ($event->status == 'cancelado' ? 'danger' : 'info')) }}">
                                        {{ ucfirst($event->status) }}
                                    </span>
                                </p>
                            </div>
                        </div>
                        <hr>
                        <div class="row text-center">
                            <div class="col-4">
                                <h4 class="mb-0 text-primary">{{ $users->count() }}</h4>
                                <small class="text-muted">Inscritos</small>
                            </div>
                            <div class="col-4">
                                <h4 class="mb-0 text-success">{{ $attendances->count() }}</h4>
                                <small class="text-muted">Presentes</small>
                            </div>
                            <div class="col-4">
                                <h4 class="mb-0 text-danger">{{ $users->count() - $attendances->count() }}</h4>
                                <small class="text-muted">Ausentes</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h6 class="border-bottom pb-2 mb-3"><i class="fas fa-cog me-1"></i>Registro Masivo</h6>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#manualCheckinModal">
                                <i class="fas fa-check-circle me-1"></i> Registro Manual
                            </button>
                            <button type="button" class="btn btn-outline-success btn-sm" data-bs-toggle="modal" data-bs-target="#qrCheckinModal">
                                <i class="fas fa-qrcode me-1"></i> Escanear QR
                            </button>
                            <button type="button" class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#barcodeCheckinModal">
                                <i class="fas fa-barcode me-1"></i> Escanear Código de Barras
                            </button>
                            @if($event->allow_face_checkin)
                            <button type="button" class="btn btn-outline-warning btn-sm" data-bs-toggle="modal" data-bs-target="#faceCheckinModal">
                                <i class="fas fa-user-check me-1"></i> Reconocimiento Facial
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    <div class="col-12">
        <div class="card border border-dark">
            <div class="card-header bg-secondary bg-opacity-25 border-bottom border-dark d-flex justify-content-between align-items-center">
                <h3 class="card-title mb-0"><i class="fas fa-users me-2"></i>Participantes del Evento</h3>
                <span class="badge bg-primary">{{ $users->count() }} inscritos</span>
            </div>
            <div class="card-body table-responsive p-3">
                <table class="table table-hover text-nowrap table-bordered border-dark table-sm datatable">
                    <thead class="text-center table-light">
                        <tr>
                            <th style="width: 50px;">#</th>
                            <th>Participante</th>
                            <th>Email</th>
                            <th>Organización</th>
                            <th>Estado</th>
                            <th>Hora Registro</th>
                            <th>Método</th>
                            <th style="width: 220px;">Opciones de Registro</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users ?? [] as $index => $user)
                            @php $attendance = $attendances->where('user_id', $user->id)->first(); @endphp
                        <tr class="{{ $attendance ? 'table-success' : '' }}">
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($user->avatar_url)
                                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="rounded-circle me-2" style="width: 32px; height: 32px; object-fit: cover;">
                                    @else
                                        <i class="fas fa-user-circle fa-2x me-2 text-secondary"></i>
                                    @endif
                                    <div>
                                        <strong>{{ $user->name }}</strong>
                                        <br><small class="text-muted">{{ ucfirst($user->role) }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>{{ $user->email }}</td>
                            <td>
                                @if($user->organization)
                                    <span class="badge bg-light text-dark border">{{ $user->organization->name }}</span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($attendance)
                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i>Presente</span>
                                @else
                                    <span class="badge bg-danger"><i class="fas fa-times me-1"></i>Ausente</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($attendance)
                                    {{ $attendance->check_in_at->format('d/m/Y H:i') }}
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if($attendance)
                                    @php
                                        $methodIcons = [
                                            'manual' => 'fa-hand-pointer text-primary',
                                            'qr' => 'fa-qrcode text-success',
                                            'barcode' => 'fa-barcode text-info',
                                            'face' => 'fa-user-check text-warning',
                                        ];
                                        $icon = $methodIcons[$attendance->method] ?? 'fa-question text-secondary';
                                    @endphp
                                    <span class="badge bg-light text-dark border" title="{{ ucfirst($attendance->method) }}">
                                        <i class="fas {{ $icon }} me-1"></i>{{ ucfirst($attendance->method) }}
                                    </span>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                            <td class="text-center">
                                @if(!$attendance)
                                    {{-- Botones de registro para usuarios sin asistencia --}}
                                    <div class="btn-group btn-group-sm" role="group">
                                        <button type="button" class="btn btn-primary" 
                                                onclick="quickRegister({{ $user->id }}, '{{ $user->name }}', 'manual')"
                                                data-bs-toggle="tooltip" title="Registro Manual">
                                            <i class="fas fa-hand-pointer"></i>
                                        </button>
                                        <button type="button" class="btn btn-success" 
                                                onclick="quickRegister({{ $user->id }}, '{{ $user->name }}', 'qr')"
                                                data-bs-toggle="tooltip" title="Código QR">
                                            <i class="fas fa-qrcode"></i>
                                        </button>
                                        <button type="button" class="btn btn-info" 
                                                onclick="quickRegister({{ $user->id }}, '{{ $user->name }}', 'barcode')"
                                                data-bs-toggle="tooltip" title="Código de Barras">
                                            <i class="fas fa-barcode"></i>
                                        </button>
                                        @if($event->allow_face_checkin)
                                        <button type="button" class="btn btn-warning" 
                                                onclick="openFaceModal({{ $user->id }}, '{{ $user->name }}')"
                                                data-bs-toggle="tooltip" title="Reconocimiento Facial">
                                            <i class="fas fa-user-check"></i>
                                        </button>
                                        @endif
                                    </div>
                                @else
                                    {{-- Opción para anular asistencia --}}
                                    <button type="button" class="btn btn-outline-danger btn-sm" 
                                            onclick="cancelAttendance({{ $attendance->id }}, '{{ $user->name }}')"
                                            data-bs-toggle="tooltip" title="Anular Asistencia">
                                        <i class="fas fa-undo me-1"></i>Anular
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal de Confirmación de Registro Rápido --}}
<div class="modal fade" id="quickRegisterModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title"><i class="fas fa-user-check me-2"></i>Confirmar Registro</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-question-circle fa-4x text-primary mb-3"></i>
                <h5>¿Registrar asistencia de <strong id="quickRegisterUserName"></strong>?</h5>
                <p class="text-muted mb-0">Método: <span id="quickRegisterMethod" class="badge bg-secondary"></span></p>
                <input type="hidden" id="quickRegisterUserId">
                <input type="hidden" id="quickRegisterMethodValue">
            </div>
            <div class="modal-footer justify-content-center">
                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">
                    <i class="fas fa-times me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-primary btn-sm" onclick="confirmQuickRegister()">
                    <i class="fas fa-check me-1"></i>Confirmar Registro
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal de Reconocimiento Facial para Usuario Específico --}}
<div class="modal fade" id="faceUserModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h5 class="modal-title"><i class="fas fa-user-check me-2"></i>Reconocimiento Facial</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="text-center mb-3">
                    <p>Registrando a: <strong id="faceUserName"></strong></p>
                    <input type="hidden" id="faceUserId">
                </div>
                <div class="text-center">
                    <video id="webcamUser" width="320" height="240" autoplay class="border rounded"></video>
                    <canvas id="canvasUser" width="320" height="240" style="display:none;"></canvas>
                    <img id="capturedImageUser" style="display:none;" class="border rounded" width="320" height="240">
                </div>
                <div class="d-grid gap-2 mt-3">
                    <button type="button" id="captureButtonUser" class="btn btn-primary btn-sm" onclick="captureFaceUser()">
                        <i class="fas fa-camera me-1"></i>Capturar
                    </button>
                    <button type="button" id="retakeButtonUser" class="btn btn-secondary btn-sm" style="display:none;" onclick="retakeFaceUser()">
                        <i class="fas fa-redo me-1"></i>Volver a Tomar
                    </button>
                    <button type="button" id="registerFaceButtonUser" class="btn btn-success btn-sm" style="display:none;" onclick="registerFaceUser()">
                        <i class="fas fa-check me-1"></i>Registrar Asistencia
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

@include('attendance.modals')
@endsection

@push('scripts')
<script>
const eventId = {{ $event->id }};
const event = @json($event);
const users = @json($users);

// Inicializar tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});

// ==========================================
// REGISTRO RÁPIDO (Manual, QR, Barcode)
// ==========================================
function quickRegister(userId, userName, method) {
    document.getElementById('quickRegisterUserId').value = userId;
    document.getElementById('quickRegisterUserName').textContent = userName;
    document.getElementById('quickRegisterMethodValue').value = method;
    
    const methodLabels = {
        'manual': 'Manual',
        'qr': 'Código QR',
        'barcode': 'Código de Barras'
    };
    document.getElementById('quickRegisterMethod').textContent = methodLabels[method] || method;
    
    new bootstrap.Modal(document.getElementById('quickRegisterModal')).show();
}

async function confirmQuickRegister() {
    const userId = document.getElementById('quickRegisterUserId').value;
    const method = document.getElementById('quickRegisterMethodValue').value;
    
    try {
        const response = await axios.post(`/attendance/${eventId}/quick-register`, {
            user_id: userId,
            method: method,
            status: 'present'
        });
        
        if (response.data.success) {
            bootstrap.Modal.getInstance(document.getElementById('quickRegisterModal')).hide();
            showToast('success', 'Asistencia registrada correctamente');
            setTimeout(() => location.reload(), 1000);
        }
    } catch (e) {
        showToast('error', 'Error: ' + (e.response?.data?.error || e.message));
    }
}

// ==========================================
// ANULAR ASISTENCIA
// ==========================================
async function cancelAttendance(attendanceId, userName) {
    if (!confirm(`¿Está seguro de anular la asistencia de ${userName}?`)) return;
    
    try {
        const response = await axios.delete(`/attendance/${attendanceId}`);
        if (response.data.success) {
            showToast('success', 'Asistencia anulada correctamente');
            setTimeout(() => location.reload(), 1000);
        }
    } catch (e) {
        showToast('error', 'Error: ' + (e.response?.data?.error || e.message));
    }
}

// ==========================================
// RECONOCIMIENTO FACIAL POR USUARIO
// ==========================================
let streamUser;
const webcamUser = document.getElementById('webcamUser');
const canvasUser = document.getElementById('canvasUser');
const capturedImageUser = document.getElementById('capturedImageUser');

function openFaceModal(userId, userName) {
    document.getElementById('faceUserId').value = userId;
    document.getElementById('faceUserName').textContent = userName;
    new bootstrap.Modal(document.getElementById('faceUserModal')).show();
}

document.getElementById('faceUserModal').addEventListener('shown.bs.modal', async function() {
    try {
        streamUser = await navigator.mediaDevices.getUserMedia({ video: true });
        webcamUser.srcObject = streamUser;
    } catch (e) {
        alert('Error accediendo a la cámara: ' + e.message);
    }
});

document.getElementById('faceUserModal').addEventListener('hidden.bs.modal', function() {
    if (streamUser) {
        streamUser.getTracks().forEach(track => track.stop());
    }
    retakeFaceUser();
});

function captureFaceUser() {
    canvasUser.getContext('2d').drawImage(webcamUser, 0, 0, 320, 240);
    capturedImageUser.src = canvasUser.toDataURL('image/png');
    webcamUser.style.display = 'none';
    capturedImageUser.style.display = 'block';
    document.getElementById('captureButtonUser').style.display = 'none';
    document.getElementById('retakeButtonUser').style.display = 'block';
    document.getElementById('registerFaceButtonUser').style.display = 'block';
}

function retakeFaceUser() {
    webcamUser.style.display = 'block';
    capturedImageUser.style.display = 'none';
    document.getElementById('captureButtonUser').style.display = 'block';
    document.getElementById('retakeButtonUser').style.display = 'none';
    document.getElementById('registerFaceButtonUser').style.display = 'none';
}

async function registerFaceUser() {
    const userId = document.getElementById('faceUserId').value;
    
    if (!capturedImageUser.src) {
        alert('Capture el rostro primero');
        return;
    }
    
    try {
        const blob = await fetch(capturedImageUser.src).then(r => r.blob());
        const formData = new FormData();
        formData.append('image', blob, 'face.png');
        formData.append('user_id', userId);
        
        const response = await axios.post(`/attendance/${eventId}/face`, formData, {
            headers: { 'Content-Type': 'multipart/form-data' }
        });
        
        if (response.data.success) {
            bootstrap.Modal.getInstance(document.getElementById('faceUserModal')).hide();
            showToast('success', 'Asistencia registrada por reconocimiento facial');
            setTimeout(() => location.reload(), 1000);
        }
    } catch (e) {
        showToast('error', 'Error: ' + (e.response?.data?.error || e.message));
    }
}

// ==========================================
// FUNCIONES AUXILIARES
// ==========================================
function showToast(type, message) {
    // Simple alert como fallback - se puede mejorar con una librería de toasts
    if (type === 'success') {
        alert('✅ ' + message);
    } else {
        alert('❌ ' + message);
    }
}

// ==========================================
// MODALES MASIVOS (código original)
// ==========================================
async function registerManual() {
    const userId = document.getElementById('userSelect').value;
    const status = document.getElementById('statusSelect').value;
    if (!userId) { alert('Seleccione un usuario'); return; }
    
    try {
        const {data} = await axios.post(`/attendance/${eventId}/manual`, {user_id: userId, status});
        if (data.success) { alert('Asistencia registrada'); location.reload(); }
    } catch(e) { alert('Error: ' + (e.response?.data?.error || e.message)); }
}

async function registerQR() {
    const qrToken = document.getElementById('qrToken').value;
    if (!qrToken) { alert('Ingrese un token QR'); return; }
    
    try {
        const {data} = await axios.post(`/attendance/${eventId}/qr`, {qr_token: qrToken, user_id: {{ auth()->id() }}});
        if (data.success) { alert('Asistencia registrada'); location.reload(); }
    } catch(e) { alert('Error: ' + (e.response?.data?.error || e.message)); }
}

async function registerBarcode() {
    const barcodeData = document.getElementById('barcodeData').value;
    if (!barcodeData) { alert('Ingrese datos de código de barras'); return; }
    
    try {
        const {data} = await axios.post(`/attendance/${eventId}/barcode`, {barcode_data: barcodeData, user_id: {{ auth()->id() }}});
        if (data.success) { alert('Asistencia registrada'); location.reload(); }
    } catch(e) { alert('Error: ' + (e.response?.data?.error || e.message)); }
}

// Face recognition masivo (código original)
let stream;
const webcam = document.getElementById('webcam');
const canvas = document.getElementById('canvas');
const capturedImage = document.getElementById('capturedImage');

if (document.getElementById('faceCheckinModal')) {
    document.getElementById('faceCheckinModal').addEventListener('shown.bs.modal', async function() {
        try {
            stream = await navigator.mediaDevices.getUserMedia({video: true});
            webcam.srcObject = stream;
        } catch(e) { alert('Error accediendo a la cámara'); }
    });

    document.getElementById('faceCheckinModal').addEventListener('hidden.bs.modal', function() {
        if (stream) stream.getTracks().forEach(t => t.stop());
        retakeFace();
    });
}

function captureFace() {
    canvas.getContext('2d').drawImage(webcam, 0, 0);
    capturedImage.src = canvas.toDataURL('image/png');
    webcam.style.display = 'none';
    capturedImage.style.display = 'block';
    document.getElementById('captureButton').style.display = 'none';
    document.getElementById('retakeButton').style.display = 'block';
    document.getElementById('registerFaceButton').style.display = 'block';
}

function retakeFace() {
    if (webcam) webcam.style.display = 'block';
    if (capturedImage) capturedImage.style.display = 'none';
    if (document.getElementById('captureButton')) document.getElementById('captureButton').style.display = 'block';
    if (document.getElementById('retakeButton')) document.getElementById('retakeButton').style.display = 'none';
    if (document.getElementById('registerFaceButton')) document.getElementById('registerFaceButton').style.display = 'none';
}

async function registerFace() {
    if (!capturedImage.src) { alert('Capture su rostro primero'); return; }
    
    const blob = await fetch(capturedImage.src).then(r => r.blob());
    const formData = new FormData();
    formData.append('image', blob, 'face.png');
    
    try {
        const {data} = await axios.post(`/attendance/${eventId}/face`, formData, {headers: {'Content-Type': 'multipart/form-data'}});
        if (data.success) { alert('Asistencia registrada'); location.reload(); }
    } catch(e) { alert('Error: ' + (e.response?.data?.error || e.message)); }
}
</script>
@endpush
