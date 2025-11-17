{{-- resources/views/announcements/create.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm">
                <div class="card-header bg-brown text-white">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-plus-circle"></i> Crear Nuevo Anuncio
                    </h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('announcements.store') }}" method="POST">
                        @csrf

                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Título del Anuncio*</label>
                                <input type="text" name="title" class="form-control"
                                       value="{{ old('title') }}">
                                @error('title')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Descripción</label>
                                <textarea name="description" class="form-control" rows="3">{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Hora de Inicio*</label>
                                <input type="datetime-local" name="start_time" class="form-control"
                                       value="{{ old('start_time') }}" >
                                @error('start_time')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Hora de Fin*</label>
                                <input type="datetime-local" name="end_time" class="form-control"
                                       value="{{ old('end_time') }}" >
                                @error('end_time')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Link de la Reunión*</label>
                                <input type="url" name="link" class="form-control"
                                       value="{{ old('link') }}" placeholder="https://meet.google.com/...">
                                @error('link')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Estado*</label>
                                <select name="status" class="form-select" >
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Activo</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                                    <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelado</option>
                                </select>
                                @error('status')
                                    <div class="text-danger small">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="d-flex justify-content-between mt-4">
                            <a href="{{ route('announcements.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-left"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-brown">
                                <i class="bi bi-check-circle"></i> Crear Anuncio
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
