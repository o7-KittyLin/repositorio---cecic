{{-- resources/views/purchases/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-brown">
            <i class="bi bi-bag-check"></i> Mis Documentos Adquiridos
        </h2>
        <a href="{{ route('repository.gallery') }}" class="btn btn-outline-brown">
            <i class="bi bi-arrow-left"></i> Volver al Repositorio
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-4">
        @forelse($purchases as $purchase)
            @php $document = $purchase->document; @endphp
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-sm h-100">
                    @if(Str::endsWith($document->file_path, '.pdf'))
                        <iframe src="{{ asset('storage/'.$document->file_path) }}#toolbar=0&navpanes=0" 
                                class="preview-frame"></iframe>
                    @else
                        <div class="d-flex align-items-center justify-content-center bg-light preview-frame text-muted">
                            <i class="bi bi-file-earmark-text fs-1"></i>
                        </div>
                    @endif

                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title fw-bold text-brown">{{ $document->title }}</h5>
                        
                        <small class="text-muted d-block mb-2">
                            {{ $document->category->name ?? 'Sin categoría' }}
                        </small>

                        <p class="card-text small text-secondary flex-grow-1">
                            {{ Str::limit($document->description, 100) }}
                        </p>

                        <div class="d-flex justify-content-between align-items-center mt-auto">
                            <small class="text-muted">
                                Adquirido: {{ $purchase->created_at->format('d/m/Y') }}
                            </small>
                            <div class="d-flex gap-2">
                                <a href="{{ asset('storage/'.$document->file_path) }}" 
                                   target="_blank" class="btn btn-sm btn-outline-primary" title="Ver">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('repository.download', $document->id) }}" 
                                   class="btn btn-sm btn-outline-success" title="Descargar">
                                    <i class="bi bi-download"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12">
                <div class="text-center text-muted py-5">
                    <i class="bi bi-bag-x display-6 d-block mb-3"></i>
                    <h4>No has adquirido ningún documento aún</h4>
                    <p class="mb-4">Explora nuestro repositorio y adquiere documentos de tu interés.</p>
                    <a href="{{ route('repository.gallery') }}" class="btn btn-brown">
                        <i class="bi bi-archive"></i> Explorar Repositorio
                    </a>
                </div>
            </div>
        @endforelse
    </div>

    <div class="d-flex justify-content-center mt-4">
        {{ $purchases->links() }}
    </div>
</div>

<style>
.preview-frame {
    height: 200px;
    width: 100%;
    border: none;
    background-color: #f0f0f0;
    object-fit: cover;
}
</style>
@endsection