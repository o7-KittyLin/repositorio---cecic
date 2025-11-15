{{-- resources/views/purchases/success.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0 text-center">
                <div class="card-body py-5">
                    <div class="mb-4">
                        <i class="bi bi-check-circle-fill display-1 text-success"></i>
                    </div>
                    
                    <h2 class="fw-bold text-brown mb-3">¡Compra Exitosa!</h2>
                    
                    <div class="card border-0 bg-light mb-4">
                        <div class="card-body">
                            <h5 class="fw-bold">{{ $purchase->document->title }}</h5>
                            <p class="text-muted mb-2">{{ $purchase->document->category->name ?? 'Sin categoría' }}</p>
                            <div class="d-flex justify-content-center gap-4 text-muted small">
                                <span><i class="bi bi-clock"></i> {{ $purchase->created_at->format('d/m/Y H:i') }}</span>
                                <span><i class="bi bi-cash-coin"></i> ${{ number_format($purchase->amount, 2) }}</span>
                            </div>
                        </div>
                    </div>

                    <p class="text-muted mb-4">
                        Tu documento ha sido adquirido exitosamente. Ya puedes descargarlo y acceder a él cuando lo necesites.
                    </p>

                    <div class="d-flex justify-content-center gap-3">
                        <a href="{{ route('repository.download', $purchase->document->id) }}" 
                           class="btn btn-success">
                            <i class="bi bi-download"></i> Descargar Documento
                        </a>
                        <a href="{{ route('purchases.my') }}" class="btn btn-outline-brown">
                            <i class="bi bi-bag-check"></i> Ver Mis Compras
                        </a>
                        <a href="{{ route('repository.gallery') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Seguir Explorando
                        </a>
                    </div>

                    <div class="mt-4 p-3 bg-light rounded">
                        <h6 class="fw-semibold">¿Necesitas ayuda?</h6>
                        <p class="small text-muted mb-0">
                            Si tienes problemas para descargar el documento, contacta con nuestro soporte técnico.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection