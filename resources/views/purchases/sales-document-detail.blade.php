@extends('layouts.app')

@section('content')

<h3 class="fw-bold text-brown mb-4">
    <i class="bi bi-people"></i> Compradores del Documento
</h3>

<div class="mb-3">
    <h5 class="fw-bold">{{ $document->title }}</h5>
    <p class="text-muted">
        Precio:
        @if ($document->is_free)
            <span class="badge bg-success">Gratis</span>
        @else
            ${{ number_format($document->price, 2) }}
        @endif
    </p>
</div>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Usuario</th>
                    <th>Correo</th>
                    <th>Monto</th>
                    <th>Fecha</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($sales as $s)
                <tr>
                    <td>{{ $s->user->name }}</td>
                    <td>{{ $s->user->email }}</td>
                    <td>${{ number_format($s->amount, 2) }}</td>
                    <td>{{ $s->created_at->format('d/m/Y H:i') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="text-center text-muted p-4">
                        <i class="bi bi-inbox fs-3 d-block"></i>
                        No hay compradores registrados.
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>
</div>

<div class="mt-3">
    <a href="{{ route('sales.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Volver
    </a>
</div>

@endsection
