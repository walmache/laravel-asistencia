@extends('layouts.adminlte')

@section('title', __('common.edit') . ' ' . __('common.organizations') . ' - neuroTech')
@section('page-title', '')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border border-dark mt-4">
            <div class="card-header bg-secondary bg-opacity-25 border-bottom border-dark">
                <h3 class="card-title">{{ __('common.edit') }} {{ __('common.organizations') }}</h3>
            </div>
            <form action="{{ route('organizations.update', $organization->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body p-3">
                    <!-- Datos de la Organización -->
                    <h6 class="text-secondary border-bottom pb-2 mb-3"><i class="fas fa-building me-2"></i>Datos de la Organización</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Nombre Comercial <span class="text-danger">*</span></label>
                            <div class="input-group border border-secondary rounded">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-building"></i>
                                </span>
                                <input type="text" class="form-control border-0 form-control-sm @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $organization->name) }}" placeholder="Ej: Empresa ABC" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="business_name" class="form-label">Razón Social</label>
                            <div class="input-group border border-secondary rounded">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-file-contract"></i>
                                </span>
                                <input type="text" class="form-control border-0 form-control-sm @error('business_name') is-invalid @enderror" id="business_name" name="business_name" value="{{ old('business_name', $organization->business_name) }}" placeholder="Ej: Empresa ABC S.A.">
                                @error('business_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="ruc" class="form-label">RUC / Identificación Fiscal</label>
                            <div class="input-group border border-secondary rounded">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-id-card"></i>
                                </span>
                                <input type="text" class="form-control border-0 form-control-sm @error('ruc') is-invalid @enderror" id="ruc" name="ruc" value="{{ old('ruc', $organization->ruc) }}" placeholder="Ej: 1791234567001" maxlength="20">
                                @error('ruc')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="address" class="form-label">Dirección</label>
                            <div class="input-group border border-secondary rounded">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-map-marker-alt"></i>
                                </span>
                                <input type="text" class="form-control border-0 form-control-sm @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $organization->address) }}" placeholder="Ej: Av. Principal 123, Ciudad">
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Medios de Contacto -->
                    <h6 class="text-secondary border-bottom pb-2 mb-3 mt-4"><i class="fas fa-address-book me-2"></i>Medios de Contacto</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Teléfono</label>
                            <div class="input-group border border-secondary rounded">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-phone"></i>
                                </span>
                                <input type="tel" class="form-control border-0 form-control-sm @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $organization->phone) }}" placeholder="Ej: +593 99 123 4567" maxlength="20">
                                @error('phone')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Correo Electrónico</label>
                            <div class="input-group border border-secondary rounded">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-envelope"></i>
                                </span>
                                <input type="email" class="form-control border-0 form-control-sm @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $organization->email) }}" placeholder="Ej: contacto@empresa.com">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Representante Legal -->
                    <h6 class="text-secondary border-bottom pb-2 mb-3 mt-4"><i class="fas fa-user-tie me-2"></i>Representante Legal</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="legal_rep_id" class="form-label">Cédula / Pasaporte</label>
                            <div class="input-group border border-secondary rounded">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-id-badge"></i>
                                </span>
                                <input type="text" class="form-control border-0 form-control-sm @error('legal_rep_id') is-invalid @enderror" id="legal_rep_id" name="legal_rep_id" value="{{ old('legal_rep_id', $organization->legal_rep_id) }}" placeholder="Ej: 1712345678" maxlength="20">
                                @error('legal_rep_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="legal_rep_name" class="form-label">Nombres Completos</label>
                            <div class="input-group border border-secondary rounded">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-user"></i>
                                </span>
                                <input type="text" class="form-control border-0 form-control-sm @error('legal_rep_name') is-invalid @enderror" id="legal_rep_name" name="legal_rep_name" value="{{ old('legal_rep_name', $organization->legal_rep_name) }}" placeholder="Ej: Juan Carlos Pérez García">
                                @error('legal_rep_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Descripción -->
                    <h6 class="text-secondary border-bottom pb-2 mb-3 mt-4"><i class="fas fa-info-circle me-2"></i>Información Adicional</h6>
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <div class="input-group border border-secondary rounded">
                                <span class="input-group-text bg-light border-0 align-items-start pt-2">
                                    <i class="fas fa-align-left"></i>
                                </span>
                                <textarea class="form-control border-0 form-control-sm @error('description') is-invalid @enderror" id="description" name="description" rows="3" placeholder="Descripción de la organización, actividad económica, etc.">{{ old('description', $organization->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-secondary bg-opacity-25 border-top border-dark text-end">
                    <a href="{{ route('organizations.index') }}" class="btn btn-secondary btn-sm"><i class="fas fa-times me-1"></i>{{ __('common.cancel') }}</a>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save me-1"></i>{{ __('common.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
