@extends('layouts.app')
@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-brown mb-0">
            <i class="bi bi-pencil-square"></i> Editar Documento
        </h3>
        <a href="{{ route('repository.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Volver al Repositorio
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
{{--    @if($errors->any())--}}
{{--        <div class="alert alert-danger">--}}
{{--            <strong>¡Ups!</strong> Hay algunos errores:<br>--}}
{{--            <ul class="mb-0">--}}
{{--                @foreach($errors->all() as $e)--}}
{{--                    <li>{{ $e }}</li>--}}
{{--                @endforeach--}}
{{--            </ul>--}}
{{--        </div>--}}
{{--    @endif--}}

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('repository.update', $document->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Título*</label>
                        <input type="text" name="title" class="form-control with-counter"
                               maxlength="255" data-max="255"
                               value="{{ old('title', $document->title) }}">
                        <div class="form-text text-end"><small class="counter">0/255</small></div>
                        @error('title')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Categoría</label>
                        <select name="category_id" class="form-select">
                            <option value="">-- Sin categoría --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $document->category_id == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror

                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tipo de documento</label>
                        <select name="is_free" id="is_free" class="form-select" onchange="togglePriceField()">
                            <option value="1" {{ $document->is_free ? 'selected' : '' }}>Gratis</option>
                            <option value="0" {{ !$document->is_free ? 'selected' : '' }}>De pago</option>
                        </select>
                    </div>

                    <div class="col-md-6" id="priceField" style="{{ $document->is_free ? 'display:none;' : '' }}">
                        <label class="form-label fw-semibold">Precio</label>
                        <input type="number" step="0.01" min="0" max="99999999.99" name="price" class="form-control"
                               value="{{ old('price', $document->price) }}" placeholder="0.00">
                        @error('price')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Descripción</label>
                        <textarea name="description" class="form-control with-counter" rows="3" maxlength="300" data-max="300">{{ old('description', $document->description) }}</textarea>
                        <div class="form-text text-end"><small class="counter">0/300</small></div>
                        @error('description')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Archivo actual</label>
                        <div class="d-flex align-items-center gap-2">
                            <a href="{{ asset('storage/'.$document->file_path) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-eye"></i> Ver archivo
                            </a>
                            <small class="text-muted text-truncate">{{ basename($document->file_path) }}</small>
                        </div>
                    </div>

                    <div class="col-12">
                        <label class="form-label fw-semibold">Reemplazar archivo (opcional)</label>
                        <input type="file" name="file" class="form-control" accept=".pdf,.doc,.docx,.ppt,.pptx">
                        <div class="form-text">Formatos: PDF, Word o PowerPoint (máx. 10MB)</div>
                    </div>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-brown">
                        <i class="bi bi-save"></i> Guardar cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function togglePriceField() {
    const isFree = document.getElementById('is_free').value;
    const priceField = document.getElementById('priceField');
    priceField.style.display = (isFree == "0") ? 'block' : 'none';
}

document.addEventListener('DOMContentLoaded', () => {
    const counters = document.querySelectorAll('.with-counter');
    counters.forEach(el => {
        const max = parseInt(el.dataset.max || el.getAttribute('maxlength'), 10);
        const counter = el.parentElement.querySelector('.counter');
        const update = () => { if (counter) counter.textContent = `${el.value.length}/${max}`; };
        el.addEventListener('input', update);
        update();
    });
    togglePriceField();
});
</script>
@endsection
