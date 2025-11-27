@extends('layouts.adminlte')

@section('title', $organization->name . ' - neuroTech')
@section('page-title', '')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border border-dark mt-4">
            <div class="card-header bg-secondary bg-opacity-25 border-bottom border-dark">
                <h3 class="card-title">{{ $organization->name }}</h3>
                <div class="card-tools">
                    <a href="{{ route('organizations.edit', $organization->id) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit"></i> {{ __('common.edit') }}
                    </a>
                </div>
            </div>
            <div class="card-body p-3">
                <div class="row">
                    <!-- Datos de la Organización -->
                    <div class="col-md-6">
                        <h6 class="text-secondary border-bottom pb-2 mb-3"><i class="fas fa-building me-2"></i>Datos de la Organización</h6>
                        <dl class="row small">
                            <dt class="col-sm-5">Nombre Comercial:</dt>
                            <dd class="col-sm-7">{{ $organization->name }}</dd>
                            
                            <dt class="col-sm-5">Razón Social:</dt>
                            <dd class="col-sm-7">{{ $organization->business_name ?? 'N/A' }}</dd>
                            
                            <dt class="col-sm-5">RUC:</dt>
                            <dd class="col-sm-7">{{ $organization->ruc ?? 'N/A' }}</dd>
                            
                            <dt class="col-sm-5">Dirección:</dt>
                            <dd class="col-sm-7">{{ $organization->address ?? 'N/A' }}</dd>
                        </dl>
                    </div>
                    
                    <!-- Medios de Contacto y Rep. Legal -->
                    <div class="col-md-6">
                        <h6 class="text-secondary border-bottom pb-2 mb-3"><i class="fas fa-address-book me-2"></i>Contacto</h6>
                        <dl class="row small">
                            <dt class="col-sm-5">Teléfono:</dt>
                            <dd class="col-sm-7">
                                @if($organization->phone)
                                    <a href="tel:{{ $organization->phone }}">{{ $organization->phone }}</a>
                                @else
                                    N/A
                                @endif
                            </dd>
                            
                            <dt class="col-sm-5">Correo:</dt>
                            <dd class="col-sm-7">
                                @if($organization->email)
                                    <a href="mailto:{{ $organization->email }}">{{ $organization->email }}</a>
                                @else
                                    N/A
                                @endif
                            </dd>
                        </dl>
                        
                        <h6 class="text-secondary border-bottom pb-2 mb-3 mt-4"><i class="fas fa-user-tie me-2"></i>Representante Legal</h6>
                        <dl class="row small">
                            <dt class="col-sm-5">Cédula/Pasaporte:</dt>
                            <dd class="col-sm-7">{{ $organization->legal_rep_id ?? 'N/A' }}</dd>
                            
                            <dt class="col-sm-5">Nombre:</dt>
                            <dd class="col-sm-7">{{ $organization->legal_rep_name ?? 'N/A' }}</dd>
                        </dl>
                    </div>
                </div>
                
                <!-- Descripción -->
                @if($organization->description)
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="text-secondary border-bottom pb-2 mb-3"><i class="fas fa-info-circle me-2"></i>Descripción</h6>
                        <p class="small">{{ $organization->description }}</p>
                    </div>
                </div>
                @endif
                
                <!-- Estadísticas -->
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="text-secondary border-bottom pb-2 mb-3"><i class="fas fa-chart-bar me-2"></i>Estadísticas</h6>
                        <div class="row">
                            <div class="col-md-4">
                                <div class="small-box text-bg-info">
                                    <div class="inner">
                                        <h4>{{ $organization->events->count() }}</h4>
                                        <p>Eventos</p>
                                    </div>
                                    <div class="icon"><i class="fas fa-calendar-alt"></i></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Lista de Eventos -->
                @if($organization->events->count() > 0)
                <div class="row mt-3">
                    <div class="col-12">
                        <h6 class="text-secondary border-bottom pb-2 mb-3"><i class="fas fa-calendar-alt me-2"></i>Eventos de esta Organización</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover table-bordered border-secondary">
                                <thead class="text-center">
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Inicio</th>
                                        <th>Fin</th>
                                        <th>Estado</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($organization->events as $event)
                                    <tr>
                                        <td>{{ $event->name }}</td>
                                        <td>{{ $event->start_at->format('d/m/Y H:i') }}</td>
                                        <td>{{ $event->end_at->format('d/m/Y H:i') }}</td>
                                        <td class="text-center">
                                            <span class="badge bg-{{ $event->status == 'scheduled' ? 'secondary' : ($event->status == 'ongoing' ? 'success' : 'info') }}">
                                                {{ ucfirst($event->status) }}
                                            </span>
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('events.show', $event->id) }}" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Ver detalles">
                                                <i class="fas fa-eye fa-xs"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </div>
            <div class="card-footer bg-secondary bg-opacity-25 border-top border-dark text-end">
                <a href="{{ route('organizations.index') }}" class="btn btn-secondary btn-sm">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
