@extends('layouts.adminlte')

@section('title', __('common.my_events') . ' - neuroTech')
@section('page-title', __('common.my_events'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border border-dark mt-4">
            <div class="card-header bg-secondary bg-opacity-25 border-bottom border-dark">
                <h3 class="card-title">Mis Eventos Asignados</h3>
            </div>
            <div class="card-body table-responsive p-3">
                <table class="table table-hover text-nowrap table-bordered border-secondary table-sm datatable">
                    <thead class="text-center">
                        <tr>
                            <th>{{ __('common.events') }}</th>
                            <th>{{ __('common.table_organization') }}</th>
                            <th>{{ __('common.table_start') }}</th>
                            <th>{{ __('common.table_end') }}</th>
                            <th>{{ __('common.table_status') }}</th>
                            <th>{{ __('common.table_my_attendance') }}</th>
                            <th>{{ __('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($events ?? [] as $event)
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
                            <td class="text-center">
                                <a href="{{ route('attendance.show', ['id' => $event->id]) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{ __('common.view_details') }}">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection




