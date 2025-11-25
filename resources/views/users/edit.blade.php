@extends('layouts.adminlte')

@section('title', __('common.edit') . ' ' . __('common.users') . ' - neuroTech')
@section('page-title', '')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border">
            <div class="card-header bg-light border-bottom">
                <h3 class="card-title">{{ __('common.edit') }} {{ __('common.users') }}</h3>
            </div>
            <form action="{{ route('users.update', $user->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">{{ __('common.name') }} <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text form-input-icon">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" class="form-control form-control-with-icon form-control-sm @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $user->name) }}" placeholder="Ej: Juan Pérez García" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">{{ __('common.email') }} <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text form-input-icon">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" class="form-control form-control-with-icon form-control-sm @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $user->email) }}" placeholder="Ej: usuario@ejemplo.com" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">{{ __('common.password') }}</label>
                            <div class="input-group">
                                <span class="input-group-text form-input-icon">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" class="form-control form-control-with-icon form-control-sm @error('password') is-invalid @enderror" id="password" name="password" placeholder="Dejar en blanco para no cambiar">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <small class="form-text text-muted">Dejar en blanco para no cambiar</small>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar Contraseña</label>
                            <div class="input-group">
                                <span class="input-group-text form-input-icon">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" class="form-control form-control-with-icon form-control-sm" id="password_confirmation" name="password_confirmation" placeholder="Confirme la contraseña">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="role" class="form-label">Rol <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text form-input-icon">
                                <i class="fas fa-user-shield"></i>
                            </span>
                            <select class="form-select form-control-with-icon form-select-sm form-control-sm @error('role') is-invalid @enderror" id="role" name="role" required>
                                <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>Usuario</option>
                                <option value="coordinator" {{ old('role', $user->role) == 'coordinator' ? 'selected' : '' }}>Coordinador</option>
                                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrador</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">{{ __('common.save') }}</button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary">{{ __('common.cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

