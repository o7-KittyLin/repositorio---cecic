@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h3 class="fw-bold mb-4"><i class="bi bi-gear"></i> Configuracion de pagos</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-7">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <strong>Datos para pago simulado</strong>
                </div>
                <div class="card-body">
                    <form action="{{ route('payment-settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Numero de cuenta (solo numeros, 10-16 digitos)</label>
                            <input type="text"
                                   name="account_number"
                                   class="form-control"
                                   inputmode="numeric"
                                   maxlength="16"
                                   pattern="^[0-9]{10,16}$"
                                   value="{{ old('account_number', $setting->account_number ?? '') }}">
                            <div id="account_number_error" class="text-danger small"></div>
                            @error('account_number')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Sin espacios ni guiones. Ej: 123456789012</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Llave (opcional)</label>
                            <input type="text" name="key" class="form-control"
                                   value="{{ old('key', $setting->key ?? '') }}">
                            @error('key')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Imagen QR / Comprobante (opcional, max 2MB)</label>
                            <input type="file" name="qr" class="form-control" accept="image/*">
                            <div id="qr_error" class="text-danger small"></div>
                            @error('qr')
                                <div class="text-danger small">{{ $message }}</div>
                            @enderror
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

        <div class="col-lg-5">
            <div class="card shadow-sm">
                <div class="card-header bg-light">
                    <strong>Vista previa para usuarios</strong>
                </div>
                <div class="card-body">
                    @if($setting)
                        <p class="mb-1"><strong>Numero de cuenta:</strong> {{ $setting->account_number }}</p>
                        @if($setting->key)
                            <p class="mb-1"><strong>Llave:</strong> {{ $setting->key }}</p>
                        @endif
                        @if($setting->qr_path)
                            <div class="mt-3">
                                <img src="{{ asset('storage/'.$setting->qr_path) }}" alt="QR" class="img-fluid rounded border">
                            </div>
                        @endif
                    @else
                        <p class="text-muted">Aun no hay configuracion de pagos.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const accountInput = document.querySelector('input[name="account_number"]');
    const accountError = document.getElementById('account_number_error');
    const regex = /^[0-9]{10,16}$/;
    const qrInput = document.querySelector('input[name="qr"]');
    const qrError = document.getElementById('qr_error');

    function validateAccount() {
        if (!accountInput) return;
        const val = (accountInput.value || '').trim();
        if (val === '') {
            accountError.textContent = '';
            return true;
        }
        if (!regex.test(val)) {
            accountError.textContent = 'Solo numeros, 10 a 16 digitos.';
            return false;
        }
        accountError.textContent = '';
        return true;
    }

    if (accountInput) {
        accountInput.addEventListener('input', validateAccount);
    }

    if (qrInput) {
        qrInput.addEventListener('change', function() {
            if (!qrError) return;
            qrError.textContent = '';
            const file = qrInput.files && qrInput.files[0];
            if (!file) return;
            const maxBytes = 2 * 1024 * 1024; // 2MB
            if (file.size > maxBytes) {
                qrError.textContent = 'La imagen no puede superar 2MB.';
                qrInput.value = '';
            }
        });
    }
});
</script>
@endpush
