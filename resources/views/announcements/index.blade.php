{{-- resources/views/announcements/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-brown">
            <i class="bi bi-megaphone"></i> Gestión de Anuncios
        </h2>
        <a href="{{ route('announcements.create') }}" class="btn btn-brown">
            <i class="bi bi-plus-circle"></i> Nuevo Anuncio
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Título</th>
                            <th>Inicio</th>
                            <th>Fin</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($announcements as $announcement)
                            <tr>
                                <td class="fw-semibold">{{ $announcement->title }}</td>
                                <td>{{ $announcement->start_time->format('d/m/Y H:i') }}</td>
                                <td>{{ $announcement->end_time->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($announcement->status == 'active' && $announcement->start_time <= now() && $announcement->end_time >= now())
                                        <span class="badge bg-success">En Curso</span>
                                    @elseif($announcement->status == 'active' && $announcement->start_time > now())
                                        <span class="badge bg-warning text-dark">Programado</span>
                                    @elseif($announcement->status == 'cancelled')
                                        <span class="badge bg-danger">Cancelado</span>
                                    @else
                                        <span class="badge bg-secondary">Inactivo</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('announcements.edit', $announcement->id) }}" 
                                       class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('announcements.destroy', $announcement->id) }}" 
                                          method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('¿Eliminar este anuncio?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">
                                    <i class="bi bi-megaphone display-6 d-block mb-2"></i>
                                    No hay anuncios registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-end mt-3">
                {{ $announcements->links() }}
            </div>
        </div>
    </div>
</div>
@endsection