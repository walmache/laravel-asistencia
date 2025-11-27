@extends('layouts.adminlte')

@section('title', __('common.edit') . ' ' . __('common.users') . ' - neuroTech')
@section('page-title', '')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border border-dark mt-4">
            <div class="card-header bg-secondary bg-opacity-25 border-bottom border-dark">
                <h3 class="card-title">{{ __('common.edit') }} {{ __('common.users') }}</h3>
            </div>
            <form action="{{ route('users.update', $user->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">{{ __('common.name') }} <span class="text-danger">*</span></label>
                            <div class="input-group border border-secondary rounded">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" class="form-control border-0 form-control-sm @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" placeholder="Ej: Juan Pérez García" required>
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
                                <input type="email" class="form-control border-0 form-control-sm @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" placeholder="Ej: usuario@ejemplo.com" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">{{ __('common.password') }}</label>
                            <div class="input-group border border-secondary rounded">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" class="form-control border-0 form-control-sm @error('password') is-invalid @enderror" id="password" name="password" placeholder="Dejar en blanco para no cambiar">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="form-text text-muted">Dejar en blanco para no cambiar</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                            <div class="input-group border border-secondary rounded">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" class="form-control border-0 form-control-sm" id="password_confirmation" name="password_confirmation" placeholder="Confirme la contraseña">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="role" class="form-label">Rol <span class="text-danger">*</span></label>
                            <div class="input-group border border-secondary rounded">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-user-shield"></i>
                                </span>
                                <select class="form-select border-0 form-select-sm @error('role') is-invalid @enderror" id="role" name="role" required>
                                    <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>Usuario</option>
                                    <option value="coordinator" {{ old('role', $user->role) == 'coordinator' ? 'selected' : '' }}>Coordinador</option>
                                    <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrador</option>
                                </select>
                                @error('role')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="organization_id" class="form-label">Organización</label>
                            <div class="input-group border border-secondary rounded">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-building"></i>
                                </span>
                                <select class="form-select border-0 form-select-sm @error('organization_id') is-invalid @enderror" id="organization_id" name="organization_id">
                                    <option value="">-- Sin organización --</option>
                                    @foreach($organizations as $org)
                                        <option value="{{ $org->id }}" {{ old('organization_id', $user->organization_id) == $org->id ? 'selected' : '' }}>{{ $org->name }}</option>
                                    @endforeach
                                </select>
                                @error('organization_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="text-muted">Asignar a una organización para acceder a sus eventos privados.</small>
                        </div>
                        
                        <div class="col-md-4 mb-3">
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
                    
                    <!-- Foto actual y vista previa -->
                    <div class="row">
                        <div class="col-12 mb-3">
                            <div id="avatarSection">
                                <label class="form-label">Foto Actual / Vista Previa</label>
                                <div class="d-flex align-items-center">
                                    @if($user->avatar_url)
                                        <img id="currentAvatar" src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="rounded-circle border border-secondary" style="width: 80px; height: 80px; object-fit: cover;">
                                        <div class="ms-3">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" id="removeAvatar" name="remove_avatar" value="1">
                                                <label class="form-check-label text-danger" for="removeAvatar">
                                                    <i class="fas fa-trash"></i> Eliminar foto actual
                                                </label>
                                            </div>
                                        </div>
                                    @else
                                        <div id="noAvatarPlaceholder" class="d-flex align-items-center justify-content-center rounded-circle border border-secondary bg-light" style="width: 80px; height: 80px;">
                                            <i class="fas fa-circle-user fa-3x text-secondary"></i>
                                        </div>
                                        <span class="ms-3 text-muted">Sin foto de perfil</span>
                                    @endif
                                    <img id="avatarPreview" src="" alt="Vista previa" class="rounded-circle border border-success d-none ms-3" style="width: 80px; height: 80px; object-fit: cover;">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-secondary bg-opacity-25 border-top border-dark text-end">
                    <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-times me-1"></i>{{ __('common.cancel') }}</a>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save me-1"></i>{{ __('common.save') }}</button>
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
    const previewImage = document.getElementById('avatarPreview');
    const removeCheckbox = document.getElementById('removeAvatar');
    const currentAvatar = document.getElementById('currentAvatar');

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
                previewImage.classList.remove('d-none');
                
                // Desmarcar eliminar si selecciona nueva imagen
                if (removeCheckbox) {
                    removeCheckbox.checked = false;
                }
            };
            reader.readAsDataURL(file);
        } else {
            previewImage.classList.add('d-none');
        }
    });

    // Al marcar eliminar, ocultar vista previa y limpiar input
    if (removeCheckbox) {
        removeCheckbox.addEventListener('change', function() {
            if (this.checked) {
                avatarInput.value = '';
                previewImage.classList.add('d-none');
                if (currentAvatar) {
                    currentAvatar.style.opacity = '0.3';
                }
            } else {
                if (currentAvatar) {
                    currentAvatar.style.opacity = '1';
                }
            }
        });
    }
});
</script>
@endpush
