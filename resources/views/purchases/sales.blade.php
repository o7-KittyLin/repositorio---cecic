@extends('layouts.app')

@section('content')

<h3 class="fw-bold text-brown mb-4">
    <i class="bi bi-cash-coin"></i> Registro de Ventas
</h3>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Documento</th>
                    <th>Usuario</th>
                    <th>Monto</th>
                    <th>Estado</th>
                    <th>Fecha</th>
                    <th>Ventas totales</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($sales as $s)
                <tr>
                    <td>
                        {{ $s->document->title }}<br>
                        <a href="{{ route('sales.documentDetail', $s->document->id) }}"
                           class="btn btn-sm btn-outline-dark mt-1">
                            <i class="bi bi-people"></i> Ver compradores
                        </a>
                    </td>

                    <td>{{ $s->user->name }} <br>
                        <small class="text-muted">{{ $s->user->email }}</small>
                    </td>

                    <td>${{ number_format($s->amount, 2) }}</td>

                    <td>
                        <span class="badge bg-success">Pagado</span>
                    </td>

                    <td>{{ $s->created_at->format('d/m/Y H:i') }}</td>

                    <td>
                        <span class="badge bg-primary">
                            {{ $s->document->purchases_count }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>

    <div class="mt-3 px-3">
        {{ $sales->links() }}
    </div>

</div>

@endsection
