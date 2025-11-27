@extends('layouts.adminlte')

@section('title', __('common.create') . ' ' . __('common.events') . ' - neuroTech')
@section('page-title', '')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border border-dark mt-4">
            <div class="card-header bg-secondary bg-opacity-25 border-bottom border-dark">
                <h3 class="card-title">{{ __('common.create') }} {{ __('common.events') }}</h3>
            </div>
            <form action="{{ route('events.store') }}" method="POST">
                @csrf
                <div class="card-body p-3">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">{{ __('common.name') }} <span class="text-danger">*</span></label>
                            <div class="input-group border border-secondary rounded">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-calendar-alt"></i>
                                </span>
                                <input type="text" class="form-control border-0 form-control-sm @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Ingrese el nombre del evento" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="organization_id" class="form-label">Organización <span class="text-danger">*</span></label>
                            <div class="input-group border border-secondary rounded">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-building"></i>
                                </span>
                                <select class="form-select border-0 form-select-sm @error('organization_id') is-invalid @enderror" id="organization_id" name="organization_id" required>
                                    <option value="">Seleccione una organización</option>
                                    @foreach($organizations as $org)
                                        <option value="{{ $org->id }}" {{ old('organization_id') == $org->id ? 'selected' : '' }}>{{ $org->name }}</option>
                                    @endforeach
                                </select>
                                @error('organization_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">{{ __('common.description') }}</label>
                        <div class="input-group border border-secondary rounded">
                            <span class="input-group-text bg-light border-0">
                                <i class="fas fa-align-left"></i>
                            </span>
                            <textarea class="form-control border-0 form-control-sm @error('description') is-invalid @enderror" id="description" name="description" rows="2" placeholder="Ingrese la descripción del evento">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="start_at" class="form-label">{{ __('common.start_date') }} <span class="text-danger">*</span></label>
                            <div class="input-group border border-secondary rounded">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-calendar-check"></i>
                                </span>
                                <input type="datetime-local" class="form-control border-0 form-control-sm @error('start_at') is-invalid @enderror" id="start_at" name="start_at" value="{{ old('start_at') }}" required>
                                @error('start_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="end_at" class="form-label">{{ __('common.end_date') }} <span class="text-danger">*</span></label>
                            <div class="input-group border border-secondary rounded">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-calendar-times"></i>
                                </span>
                                <input type="datetime-local" class="form-control border-0 form-control-sm @error('end_at') is-invalid @enderror" id="end_at" name="end_at" value="{{ old('end_at') }}" required>
                                @error('end_at')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="status" class="form-label">{{ __('common.status') }} <span class="text-danger">*</span></label>
                            <div class="input-group border border-secondary rounded">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-info-circle"></i>
                                </span>
                                <select class="form-select border-0 form-select-sm @error('status') is-invalid @enderror" id="status" name="status" required>
                                    <option value="scheduled" {{ old('status') == 'scheduled' ? 'selected' : '' }}>Programado</option>
                                    <option value="ongoing" {{ old('status') == 'ongoing' ? 'selected' : '' }}>En curso</option>
                                    <option value="finished" {{ old('status') == 'finished' ? 'selected' : '' }}>Finalizado</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="user_ids" class="form-label">Usuarios asignados</label>
                            <div class="input-group border border-secondary rounded">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-users"></i>
                                </span>
                                <select class="form-select border-0 form-select-sm" id="user_ids" name="user_ids[]" multiple size="3">
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ in_array($user->id, old('user_ids', [])) ? 'selected' : '' }}>{{ $user->name }} ({{ $user->email }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <small class="form-text text-muted">Ctrl/Cmd para múltiples selecciones</small>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-secondary bg-opacity-25 border-top border-dark text-end">
                    <a href="{{ route('events.index') }}" class="btn btn-secondary btn-sm">{{ __('common.cancel') }}</a>
                    <button type="submit" class="btn btn-primary btn-sm">{{ __('common.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
