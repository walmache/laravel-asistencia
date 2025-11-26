@extends('layouts.adminlte')

@section('title', __('common.create') . ' ' . __('common.users') . ' - neuroTech')
@section('page-title', '')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border border-info mt-4">
            <div class="card-header bg-light border-bottom">
                <h3 class="card-title">{{ __('common.create') }} {{ __('common.users') }}</h3>
            </div>
            <form action="{{ route('users.store') }}" method="POST">
                @csrf
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">{{ __('common.name') }} <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text form-input-icon">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" class="form-control form-control-with-icon form-control-sm @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Ej: Juan Pérez García" required>
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
                                <input type="email" class="form-control form-control-with-icon form-control-sm @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Ej: usuario@ejemplo.com" required>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">{{ __('common.password') }} <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text form-input-icon">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" class="form-control form-control-with-icon form-control-sm @error('password') is-invalid @enderror" id="password" name="password" placeholder="Ingrese la contraseña" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Confirmar Contraseña <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text form-input-icon">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="password" class="form-control form-control-with-icon form-control-sm" id="password_confirmation" name="password_confirmation" placeholder="Confirme la contraseña" required>
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
                                <option value="user" {{ old('role') == 'user' ? 'selected' : '' }}>Usuario</option>
                                <option value="coordinator" {{ old('role') == 'coordinator' ? 'selected' : '' }}>Coordinador</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Administrador</option>
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary btn-sm">{{ __('common.save') }}</button>
                    <a href="{{ route('users.index') }}" class="btn btn-secondary btn-sm">{{ __('common.cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

