@extends('layouts.adminlte')

@section('title', __('common.create') . ' ' . __('common.users') . ' - neuroTech')
@section('page-title', '')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border border-dark mt-4">
            <div class="card-header bg-secondary bg-opacity-25 border-bottom border-dark">
                <h3 class="card-title">{{ __('common.create') }} {{ __('common.users') }}</h3>
            </div>
            <form action="{{ route('users.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">{{ __('common.name') }} <span class="text-danger">*</span></label>
                            <div class="input-group border border-secondary rounded">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" class="form-control border-0 form-control-sm @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Ej: Juan Pérez García" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">{{ __('common.email') }} <span class="text-danger">*</span></label>
                            <div class="input-group border border-secondary rounded">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" class="form-control border-0 form-control-sm @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Ej: usuario@ejemplo.com" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">{{ __('common.password') }} <span class="text-danger">*</span></label>
                            <div class="input-group border border-secondary rounded">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" class="form-control border-0 form-control-sm @error('password') is-invalid @enderror" id="password" name="password" placeholder="Ingrese la contraseña" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar Contraseña <span class="text-danger">*</span></label>
                            <div class="input-group border border-secondary rounded">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" class="form-control border-0 form-control-sm" id="password_confirmation" name="password_confirmation" placeholder="Confirme la contraseña" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label">Rol <span class="text-danger">*</span></label>
                            <div class="input-group border border-secondary rounded">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-user-shield"></i>
                                </span>
                                <select class="form-select border-0 form-select-sm @error('role') is-invalid @enderror" id="role" name="role" required>
                                    <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Usuario</option>
                                    <option value="coordinator" {{ old('role') == 'coordinator' ? 'selected' : '' }}>Coordinador</option>
                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrador</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="avatar" class="form-label">Foto de Perfil</label>
                            <div class="input-group border border-secondary rounded">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-camera"></i>
                                </span>
                                <input type="file" class="form-control border-0 form-control-sm @error('avatar') is-invalid @enderror" id="avatar" name="avatar" accept="image/jpeg,image/png,image/webp">
                                @error('avatar')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">Formatos: JPG, PNG, WebP. Máximo: 2MB</small>
                        </div>
                    </div>
                    
                    <!-- Vista previa de la imagen -->
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div id="avatarPreviewContainer" class="d-none">
                                <label class="form-label">Vista Previa</label>
                                <div class="d-flex align-items-center">
                                    <img id="avatarPreview" src="" alt="Vista previa" class="rounded-circle border border-secondary" style="width: 80px; height: 80px; object-fit: cover;">
                                    <button type="button" class="btn btn-outline-danger btn-sm ms-3" id="removePreview">
                                        <i class="fas fa-times"></i> Quitar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-secondary bg-opacity-25 border-top border-dark text-end">
                    <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">{{ __('common.cancel') }}</a>
                    <button type="submit" class="btn btn-primary btn-sm">{{ __('common.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const avatarInput = document.getElementById('avatar');
    const previewContainer = document.getElementById('avatarPreviewContainer');
    const previewImage = document.getElementById('avatarPreview');
    const removeButton = document.getElementById('removePreview');

    // Vista previa al seleccionar archivo
    avatarInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            // Validar tipo de archivo
            const allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            if (!allowedTypes.includes(file.type)) {
                alert('Formato no permitido. Use JPG, PNG o WebP.');
                avatarInput.value = '';
                return;
            }
            
            // Validar tamaño (2MB = 2097152 bytes)
            if (file.size > 2097152) {
                alert('La imagen excede el tamaño máximo de 2MB.');
                avatarInput.value = '';
                return;
            }
            
            // Mostrar vista previa
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
                previewContainer.classList.remove('d-none');
            };
            reader.readAsDataURL(file);
        }
    });

    // Quitar imagen seleccionada
    removeButton.addEventListener('click', function() {
        avatarInput.value = '';
        previewImage.src = '';
        previewContainer.classList.add('d-none');
    });
});
</script>
@endpush
