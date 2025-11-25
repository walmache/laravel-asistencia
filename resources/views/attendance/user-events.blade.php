@extends('layouts.adminlte')

@section('title', __('common.my_events') . ' - neuroTech')
@section('page-title', __('common.my_events'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Mis Eventos Asignados</h3>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Evento</th>
                            <th>Organización</th>
                            <th>Inicio</th>
                            <th>Fin</th>
                            <th>Estado</th>
                            <th>Mi Asistencia</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($events ?? [] as $event)
                            @php $myAttendance = $event->attendances->where('user_id', auth()->id())->first(); @endphp
                        <tr>
                            <td>{{ $event->name }}</td>
                            <td>{{ $event->organization->name ?? 'N/A' }}</td>
                            <td>{{ $event->start_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $event->end_at->format('d/m/Y H:i') }}</td>
                            <td><span class="badge bg-{{ $event->status == 'scheduled' ? 'secondary' : ($event->status == 'ongoing' ? 'success' : 'info') }}">{{ ucfirst($event->status) }}</span></td>
                            <td>
                                @if($myAttendance)
                                    <span class="badge bg-success">Presente</span>
                                @else
                                    <span class="badge bg-danger">Ausente</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('attendance.show', ['id' => $event->id]) }}" class="btn btn-sm btn-primary">
                                    <i class="fas fa-eye"></i> Ver/Registrar
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center">No estás asignado a ningún evento</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection


