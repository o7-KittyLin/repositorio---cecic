@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-house-door"></i> Inicio
            </a>
            <h2 class="fw-bold text-brown mb-0">
                <i class="bi bi-archive"></i> Repositorio CECIC
            </h2>
        </div>

<div class="d-flex gap-2">
    <!-- Filtro por categoría -->
    <form action="{{ route('repository.index') }}" method="GET" class="d-flex gap-2 align-items-center">
        <select name="category_id" class="form-select" onchange="this.form.submit()" style="min-width: 200px;">
            <option value="">Todas las categorías</option>
            @foreach ($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                    {{ $cat->name }}
                </option>
            @endforeach
        </select>

        @if (request('category_id'))
            <a href="{{ route('repository.index') }}" class="btn btn-outline-secondary" title="Quitar filtro">
                <i class="bi bi-x"></i>
            </a>
        @endif
    </form>

    {{-- Botón para eliminar la categoría seleccionada --}}
    @if (request('category_id'))
        @php
            $selectedCategory = $categories->firstWhere('id', request('category_id'));
        @endphp

        {{-- Formulario oculto que realmente hace el DELETE --}}
        <form id="deleteCategoryForm"
            action="{{ route('categories.destroy', request('category_id')) }}"
            method="POST" class="d-inline">
            @csrf
            @method('DELETE')
        </form>

        {{-- Botón que solo abre el modal bonito --}}
        <button type="button"
                class="btn btn-outline-danger"
                data-bs-toggle="modal"
                data-bs-target="#confirmDeleteCategoryModal">
            <i class="bi bi-trash"></i>
        </button>
    @endif


    <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#categoryModal">
        <i class="bi bi-tags"></i> Nueva Categoría
    </button>
    <button class="btn btn-brown" data-bs-toggle="modal" data-bs-target="#uploadModal">
        <i class="bi bi-upload"></i> Subir documento
    </button>
</div>

    </div>

    <!-- Mensajes -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif


    <!-- Tabla de documentos -->
    <table class="table table-hover bg-white shadow-sm align-middle">
        <thead class="table-light">
            <tr>
                <th>Título</th>
                <th>Categoría</th>
                <th>Tipo</th>
                <th>Descripción</th>
                <th>Archivo</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
        @forelse($documents as $doc)
            <tr>
                <td class="fw-semibold">{{ $doc->title }}</td>
                <td>{{ $doc->category->name ?? 'Sin categoría' }}</td>
                <td>
                    @if($doc->is_free)
                        <span class="badge bg-success">Gratis</span>
                    @else
                        <span class="badge bg-warning text-dark">De pago ${{ number_format($doc->price, 2) }}</span>
                    @endif
                </td>
                <td>{{ Str::limit($doc->description, 60) }}</td>
                <td>
                    <a href="{{ asset('storage/'.$doc->file_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-eye"></i> Ver
                    </a>
                    <a href="{{ route('repository.download', $doc->id) }}" class="btn btn-sm btn-outline-success">
                        <i class="bi bi-download"></i> Descargar
                    </a>
                </td>
                <td>
                    <a href="{{ route('repository.edit', $doc->id) }}" class="btn btn-sm btn-warning">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <form method="POST" action="{{ route('repository.toggle', $doc->id) }}" class="d-inline">
                        @csrf @method('PATCH')
                        <button class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar documento?')">
                            <i class="bi bi-trash"></i>
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center text-muted py-4">
                    <i class="bi bi-inbox display-6 d-block mb-2"></i>
                    No hay documentos registrados.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>

    <div class="d-flex justify-content-end mt-3">
        {{ $documents->links() }}
    </div>
</div>

<!-- Modal Subir Documento -->
<div class="modal fade" id="uploadModal" tabindex="-1">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header bg-brown text-white">
        <h5 class="modal-title"><i class="bi bi-cloud-upload"></i> Subir Documento</h5>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{ route('repository.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Título</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Categoría</label>
                    <div class="input-group">
                        <select name="category_id" id="categorySelect" class="form-select">
                            <option value="">-- Sin categoría --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#categoryModal">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Tipo de documento</label>
                    <select name="is_free" id="is_free" class="form-select" onchange="togglePriceField()">
                        <option value="1">Gratis</option>
                        <option value="0">De pago</option>
                    </select>
                </div>

                <div class="col-md-6" id="priceField" style="display:none;">
                    <label class="form-label fw-semibold">Precio</label>
                    <input type="number" name="price" class="form-control" step="0.01" placeholder="0.00">
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Descripción</label>
                    <textarea name="description" class="form-control" rows="3"></textarea>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Archivo</label>
                    <input type="file" name="file" class="form-control" required>
                    <div class="form-text">Formatos: PDF (máx. 10MB)</div> <!-- , Word o PowerPoint  -->
                </div>
            </div>
            <div class="text-end mt-3">
                <button type="submit" class="btn btn-brown">
                    <i class="bi bi-upload"></i> Subir
                </button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>


<!-- Modal Crear Categoría -->
<div class="modal fade" id="categoryModal" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-secondary text-white">
        <h5 class="modal-title"><i class="bi bi-tags"></i> Nueva Categoría</h5>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{ route('categories.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Nombre de la categoría</label>
                <input type="text" name="name" id="categoryName" class="form-control" required>
            </div>
            <div class="text-end">
                <button type="submit" class="btn btn-secondary">Guardar</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Confirmar Eliminación de Categoría -->
<div class="modal fade" id="confirmDeleteCategoryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">
            <i class="bi bi-exclamation-triangle"></i> Eliminar categoría
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p class="mb-2">
            ¿Seguro que deseas eliminar la categoría
            <strong>{{ $selectedCategory->name ?? 'seleccionada' }}</strong>?
        </p>
        <p class="text-muted">
            Esta acción no se puede deshacer.
        </p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-danger" id="confirmDeleteCategoryBtn">
            <i class="bi bi-trash"></i> Eliminar
        </button>
      </div>
    </div>
  </div>
</div>


<script>
function togglePriceField() {
    const isFree = document.getElementById('is_free').value;
    const priceField = document.getElementById('priceField');
    priceField.style.display = (isFree == "0") ? 'block' : 'none';
}

function togglePriceField() {
    const isFree = document.getElementById('is_free').value;
    const priceField = document.getElementById('priceField');
    priceField.style.display = (isFree == "0") ? 'block' : 'none';
}

// 🗑 Confirmar eliminación de categoría con modal
document.addEventListener('DOMContentLoaded', function () {
    const confirmDeleteBtn = document.getElementById('confirmDeleteCategoryBtn');
    const deleteForm = document.getElementById('deleteCategoryForm');

    if (confirmDeleteBtn && deleteForm) {
        confirmDeleteBtn.addEventListener('click', function () {
            deleteForm.submit();
        });
    }
});
</script>
@endsection
