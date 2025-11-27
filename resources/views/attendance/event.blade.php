@extends('layouts.adminlte')

@section('title', ($event->name ?? 'Evento') . ' - Asistencia')
@section('page-title', 'Registro de Asistencia')

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card border border-dark mt-4">
            <div class="card-header bg-secondary bg-opacity-25 border-bottom border-dark">
                <h3 class="card-title">{{ $event->name ?? 'Evento' }}</h3>
                <div class="card-tools">
                    <a href="{{ route('attendance.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5>Detalles del Evento</h5>
                        <p><strong>Organización:</strong> {{ $event->organization->name ?? 'N/A' }}</p>
                        <p><strong>Descripción:</strong> {{ $event->description ?? 'Sin descripción' }}</p>
                        <p><strong>Inicio:</strong> {{ $event->start_at->format('d/m/Y H:i') }}</p>
                        <p><strong>Fin:</strong> {{ $event->end_at->format('d/m/Y H:i') }}</p>
                        <p><strong>Estado:</strong> 
                            <span class="badge bg-{{ $event->status == 'scheduled' ? 'secondary' : ($event->status == 'ongoing' ? 'success' : 'info') }}">
                                {{ ucfirst($event->status) }}
                            </span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <h5>Opciones de Registro</h5>
                        <div class="d-grid gap-2">
                            <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#manualCheckinModal">
                                <i class="fas fa-check-circle"></i> Registro Manual
                            </button>
                            <button type="button" class="btn btn-outline-success" data-bs-toggle="modal" data-bs-target="#qrCheckinModal">
                                <i class="fas fa-qrcode"></i> Código QR
                            </button>
                            <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#barcodeCheckinModal">
                                <i class="fas fa-barcode"></i> Código de Barras
                            </button>
                            @if($event->allow_face_checkin)
                            <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#faceCheckinModal">
                                <i class="fas fa-user-check"></i> Reconocimiento Facial
                            </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-12">
        <div class="card border border-dark mt-4">
            <div class="card-header bg-secondary bg-opacity-25 border-bottom border-dark">
                <h3 class="card-title">Asistentes</h3>
            </div>
            <div class="card-body table-responsive p-3">
                <table class="table table-hover text-nowrap table-bordered border-secondary table-sm datatable">
                    <thead class="text-center">
                        <tr>
                            <th>{{ __('common.table_name') }}</th>
                            <th>{{ __('common.table_email') }}</th>
                            <th>{{ __('common.table_role') }}</th>
                            <th>{{ __('common.table_status') }}</th>
                            <th>{{ __('common.table_registration_time') }}</th>
                            <th>{{ __('common.table_method') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users ?? [] as $user)
                            @php $attendance = $attendances->where('user_id', $user->id)->first(); @endphp
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td><span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'coordinator' ? 'warning' : 'info') }}">{{ ucfirst($user->role) }}</span></td>
                            <td>
                                @if($attendance)
                                    <span class="badge bg-success">Presente</span>
                                @else
                                    <span class="badge bg-danger">Ausente</span>
                                @endif
                            </td>
                            <td>{{ $attendance ? $attendance->check_in_at->format('d/m/Y H:i') : 'N/A' }}</td>
                            <td>{{ $attendance ? ucfirst($attendance->method) : 'N/A' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
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

// Modal handlers
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

// Face recognition
let stream;
const webcam = document.getElementById('webcam');
const canvas = document.getElementById('canvas');
const capturedImage = document.getElementById('capturedImage');

$('#faceCheckinModal').on('shown.bs.modal', async function() {
    try {
        stream = await navigator.mediaDevices.getUserMedia({video: true});
        webcam.srcObject = stream;
    } catch(e) { alert('Error accediendo a la cámara'); }
});

$('#faceCheckinModal').on('hidden.bs.modal', function() {
    if (stream) stream.getTracks().forEach(t => t.stop());
    retakeFace();
});

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
    webcam.style.display = 'block';
    capturedImage.style.display = 'none';
    document.getElementById('captureButton').style.display = 'block';
    document.getElementById('retakeButton').style.display = 'none';
    document.getElementById('registerFaceButton').style.display = 'none';
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




