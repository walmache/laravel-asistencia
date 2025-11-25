@extends('layouts.adminlte')

@section('title', __('common.edit') . ' ' . __('common.organizations') . ' - neuroTech')
@section('page-title', '')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border">
            <div class="card-header bg-light border-bottom">
                <h3 class="card-title">{{ __('common.edit') }} {{ __('common.organizations') }}</h3>
            </div>
            <form action="{{ route('organizations.update', $organization->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">{{ __('common.name') }} <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text form-input-icon">
                                    <i class="fas fa-building"></i>
                                </span>
                                <input type="text" class="form-control form-control-with-icon form-control-sm @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $organization->name) }}" placeholder="Ingrese el nombre de la organización" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="description" class="form-label">{{ __('common.description') }}</label>
                            <div class="input-group">
                                <span class="input-group-text form-input-icon">
                                    <i class="fas fa-align-left"></i>
                                </span>
                                <input type="text" class="form-control form-control-with-icon form-control-sm @error('description') is-invalid @enderror" id="description" name="description" value="{{ old('description', $organization->description) }}" placeholder="Ingrese la descripción">
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">{{ __('common.save') }}</button>
                    <a href="{{ route('organizations.index') }}" class="btn btn-secondary">{{ __('common.cancel') }}</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

