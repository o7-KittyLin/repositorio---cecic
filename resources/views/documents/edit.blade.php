@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-brown mb-4">Editar Documento</h2>

    <div class="card shadow-sm border-0 p-4">
        <form action="{{ route('documents.update', $document->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Título</label>
                <input type="text" name="title" class="form-control" value="{{ $document->title }}" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea name="description" rows="3" class="form-control">{{ $document->description }}</textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Precio</label>
                <input type="number" step="0.01" name="price" class="form-control" value="{{ $document->price }}">
            </div>

            <button type="submit" class="btn btn-warning"><i class="bi bi-save"></i> Actualizar</button>
            <a href="{{ route('documents.index') }}" class="btn btn-secondary">Volver</a>
        </form>
    </div>
</div>
@endsection
