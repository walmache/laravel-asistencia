@extends('layouts.adminlte')

@section('title', __('common.events') . ' - neuroTech')
@section('page-title', __('common.events'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('common.events') }}</h3>
                @if(auth()->user()?->hasRole(['admin', 'coordinator']))
                <div class="card-tools">
                    <a href="{{ route('events.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> {{ __('common.create') }}
                    </a>
                </div>
                @endif
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap" id="eventsTable">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Organización</th>
                            <th>Inicio</th>
                            <th>Fin</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($events ?? [] as $event)
                        <tr>
                            <td>{{ $event->name }}</td>
                            <td>{{ $event->organization->name ?? 'N/A' }}</td>
                            <td>{{ $event->start_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $event->end_at->format('d/m/Y H:i') }}</td>
                            <td><span class="badge bg-{{ $event->status == 'scheduled' ? 'secondary' : ($event->status == 'ongoing' ? 'success' : 'info') }}">{{ ucfirst($event->status) }}</span></td>
                            <td>
                                <a href="{{ route('events.show', $event->id) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                @if(auth()->user()?->hasRole(['admin', 'coordinator']))
                                <a href="{{ route('events.edit', $event->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('events.destroy', $event->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro?')"><i class="fas fa-trash"></i></button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center">No hay eventos disponibles</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($events->hasPages())
            <div class="card-footer">
                {{ $events->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
async function loadEvents() {
    if (!window.API_TOKEN) return;
    try {
        const {data} = await axios.get(window.API_BASE_URL + '/events');
        if (data && data.length > 0) {
            const tbody = document.querySelector('#eventsTable tbody');
            tbody.innerHTML = data.map(event => `
                <tr>
                    <td>${event.name}</td>
                    <td>${event.organization?.name || 'N/A'}</td>
                    <td>${new Date(event.start_at).toLocaleDateString('es-ES')}</td>
                    <td>${new Date(event.end_at).toLocaleDateString('es-ES')}</td>
                    <td><span class="badge bg-${event.status === 'scheduled' ? 'secondary' : event.status === 'ongoing' ? 'success' : 'info'}">${event.status}</span></td>
                    <td>
                        <a href="/events/${event.id}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                    </td>
                </tr>
            `).join('');
        }
    } catch(e) { console.error('Error loading events:', e); }
}
if (window.API_TOKEN) loadEvents();
</script>
@endpush


