@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2 class="fw-bold text-brown mb-4">Subir nuevo documento</h2>

    <div class="card shadow-sm border-0 p-4">
        <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label">Título</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Descripción</label>
                <textarea name="description" rows="3" class="form-control"></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Archivo</label>
                <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.ppt,.pptx" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Precio (opcional)</label>
                <input type="number" step="0.01" name="price" class="form-control" placeholder="0.00">
            </div>

            <button type="submit" class="btn btn-brown">
                <i class="bi bi-cloud-upload"></i> Subir Documento
            </button>
        </form>
    </div>
</div>
@endsection
