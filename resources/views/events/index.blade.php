@extends('layouts.adminlte')

@section('title', __('common.events') . ' - neuroTech')
@section('page-title', '')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border">
            <div class="card-header bg-light border-bottom">
                <h3 class="card-title">{{ __('common.events') }}</h3>
                @if(auth()->user()?->hasRole(['admin', 'coordinator']))
                <div class="card-tools">
                    <a href="{{ route('events.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> {{ __('common.create') }}
                    </a>
                </div>
                @endif
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover text-nowrap table-bordered table-sm datatable">
                    <thead>
                        <tr>
                            <th>{{ __('common.table_name') }}</th>
                            <th>{{ __('common.table_organization') }}</th>
                            <th>{{ __('common.table_start') }}</th>
                            <th>{{ __('common.table_end') }}</th>
                            <th>{{ __('common.table_status') }}</th>
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
                            <td>
                                <a href="{{ route('events.show', $event->id) }}" class="btn btn-sm btn-info" data-toggle="tooltip" title="{{ __('common.view_details') }}"><i class="fas fa-eye fa-xs"></i></a>
                                @if(auth()->user()?->hasRole(['admin', 'coordinator']))
                                <a href="{{ route('events.edit', $event->id) }}" class="btn btn-sm btn-warning" data-toggle="tooltip" title="{{ __('common.edit_item') }}"><i class="fas fa-edit fa-xs"></i></a>
                                <form action="{{ route('events.destroy', $event->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" data-toggle="tooltip" title="{{ __('common.delete_item') }}" onclick="return confirm('¿Está seguro?')"><i class="fas fa-trash fa-xs"></i></button>
                                </form>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center">{{ __('common.no_events_available') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection





