@extends('layouts.adminlte')

@section('title', __('common.users') . ' - neuroTech')
@section('page-title', '')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border">
            <div class="card-header bg-light border-bottom">
                <h3 class="card-title">{{ __('common.users') }}</h3>
                <div class="card-tools">
                    <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> {{ __('common.create') }}
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive">
                <table class="table table-hover text-nowrap table-bordered table-sm datatable">
                    <thead>
                        <tr>
                            <th>{{ __('common.table_name') }}</th>
                            <th>{{ __('common.table_email') }}</th>
                            <th>{{ __('common.table_role') }}</th>
                            <th>{{ __('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users ?? [] as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td><span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'coordinator' ? 'warning' : 'info') }}">{{ ucfirst($user->role) }}</span></td>
                            <td>
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning" data-toggle="tooltip" title="{{ __('common.edit_item') }}"><i class="fas fa-edit fa-xs"></i></a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" data-toggle="tooltip" title="{{ __('common.delete_item') }}" onclick="return confirm('¿Está seguro?')"><i class="fas fa-trash fa-xs"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="4" class="text-center">{{ __('common.no_users_available') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection




