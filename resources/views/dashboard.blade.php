@extends('layouts.adminlte')

@section('title', __('common.dashboard') . ' - neuroTech')
@section('page-title', '')

@section('content')
@php
    $user = auth()->user();
@endphp

<!-- Small boxes (Stat box) - AdminLTE 4.0 -->
<div class="row mt-3">
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-info">
            <div class="inner">
                <h3 id="stat-events">{{ count($events ?? []) }}</h3>
                <p>{{ __('common.events') }}</p>
            </div>
            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M12.75 12.75a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM7.5 15.75a.75.75 0 100-1.5.75.75 0 000 1.5zM8.25 17.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM9.75 15.75a.75.75 0 100-1.5.75.75 0 000 1.5zM10.5 17.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM12 15.75a.75.75 0 100-1.5.75.75 0 000 1.5zM12.75 17.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM14.25 15.75a.75.75 0 100-1.5.75.75 0 000 1.5zM15 17.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM16.5 15.75a.75.75 0 100-1.5.75.75 0 000 1.5zM15 12.75a.75.75 0 11-1.5 0 .75.75 0 011.5 0zM16.5 13.5a.75.75 0 100-1.5.75.75 0 000 1.5z"></path>
                <path clip-rule="evenodd" fill-rule="evenodd" d="M6.75 2.25A.75.75 0 017.5 3v1.5h9V3A.75.75 0 0118 3v1.5h.75a3 3 0 013 3v11.25a3 3 0 01-3 3H5.25a3 3 0 01-3-3V7.5a3 3 0 013-3H6V3a.75.75 0 01.75-.75zm13.5 9a1.5 1.5 0 00-1.5-1.5H5.25a1.5 1.5 0 00-1.5 1.5v7.5a1.5 1.5 0 001.5 1.5h13.5a1.5 1.5 0 001.5-1.5v-7.5z"></path>
            </svg>
            <a href="{{ route('events.index') }}" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                {{ __('common.view') }} <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-success">
            <div class="inner">
                <h3 id="stat-users">{{ $total_users ?? 0 }}</h3>
                <p>{{ __('common.users') }}</p>
            </div>
            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M18.685 19.097A9.723 9.723 0 0021.75 12c0-5.385-4.365-9.75-9.75-9.75S2.25 6.615 2.25 12a9.723 9.723 0 003.065 7.097A9.716 9.716 0 0012 21.75a9.716 9.716 0 006.685-2.653zm-12.54-1.285A7.486 7.486 0 0112 15a7.486 7.486 0 015.855 2.812A8.224 8.224 0 0112 20.25a8.224 8.224 0 01-5.855-2.438zM15.75 9a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" clip-rule="evenodd" fill-rule="evenodd"></path>
            </svg>
            <a href="{{ route('users.index') }}" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                {{ __('common.view') }} <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-warning">
            <div class="inner">
                <h3 id="stat-attendances">{{ $total_attendances ?? 0 }}</h3>
                <p>{{ __('common.attendance') }}</p>
            </div>
            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path clip-rule="evenodd" fill-rule="evenodd" d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm13.36-1.814a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z"></path>
            </svg>
            <a href="{{ route('attendance.index') }}" class="small-box-footer link-dark link-underline-opacity-0 link-underline-opacity-50-hover">
                {{ __('common.view') }} <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    
    @if($user && $user->hasRole('admin'))
    <div class="col-lg-3 col-6">
        <div class="small-box text-bg-danger">
            <div class="inner">
                <h3 id="stat-organizations">{{ $total_organizations ?? 0 }}</h3>
                <p>{{ __('common.organizations') }}</p>
            </div>
            <svg class="small-box-icon" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path clip-rule="evenodd" fill-rule="evenodd" d="M4.5 2.25a.75.75 0 000 1.5v16.5h-.75a.75.75 0 000 1.5h16.5a.75.75 0 000-1.5h-.75V3.75a.75.75 0 000-1.5h-15zM9 6a.75.75 0 000 1.5h1.5a.75.75 0 000-1.5H9zm-.75 3.75A.75.75 0 019 9h1.5a.75.75 0 010 1.5H9a.75.75 0 01-.75-.75zM9 12a.75.75 0 000 1.5h1.5a.75.75 0 000-1.5H9zm3.75-5.25A.75.75 0 0113.5 6H15a.75.75 0 010 1.5h-1.5a.75.75 0 01-.75-.75zM13.5 9a.75.75 0 000 1.5H15A.75.75 0 0015 9h-1.5zm-.75 3.75a.75.75 0 01.75-.75H15a.75.75 0 010 1.5h-1.5a.75.75 0 01-.75-.75zM9 19.5v-2.25a.75.75 0 01.75-.75h4.5a.75.75 0 01.75.75v2.25a.75.75 0 01-.75.75h-4.5A.75.75 0 019 19.5z"></path>
            </svg>
            <a href="{{ route('organizations.index') }}" class="small-box-footer link-light link-underline-opacity-0 link-underline-opacity-50-hover">
                {{ __('common.view') }} <i class="fas fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    @endif
</div>

<div class="row">
    <div class="col-12">
        <div class="card border border-dark mt-4">
            <div class="card-header bg-secondary bg-opacity-25 border-bottom border-dark">
                <h3 class="card-title">{{ __('common.recent_events') }}</h3>
            </div>
            <div class="card-body table-responsive p-3">
                <table class="table table-hover text-nowrap table-bordered border-secondary table-sm datatable">
                    <thead class="text-center">
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
                        @foreach($events ?? [] as $event)
                        <tr>
                            <td>{{ $event->name }}</td>
                            <td>{{ $event->organization->name ?? 'N/A' }}</td>
                            <td>{{ $event->start_at->format('d/m/Y H:i') }}</td>
                            <td>{{ $event->end_at->format('d/m/Y H:i') }}</td>
                            <td><span class="badge bg-{{ $event->status == 'scheduled' ? 'secondary' : ($event->status == 'ongoing' ? 'success' : 'info') }}">{{ ucfirst($event->status) }}</span></td>
                            <td>{{ $event->users->count() ?? 0 }}</td>
                            <td class="text-center">
                                <a href="{{ route('attendance.show', ['id' => $event->id]) }}" class="btn btn-sm btn-primary" data-bs-toggle="tooltip" title="{{ __('common.view_details') }}"><i class="fas fa-eye fa-xs"></i></a>
                                @if($user && $user->hasRole(['admin', 'coordinator']))
                                <a href="{{ route('events.edit', ['event' => $event->id]) }}" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="{{ __('common.edit_item') }}"><i class="fas fa-edit fa-xs"></i></a>
                                @endif
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
