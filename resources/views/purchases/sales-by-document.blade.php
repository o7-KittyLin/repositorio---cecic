@extends('layouts.app')

@section('content')
<h3 class="fw-bold text-brown mb-4">
    <i class="bi bi-book"></i> Ventas por Documento
</h3>

<div class="card shadow-sm border-0">
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Documento</th>
                    <th>Categoría</th>
                    <th>Precio</th>
                    <th>Ventas</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($documents as $doc)
                <tr>
                    <td>{{ $doc->title }}</td>
                    <td>{{ $doc->category->name ?? 'General' }}</td>
                    <td>
                        @if ($doc->is_free)
                            <span class="badge bg-success">Gratis</span>
                        @else
                            ${{ number_format($doc->price, 2) }}
                        @endif
                    </td>
                    <td>
                        <span class="badge bg-primary">{{ $doc->purchases_count }}</span>
                    </td>
                    <td>
                        <a href="{{ route('sales.documentDetail', $doc->id) }}"
                            class="btn btn-sm btn-outline-dark">
                            <i class="bi bi-people"></i> Ver compradores
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>

    <div class="mt-3 px-3">
        {{ $documents->links() }}
    </div>
</div>
@endsection
