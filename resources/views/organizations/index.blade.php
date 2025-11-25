@extends('layouts.adminlte')

@section('title', __('common.organizations') . ' - neuroTech')
@section('page-title', __('common.organizations'))

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">{{ __('common.organizations') }}</h3>
                <div class="card-tools">
                    <a href="{{ route('organizations.create') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-plus"></i> {{ __('common.create') }}
                    </a>
                </div>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($organizations ?? [] as $org)
                        <tr>
                            <td>{{ $org->name }}</td>
                            <td>{{ Str::limit($org->description ?? 'N/A', 50) }}</td>
                            <td>
                                <a href="{{ route('organizations.show', $org->id) }}" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>
                                <a href="{{ route('organizations.edit', $org->id) }}" class="btn btn-sm btn-warning"><i class="fas fa-edit"></i></a>
                                <form action="{{ route('organizations.destroy', $org->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Está seguro?')"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center">No hay organizaciones disponibles</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($organizations->hasPages())
            <div class="card-footer">
                {{ $organizations->links() }}
            </div>
            @endif
        </div>
    </div>
</div>
@endsection


