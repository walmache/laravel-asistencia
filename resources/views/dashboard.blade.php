@extends('layouts.adminlte')

@section('title', __('common.dashboard') . ' - neuroTech')
@section('page-title', '')

@section('content')
@php
    $user = auth()->user();
@endphp

<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3 id="stat-events">{{ count($events ?? []) }}</h3>
                <p>{{ __('common.events') }}</p>
            </div>
            <div class="icon"><i class="fas fa-calendar-alt"></i></div>
            <a href="{{ route('events.index') }}" class="small-box-footer">{{ __('common.view') }} <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3 id="stat-users">{{ $total_users ?? 0 }}</h3>
                <p>{{ __('common.users') }}</p>
            </div>
            <div class="icon"><i class="fas fa-users"></i></div>
            <a href="{{ route('users.index') }}" class="small-box-footer">{{ __('common.view') }} <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3 id="stat-attendances">{{ $total_attendances ?? 0 }}</h3>
                <p>{{ __('common.attendance') }}</p>
            </div>
            <div class="icon"><i class="fas fa-check-circle"></i></div>
            <a href="{{ route('attendance.index') }}" class="small-box-footer">{{ __('common.view') }} <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    
    @if($user && $user->hasRole('admin'))
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3 id="stat-organizations">{{ $total_organizations ?? 0 }}</h3>
                <p>{{ __('common.organizations') }}</p>
            </div>
            <div class="icon"><i class="fas fa-building"></i></div>
            <a href="{{ route('organizations.index') }}" class="small-box-footer">{{ __('common.view') }} <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    @endif
</div>

<div class="row">
    <div class="col-12">
        <div class="card border border-info mt-4">
            <div class="card-header bg-light border-bottom">
                <h3 class="card-title">{{ __('common.recent_events') }}</h3>
            </div>
            <div class="card-body table-responsive p-3">
                <table class="table table-hover text-nowrap table-bordered border-info table-sm datatable">
                    <thead>
                        <tr>
                            <th>{{ __('common.table_name') }}</th>
                            <th>{{ __('common.table_organization') }}</th>
                            <th>{{ __('common.table_start') }}</th>
                            <th>{{ __('common.table_end') }}</th>
                            <th>{{ __('common.table_status') }}</th>
                            <th>{{ __('common.table_attendees') }}</th>
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
                            <td>{{ $event->users->count() ?? 0 }}</td>
                            <td>
                                <a href="{{ route('attendance.show', ['id' => $event->id]) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{ __('common.view_details') }}"><i class="fas fa-eye fa-xs"></i></a>
                                @if($user && $user->hasRole(['admin', 'coordinator']))
                                <a href="{{ route('events.edit', ['event' => $event->id]) }}" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="{{ __('common.edit_item') }}"><i class="fas fa-edit fa-xs"></i></a>
                                @endif
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

@push('scripts')
<script>
async function loadStats() {
    if (!window.API_TOKEN) return;
    try {
        const {data} = await axios.get(window.API_BASE_URL + '/dashboard/statistics');
        if (data) {
            document.getElementById('stat-events')?.textContent = data.total_events || 0;
            document.getElementById('stat-users')?.textContent = data.total_users || 0;
            document.getElementById('stat-attendances')?.textContent = data.total_attendances || 0;
            document.getElementById('stat-organizations')?.textContent = data.total_organizations || 0;
        }
    } catch(e) { console.error('Error loading stats:', e); }
}
loadStats();
</script>
@endpush
