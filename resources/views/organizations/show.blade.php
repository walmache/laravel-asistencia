@extends('layouts.adminlte')

@section('title', $organization->name . ' - neuroTech')
@section('page-title', $organization->name)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border border-info mt-4">
            <div class="card-header bg-light border-bottom">
                <h3 class="card-title">Detalles de la Organización</h3>
                <div class="card-tools">
                    <a href="{{ route('organizations.edit', $organization->id) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> {{ __('common.edit') }}
                    </a>
                </div>
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Nombre:</dt>
                    <dd class="col-sm-9">{{ $organization->name }}</dd>
                    
                    <dt class="col-sm-3">Descripción:</dt>
                    <dd class="col-sm-9">{{ $organization->description ?? 'N/A' }}</dd>
                    
                    <dt class="col-sm-3">Eventos:</dt>
                    <dd class="col-sm-9">{{ $organization->events->count() }} eventos</dd>
                </dl>
                
                @if($organization->events->count() > 0)
                <div class="mt-4">
                    <h5>Eventos de esta organización</h5>
                    <ul class="list-group">
                        @foreach($organization->events as $event)
                            <li class="list-group-item">
                                <a href="{{ route('events.show', $event->id) }}">{{ $event->name }}</a>
                                <span class="badge bg-{{ $event->status == 'scheduled' ? 'secondary' : ($event->status == 'ongoing' ? 'success' : 'info') }} ms-2">
                                    {{ ucfirst($event->status) }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>
            <div class="card-footer">
                <a href="{{ route('organizations.index') }}" class="btn btn-secondary">{{ __('common.cancel') }}</a>
            </div>
        </div>
    </div>
</div>
@endsection

