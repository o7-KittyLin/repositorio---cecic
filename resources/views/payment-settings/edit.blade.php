@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold text-brown">
            <i class="bi bi-gear"></i> Configuración de pagos
        </h2>
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <form action="{{ route('payment-settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Número de cuenta*</label>
                            <input type="text" name="account_number" class="form-control" value="{{ old('account_number', $setting->account_number ?? '') }}" placeholder="Solo números (10 a 16 dígitos)">
                            @error('account_number')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Llave</label>
                            <input type="text" name="payment_key" class="form-control" value="{{ old('payment_key', $setting->payment_key ?? '') }}" placeholder="Opcional">
                            @error('payment_key')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="form-label fw-semibold">Imagen / QR</label>
                            <input type="file" name="qr_image" class="form-control">
                            @error('qr_image')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                            @if(!empty($setting->qr_image_path))
                                <div class="mt-2">
                                    <img src="{{ asset('storage/'.$setting->qr_image_path) }}" alt="QR" class="img-fluid" style="max-height:180px;">
                                </div>
                            @endif
                        </div>
                        <div class="text-end">
                            <button type="submit" class="btn btn-brown">
                                <i class="bi bi-save"></i> Guardar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h5 class="fw-bold text-brown mb-3">Vista previa para el usuario</h5>
                    <div class="border rounded p-3 bg-light mb-3">
                        <h6 class="fw-semibold">Número de cuenta</h6>
                        <p class="mb-0">{{ $setting->account_number ?? 'No definido' }}</p>
                    </div>
                    <div class="border rounded p-3 bg-light mb-3">
                        <h6 class="fw-semibold">Llave</h6>
                        <p class="mb-0">{{ $setting->payment_key ?? 'No definida' }}</p>
                    </div>
                    <div class="border rounded p-3 bg-light">
                        <h6 class="fw-semibold">Imagen / QR</h6>
                        @if(!empty($setting->qr_image_path))
                            <img src="{{ asset('storage/'.$setting->qr_image_path) }}" alt="QR" class="img-fluid" style="max-height:220px;">
                        @else
                            <p class="text-muted mb-0">Sin imagen configurada.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
