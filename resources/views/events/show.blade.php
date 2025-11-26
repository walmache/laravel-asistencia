@extends('layouts.adminlte')

@section('title', $event->name . ' - neuroTech')
@section('page-title', $event->name)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border border-info mt-4">
            <div class="card-header bg-light border-bottom">
                <h3 class="card-title">Detalles del Evento</h3>
                @if(auth()->user()?->hasRole(['admin', 'coordinator']))
                <div class="card-tools">
                    <a href="{{ route('events.edit', $event->id) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> {{ __('common.edit') }}
                    </a>
                </div>
                @endif
            </div>
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Nombre:</dt>
                    <dd class="col-sm-9">{{ $event->name }}</dd>
                    
                    <dt class="col-sm-3">Descripción:</dt>
                    <dd class="col-sm-9">{{ $event->description ?? 'N/A' }}</dd>
                    
                    <dt class="col-sm-3">Organización:</dt>
                    <dd class="col-sm-9">{{ $event->organization->name ?? 'N/A' }}</dd>
                    
                    <dt class="col-sm-3">Fecha de Inicio:</dt>
                    <dd class="col-sm-9">{{ $event->start_at->format('d/m/Y H:i') }}</dd>
                    
                    <dt class="col-sm-3">Fecha de Fin:</dt>
                    <dd class="col-sm-9">{{ $event->end_at->format('d/m/Y H:i') }}</dd>
                    
                    <dt class="col-sm-3">Estado:</dt>
                    <dd class="col-sm-9">
                        <span class="badge bg-{{ $event->status == 'scheduled' ? 'secondary' : ($event->status == 'ongoing' ? 'success' : 'info') }}">
                            {{ ucfirst($event->status) }}
                        </span>
                    </dd>
                    
                    <dt class="col-sm-3">Usuarios Asignados:</dt>
                    <dd class="col-sm-9">
                        @if($event->users->count() > 0)
                            <ul class="list-unstyled">
                                @foreach($event->users as $user)
                                    <li>{{ $user->name }} ({{ $user->email }})</li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-muted">No hay usuarios asignados</span>
                        @endif
                    </dd>
                    
                    <dt class="col-sm-3">Asistencias:</dt>
                    <dd class="col-sm-9">{{ $event->attendances->count() }} registros</dd>
                </dl>
            </div>
            <div class="card-footer">
                <a href="{{ route('events.index') }}" class="btn btn-secondary">{{ __('common.cancel') }}</a>
                <a href="{{ route('attendance.show', $event->id) }}" class="btn btn-primary">Ver Asistencias</a>
            </div>
        </div>
    </div>
</div>
@endsection

