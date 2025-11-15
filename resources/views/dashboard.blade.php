@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">

    <!-- Título principal -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-brown mb-0">
            <i class="bi bi-speedometer2 me-2"></i> 
            @hasrole('Administrador')
                Panel de Administración
            @else
                Mi Panel de Usuario
            @endhasrole
        </h2>
        <div class="text-muted">
            <i class="bi bi-person-circle me-1"></i>
            {{ Auth::user()->name }}
        </div>
    </div>

    <!-- Tarjetas de estadísticas (solo para admin) -->
    @hasrole('Administrador')
    <div class="row g-4 mb-5">
        <div class="col-lg-3 col-md-6">
            <div class="card text-center border-0 shadow-sm hover-card">
                <div class="card-body">
                    <i class="bi bi-file-earmark-text display-5 text-brown"></i>
                    <h6 class="mt-3 fw-semibold text-secondary">Total Documentos</h6>
                    <h3 class="fw-bold mt-2">{{ \App\Models\Document::count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card text-center border-0 shadow-sm hover-card">
                <div class="card-body">
                    <i class="bi bi-people display-5 text-success"></i>
                    <h6 class="mt-3 fw-semibold text-secondary">Total Usuarios</h6>
                    <h3 class="fw-bold mt-2">{{ \App\Models\User::count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card text-center border-0 shadow-sm hover-card">
                <div class="card-body">
                    <i class="bi bi-cash-coin display-5 text-warning"></i>
                    <h6 class="mt-3 fw-semibold text-secondary">Ventas Totales</h6>
                    <h3 class="fw-bold mt-2">{{ \App\Models\Purchase::where('payment_status', 'completed')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-md-6">
            <div class="card text-center border-0 shadow-sm hover-card">
                <div class="card-body">
                    <i class="bi bi-heart-fill display-5 text-danger"></i>
                    <h6 class="mt-3 fw-semibold text-secondary">Likes Totales</h6>
                    <h3 class="fw-bold mt-2">{{ \App\Models\Like::count() }}</h3>
                </div>
            </div>
        </div>
    </div>
    @else
    <!-- Estadísticas para usuario normal -->
    <div class="row g-4 mb-5">
        <div class="col-lg-4 col-md-6">
            <div class="card text-center border-0 shadow-sm hover-card">
                <div class="card-body">
                    <i class="bi bi-file-earmark-text display-5 text-brown"></i>
                    <h6 class="mt-3 fw-semibold text-secondary">Mis Documentos</h6>
                    <h3 class="fw-bold mt-2">{{ Auth::user()->documents()->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="card text-center border-0 shadow-sm hover-card">
                <div class="card-body">
                    <i class="bi bi-bag-check display-5 text-success"></i>
                    <h6 class="mt-3 fw-semibold text-secondary">Mis Compras</h6>
                    <h3 class="fw-bold mt-2">{{ Auth::user()->purchases()->where('payment_status', 'completed')->count() }}</h3>
                </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-6">
            <div class="card text-center border-0 shadow-sm hover-card">
                <div class="card-body">
                    <i class="bi bi-heart-fill display-5 text-danger"></i>
                    <h6 class="mt-3 fw-semibold text-secondary">Mis Likes</h6>
                    <h3 class="fw-bold mt-2">{{ Auth::user()->likes()->count() }}</h3>
                </div>
            </div>
        </div>
    </div>
    @endhasrole

    <!-- Acciones rápidas para usuario -->
    @hasrole('Usuario')
    <div class="row mb-5">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-lightning-fill text-warning me-2"></i>Acciones Rápidas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3 col-6">
                            <a href="{{ route('repository.gallery') }}" class="btn btn-outline-brown w-100 h-100 py-3">
                                <i class="bi bi-archive display-6 d-block mb-2"></i>
                                <span>Explorar Documentos</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="{{ route('purchases.my') }}" class="btn btn-outline-success w-100 h-100 py-3">
                                <i class="bi bi-bag-check display-6 d-block mb-2"></i>
                                <span>Mis Compras</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="#" class="btn btn-outline-primary w-100 h-100 py-3">
                                <i class="bi bi-heart display-6 d-block mb-2"></i>
                                <span>Favoritos</span>
                            </a>
                        </div>
                        <div class="col-md-3 col-6">
                            <a href="#" class="btn btn-outline-info w-100 h-100 py-3">
                                <i class="bi bi-clock-history display-6 d-block mb-2"></i>
                                <span>Recientes</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endhasrole

    <!-- Anuncios activos -->
    <div class="row">
        <div class="col-12">
            @include('components.announcements')
        </div>
    </div>

    <!-- Documentos recientes para usuario -->
    @hasrole('Usuario')
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-light">
                    <h5 class="mb-0">
                        <i class="bi bi-clock-history text-brown me-2"></i>Documentos Recientes
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        @php
                            $recentDocuments = \App\Models\Document::published()
                                ->latest()
                                ->limit(4)
                                ->get();
                        @endphp
                        
                        @forelse($recentDocuments as $document)
                            <div class="col-lg-3 col-md-6">
                                <div class="card h-100 border">
                                    <div class="card-body text-center">
                                        <i class="bi bi-file-earmark-text display-4 text-brown mb-3"></i>
                                        <h6 class="fw-bold">{{ Str::limit($document->title, 40) }}</h6>
                                        <p class="small text-muted mb-2">
                                            {{ $document->category->name ?? 'Sin categoría' }}
                                        </p>
                                        <div class="d-flex justify-content-between align-items-center">
                                            @if($document->is_free)
                                                <span class="badge bg-success">Gratis</span>
                                            @else
                                                <span class="badge bg-warning text-dark">${{ number_format($document->price, 2) }}</span>
                                            @endif
                                            <a href="{{ route('documents.show', $document->id) }}" 
                                               class="btn btn-sm btn-outline-brown">Ver</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12 text-center py-4">
                                <i class="bi bi-inbox display-4 text-muted mb-3"></i>
                                <p class="text-muted">No hay documentos disponibles</p>
                            </div>
                        @endforelse
                    </div>
                    
                    @if($recentDocuments->count() > 0)
                        <div class="text-center mt-4">
                            <a href="{{ route('repository.gallery') }}" class="btn btn-brown">
                                <i class="bi bi-arrow-right me-2"></i>Ver todos los documentos
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endhasrole

</div>

<!-- Estilos personalizados -->
@push('styles')
<style>
    .text-brown { color: #4e342e !important; }
    .bg-brown { background-color: #4e342e !important; }
    .btn-brown { 
        background-color: #4e342e !important; 
        border-color: #4e342e !important;
        color: white !important;
    }
    .btn-outline-brown { 
        border-color: #4e342e !important;
        color: #4e342e !important;
    }
    .btn-outline-brown:hover { 
        background-color: #4e342e !important;
        color: white !important;
    }
    .hover-card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.15);
    }
</style>
@endpush

@endsection