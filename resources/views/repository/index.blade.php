@extends('layouts.app')

@section('content')
<div class="container py-5">
    <!-- Encabezado -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-3">
        <div class="d-flex align-items-center gap-3">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                <i class="bi bi-house-door"></i> Inicio
            </a>
            <h2 class="fw-bold text-brown mb-0">
                <i class="bi bi-archive"></i> Repositorio CECIC
            </h2>
        </div>

        <div class="d-flex gap-2 align-items-center flex-wrap">
            <form action="{{ route('repository.index') }}" method="GET" class="d-flex gap-2 align-items-center">
                <select name="category_id" class="form-select" onchange="this.form.submit()" style="min-width: 200px;">
                    <option value="">Todas las categorias</option>
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

            @if (request('category_id'))
                @php
                    $selectedCategory = $categories->firstWhere('id', request('category_id'));
                @endphp

                <form id="deleteCategoryForm"
                      action="{{ route('categories.destroy', request('category_id')) }}"
                      method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                </form>

                <button type="button"
                        class="btn btn-outline-danger"
                        data-bs-toggle="modal"
                        data-bs-target="#confirmDeleteCategoryModal">
                    <i class="bi bi-trash"></i>
                </button>
            @endif

            <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#categoryModal">
                <i class="bi bi-tags"></i> Nueva categoria
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
    <div class="table-responsive">
        <table class="table table-hover bg-white shadow-sm align-middle">
            <thead class="table-light">
                <tr>
                    <th>Titulo</th>
                    <th>Categoria</th>
                    @hasanyrole('Administrador|Empleado')
                        <th>Creado por</th>
                    @endhasanyrole
                    <th>Estado</th>
                    <th>Descripcion</th>
                    <th>Archivo</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            @forelse($documents as $doc)
                <tr>
                    <td class="fw-semibold">{{ $doc->title }}</td>
                    <td>{{ $doc->category->name ?? 'Sin categoria' }}</td>
                    @hasanyrole('Administrador|Empleado')
                        <td>{{ $doc->user->name ?? 'N/D' }}</td>
                    @endhasanyrole
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
                        <form id="deleteDocumentForm-{{ $doc->id }}"
                              method="POST"
                              action="{{ route('repository.toggle', $doc->id) }}"
                              class="d-inline">
                            @csrf
                            @method('PATCH')

                            <button type="button"
                                    class="btn btn-sm btn-danger"
                                    data-bs-toggle="modal"
                                    data-bs-target="#confirmDeleteDocumentModal"
                                    data-doc-id="{{ $doc->id }}"
                                    data-doc-title="{{ $doc->title }}">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ auth()->user()->hasAnyRole(['Administrador','Empleado']) ? 7 : 6 }}" class="text-center text-muted py-4">
                        <i class="bi bi-inbox display-6 d-block mb-2"></i>
                        No hay documentos registrados.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="d-flex justify-content-end mt-3">
        {{ $documents->links() }}
    </div>
</div>

<!-- Modal Subir Documento -->
<div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true">
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
                    <label class="form-label fw-semibold">Titulo*</label>
                    <input type="text" name="title" class="form-control with-counter" maxlength="255" data-max="255">
                    <div class="form-text text-end"><small class="counter">0/255</small></div>
                    @error('title')
                    <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Categoria</label>
                    <div class="input-group">
                        <select name="category_id" id="categorySelect" class="form-select">
                            <option value="">-- Sin categoria --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                        <button type="button" class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#categoryModal">
                            <i class="bi bi-plus-lg"></i>
                        </button>
                    </div>
                    @error('category_id')
                    <div class="text-danger small">{{ $message }}</div>
                    @enderror
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
                    <input type="number" name="price" class="form-control" step="0.01" min="0" max="99999999.99" placeholder="0.00">
                    @error('price')
                    <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-12">
                    <label class="form-label fw-semibold">Descripcion</label>
                    <textarea name="description" class="form-control with-counter" rows="3" maxlength="300" data-max="300"></textarea>
                    <div class="form-text text-end"><small class="counter">0/300</small></div>
                    @error('description')
                    <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Archivo</label>
                    <input type="file" name="file" class="form-control">
                    <div class="form-text">Formatos: PDF (max. 10MB)</div>
                    @error('file')
                    <div class="text-danger small">{{ $message }}</div>
                    @enderror
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

