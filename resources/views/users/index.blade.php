@extends('layouts.adminlte')

@section('title', __('common.users') . ' - neuroTech')
@section('page-title', '')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card border border-dark mt-4">
            <div class="card-header bg-secondary bg-opacity-25 border-bottom border-dark">
                <h3 class="card-title">{{ __('common.users') }}</h3>
                <div class="card-tools">
                    <a href="{{ route('users.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> {{ __('common.create') }}
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive p-3">
                <table class="table table-hover text-nowrap table-bordered border-secondary table-sm datatable">
                    <thead class="text-center">
                        <tr>
                            <th>{{ __('common.table_name') }}</th>
                            <th>{{ __('common.table_email') }}</th>
                            <th>{{ __('common.table_role') }}</th>
                            <th>{{ __('common.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users ?? [] as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td><span class="badge bg-{{ $user->role == 'admin' ? 'danger' : ($user->role == 'coordinator' ? 'warning' : 'info') }}">{{ ucfirst($user->role) }}</span></td>
                            <td class="text-center">
                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-warning" data-bs-toggle="tooltip" title="{{ __('common.edit_item') }}"><i class="fas fa-edit fa-xs"></i></a>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" data-bs-toggle="tooltip" title="{{ __('common.delete_item') }}" onclick="return confirm('¿Está seguro?')"><i class="fas fa-trash fa-xs"></i></button>
                                </form>
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




