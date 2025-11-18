@extends('layouts.app')

@section('content')
<div class="container py-4">
    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-brown mb-0">
            <i class="bi bi-archive"></i> Repositorio de Documentos
        </h2>
        @role('Administrador')
        <button class="btn btn-brown" data-bs-toggle="modal" data-bs-target="#uploadModal">
            <i class="bi bi-upload"></i> Subir documento
        </button>
        @endrole
    </div>

    <!-- Mensajes -->
    @if(session('success'))
        <div class="alert alert-success shadow-sm">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger shadow-sm">{{ session('error') }}</div>
    @endif

    <!-- Tabla de documentos -->
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Título</th>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th>Archivo</th>
                            <th>Subido por</th>
                            @role('Administrador')
                            <th>Acciones</th>
                            @endrole
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($documents as $doc)
                            <tr>
                                <td class="fw-semibold">{{ $doc->title }}</td>
                                <td>{{ Str::limit($doc->description, 60) }}</td>
                                <td>
                                    @if($doc->is_free)
                                        <span class="badge bg-success">Gratis</span>
                                    @else
                                        ${{ number_format($doc->price, 2) }}
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ asset('storage/'.$doc->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> Ver
                                    </a>
                                    <a href="{{ route('documents.download', $doc->id) }}" class="btn btn-sm btn-outline-success">
                                        <i class="bi bi-download"></i> Descargar
                                    </a>
                                </td>
                                <td>{{ $doc->user->name ?? 'Desconocido' }}</td>

                                @role('Administrador')
                                <td>
                                    <a href="{{ route('documents.edit', $doc->id) }}" class="btn btn-sm btn-warning me-1">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form action="{{ route('documents.destroy', $doc->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar este documento?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                                @endrole
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-inbox display-6 d-block mb-2"></i>
                                    No hay documentos en el repositorio
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="d-flex justify-content-end mt-3">
                    {{ $documents->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de carga de documentos -->
@role('Administrador')
<div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content border-0 shadow">
      <div class="modal-header bg-brown text-white">
        <h5 class="modal-title fw-bold" id="uploadModalLabel"><i class="bi bi-cloud-upload"></i> Subir nuevo documento</h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Título</label>
                    <input type="text" name="title" class="form-control" placeholder="Nombre del documento" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Precio (opcional)</label>
                    <input type="number" step="0.01" name="price" class="form-control" placeholder="0.00">
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Descripción</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Breve descripción del contenido"></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Archivo</label>
                    <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.ppt,.pptx" required>
                    <div class="form-text">Formatos permitidos: solo PDF (máx. 10MB)</div>
                </div>
            </div>
            <div class="mt-4 text-end">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" class="btn btn-brown">
                    <i class="bi bi-upload"></i> Subir documento
                </button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endrole
@endsection
