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
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">{{ __('common.name') }} <span class="text-danger">*</span></label>
                            <div class="input-group border border-secondary rounded">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-building"></i>
                                </span>
                                <input type="text" class="form-control border-0 form-control-sm @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $organization->name) }}" placeholder="Ingrese el nombre de la organización" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="description" class="form-label">{{ __('common.description') }}</label>
                            <div class="input-group border border-secondary rounded">
                                <span class="input-group-text bg-light border-0">
                                    <i class="fas fa-align-left"></i>
                                </span>
                                <input type="text" class="form-control border-0 form-control-sm @error('description') is-invalid @enderror" id="description" name="description" value="{{ old('description', $organization->description) }}" placeholder="Ingrese la descripción">
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-secondary bg-opacity-25 border-top border-dark text-end">
                    <a href="{{ route('organizations.index') }}" class="btn btn-secondary btn-sm">{{ __('common.cancel') }}</a>
                    <button type="submit" class="btn btn-primary btn-sm">{{ __('common.save') }}</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
