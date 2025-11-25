@extends('layouts.adminlte')

@section('title', __('common.attendance') . ' - neuroTech')
@section('page-title', '')

@section('content')


<div class="card border-primary mb-3" style="max-width: 18rem;">
  <div class="card-header">Header</div>
  <div class="card-body text-primary">
    <h5 class="card-title">Primary card title</h5>
    <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
  </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="mt-2 card border-primary">
            <div class="card-header bg-light border-bottom">
                <h3 class="card-title">{{ __('common.events') }}</h3>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover text-nowrap table-bordered table-sm datatable">
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
                                <a href="{{ route('attendance.show', ['id' => $event->id]) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{ __('common.view_details') }}">
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