<!-- Modal Confirmar Eliminacion de Documento -->
<div class="modal fade" id="confirmDeleteDocumentModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">
            <i class="bi bi-file-earmark-x"></i> Eliminar documento
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p class="mb-2">
            Seguro que deseas eliminar el documento
            <strong id="docTitleToDelete">seleccionado</strong>?
        </p>
        <p class="text-muted mb-0">
            Esta accion no se puede deshacer.
        </p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-danger" id="confirmDeleteDocumentBtn">
            <i class="bi bi-trash"></i> Eliminar
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Modal Crear Categoria -->
<div class="modal fade" id="categoryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-secondary text-white">
        <h5 class="modal-title"><i class="bi bi-tags"></i> Nueva categoria</h5>
        <button class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <form method="POST" action="{{ route('categories.store') }}" novalidate>
            @csrf
            <div class="mb-3">
                <label class="form-label fw-semibold">Nombre de la categoria</label>
                <input type="text" name="name" id="categoryName" class="form-control" value="{{ old('name') }}">
                @error('name')
                <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            <div class="text-end">
                <button type="submit" class="btn btn-secondary">Guardar</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Modal Confirmar Eliminacion de Categoria -->
<div class="modal fade" id="confirmDeleteCategoryModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">
            <i class="bi bi-exclamation-triangle"></i> Eliminar categoria
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p class="mb-2">
            Seguro que deseas eliminar la categoria
            <strong>{{ $selectedCategory->name ?? 'seleccionada' }}</strong>?
        </p>
        <p class="text-muted">
            Esta accion no se puede deshacer.
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

@push('scripts')
<script>
function togglePriceField() {
    const isFree = document.getElementById('is_free').value;
    const priceField = document.getElementById('priceField');
    priceField.style.display = (isFree === "0") ? 'block' : 'none';
}

document.addEventListener('DOMContentLoaded', function () {
    togglePriceField();

    const counters = document.querySelectorAll('.with-counter');
    counters.forEach(el => {
        const max = parseInt(el.dataset.max || el.getAttribute('maxlength'), 10);
        const counter = el.parentElement.querySelector('.counter');
        const update = () => { if (counter) counter.textContent = `${el.value.length}/${max}`; };
        el.addEventListener('input', update);
        update();
    });

    const confirmDeleteCategoryBtn = document.getElementById('confirmDeleteCategoryBtn');
    const deleteCategoryForm = document.getElementById('deleteCategoryForm');
    if (confirmDeleteCategoryBtn && deleteCategoryForm) {
        confirmDeleteCategoryBtn.addEventListener('click', function () {
            deleteCategoryForm.submit();
        });
    }

    @if($errors->has('name'))
        const categoryModalEl = document.getElementById('categoryModal');
        if (categoryModalEl) {
            const catModal = new bootstrap.Modal(categoryModalEl);
            catModal.show();
        }
    @endif

    const docModal = document.getElementById('confirmDeleteDocumentModal');
    const confirmDocBtn = document.getElementById('confirmDeleteDocumentBtn');
    let currentDocId = null;

    if (docModal) {
        docModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const docId = button.getAttribute('data-doc-id');
            const docTitle = button.getAttribute('data-doc-title');

            currentDocId = docId;
            const titleSpan = docModal.querySelector('#docTitleToDelete');
            if (titleSpan) {
                titleSpan.textContent = docTitle || 'este documento';
            }
        });
    }

    if (confirmDocBtn) {
        confirmDocBtn.addEventListener('click', function () {
            if (!currentDocId) return;
            const form = document.getElementById('deleteDocumentForm-' + currentDocId);
            if (form) form.submit();
        });
    }
});
</script>
@endpush
@endsection
