{{-- resources/views/announcements/edit.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-warning text-dark">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-pencil"></i> Editar Anuncio
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('announcements.update', $announcement->id) }}" method="POST">
                        @csrf @method('PUT')
                        
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Título del Anuncio</label>
                                <input type="text" name="title" class="form-control" 
                                       value="{{ old('title', $announcement->title) }}" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Descripción</label>
                                <textarea name="description" class="form-control" rows="3">{{ old('description', $announcement->description) }}</textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Hora de Inicio</label>
                                <input type="datetime-local" name="start_time" class="form-control" 
                                       value="{{ old('start_time', $announcement->start_time->format('Y-m-d\TH:i')) }}" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Hora de Fin</label>
                                <input type="datetime-local" name="end_time" class="form-control" 
                                       value="{{ old('end_time', $announcement->end_time->format('Y-m-d\TH:i')) }}" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Link de la Reunión</label>
                                <input type="url" name="link" class="form-control" 
                                       value="{{ old('link', $announcement->link) }}" placeholder="https://meet.google.com/...">
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Estado</label>
                                <select name="status" class="form-select" required>
                                    <option value="active" {{ $announcement->status == 'active' ? 'selected' : '' }}>Activo</option>
                                    <option value="inactive" {{ $announcement->status == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                                    <option value="cancelled" {{ $announcement->status == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('announcements.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-check-circle"></i> Actualizar Anuncio
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection