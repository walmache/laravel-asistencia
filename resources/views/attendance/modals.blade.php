<!-- Manual Check-in Modal -->
<div class="modal fade" id="manualCheckinModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registro Manual</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="userSelect" class="form-label">Seleccionar Usuario</label>
                    <select class="form-select" id="userSelect">
                        <option value="">-- Seleccionar --</option>
                        @foreach($users ?? [] as $user)
                        <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->email }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="mb-3">
                    <label for="statusSelect" class="form-label">Estado</label>
                    <select class="form-select" id="statusSelect">
                        <option value="present">Presente</option>
                        <option value="absent">Ausente</option>
                        <option value="justified">Justificado</option>
                    </select>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-primary" onclick="registerManual()">Registrar</button>
            </div>
        </div>
    </div>
</div>

<!-- QR Code Modal -->
<div class="modal fade" id="qrCheckinModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registro con QR</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img src="{{ route('attendance.qrcode', ['eventId' => $event->id]) }}" alt="QR Code" class="img-fluid mb-3" style="max-width: 200px;">
                <p>Escane este código QR para registrarse</p>
                <div class="mb-3">
                    <label for="qrToken" class="form-label">O ingrese token QR:</label>
                    <input type="text" class="form-control" id="qrToken" placeholder="Token QR">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" onclick="registerQR()">Registrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Barcode Modal -->
<div class="modal fade" id="barcodeCheckinModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registro con Código de Barras</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img src="{{ route('attendance.barcode-image', ['eventId' => $event->id]) }}" alt="Barcode" class="img-fluid mb-3" style="max-width: 300px;">
                <p>Escane este código de barras para registrarse</p>
                <div class="mb-3">
                    <label for="barcodeData" class="form-label">O ingrese datos:</label>
                    <input type="text" class="form-control" id="barcodeData" placeholder="Datos del código">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-info" onclick="registerBarcode()">Registrar</button>
            </div>
        </div>
    </div>
</div>

<!-- Face Recognition Modal -->
@if($event->allow_face_checkin)
<div class="modal fade" id="faceCheckinModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Registro con Reconocimiento Facial</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <video id="webcam" width="320" height="240" autoplay class="mb-3" style="display:block;"></video>
                <canvas id="canvas" width="320" height="240" style="display:none;"></canvas>
                <img id="capturedImage" style="display:none; max-width: 320px; max-height: 240px;" class="mb-3">
                <div class="d-grid gap-2">
                    <button id="captureButton" class="btn btn-warning" onclick="captureFace()">
                        <i class="fas fa-camera"></i> Capturar Rostro
                    </button>
                    <button id="retakeButton" class="btn btn-secondary" style="display:none;" onclick="retakeFace()">
                        <i class="fas fa-redo"></i> Volver a Capturar
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-success" id="registerFaceButton" style="display:none;" onclick="registerFace()">Registrar</button>
            </div>
        </div>
    </div>
</div>
@endif




