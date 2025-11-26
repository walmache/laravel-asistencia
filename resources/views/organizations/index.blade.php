@extends('layouts.adminlte')

@section('title', __('common.organizations') . ' - neuroTech')
@section('page-title', '')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border border-info mt-4">
            <div class="card-header bg-light border-bottom">
                <h3 class="card-title">{{ __('common.organizations') }}</h3>
                <div class="card-tools">
                    <a href="{{ route('organizations.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> {{ __('common.create') }}
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive p-3">
                <table class="table table-hover text-nowrap table-bordered border-info table-sm datatable">
                    <thead>
                        <tr>
                            <th>{{ __('common.table_name') }}</th>
                            <th>{{ __('common.table_description') }}</th>
                            <th>{{ __('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($organizations ?? [] as $org)
                        <tr>
                            <td>{{ $org->name }}</td>
                            <td>{{ Str::limit($org->description ?? 'N/A', 50) }}</td>
                            <td>
                                <a href="{{ route('organizations.show', $org->id) }}" class="btn btn-sm btn-info" data-toggle="tooltip" title="{{ __('common.view_details') }}"><i class="fas fa-eye fa-xs"></i></a>
                                <a href="{{ route('organizations.edit', $org->id) }}" class="btn btn-sm btn-warning" data-toggle="tooltip" title="{{ __('common.edit_item') }}"><i class="fas fa-edit fa-xs"></i></a>
                                <form action="{{ route('organizations.destroy', $org->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" data-toggle="tooltip" title="{{ __('common.delete_item') }}" onclick="return confirm('¿Está seguro?')"><i class="fas fa-trash fa-xs"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center">{{ __('common.no_organizations_available') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection




