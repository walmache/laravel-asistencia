@extends('layouts.adminlte')

@section('title', __('common.attendance') . ' - neuroTech')
@section('page-title', '')

@section('content')


<div class="row">
    <div class="col-12">
        <div class="card border border-info mt-4">
            <div class="card-header bg-light border-bottom">
                <h3 class="card-title">{{ __('common.events') }}</h3>
            </div>
            <div class="card-body table-responsive p-3">
                <table class="table table-hover text-nowrap table-bordered border-info table-sm datatable">
                    <thead>
                        <tr>
                            <th>{{ __('common.events') }}</th>
                            <th>{{ __('common.table_organization') }}</th>
                            <th>{{ __('common.table_start') }}</th>
                            <th>{{ __('common.table_end') }}</th>
                            <th>{{ __('common.table_status') }}</th>
                            <th>{{ __('common.table_attendance') }}</th>
                            <th>{{ __('common.actions') }}</th>
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
                            <td>{{ $event->attendances->count() ?? 0 }}</td>
                            <td>
                                <a href="{{ route('attendance.show', ['id' => $event->id]) }}" class="btn btn-sm btn-primary" data-toggle="tooltip" title="{{ __('common.view_details') }}">
                                    <i class="fas fa-eye fa-xs"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="7" class="text-center">{{ __('common.no_events_available') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection




