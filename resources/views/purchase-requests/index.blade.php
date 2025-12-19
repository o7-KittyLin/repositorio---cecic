@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2 class="fw-bold text-brown">
            <i class="bi bi-hourglass-split"></i> Solicitudes de compra
        </h2>
        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Documento</th>
                            <th>Usuario</th>
                            <th>Estado</th>
                            <th>Fecha</th>
                            <th>Revisado por</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    @forelse($requests as $req)
                        <tr>
                            <td>{{ $req->document->title ?? 'Documento eliminado' }}</td>
                            <td>{{ $req->user->name }}<br><small class="text-muted">{{ $req->user->email }}</small></td>
                            <td>
                                @php
                                    $badge = [
                                        'pending' => 'warning',
                                        'approved' => 'success',
                                        'rejected' => 'danger',
                                    ][$req->status] ?? 'secondary';
                                @endphp
                                <span class="badge bg-{{ $badge }}">{{ ucfirst($req->status) }}</span>
                            </td>
                            <td>{{ $req->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                {{ $req->reviewer->name ?? '-' }}
                                @if($req->reviewed_at)
                                    <br><small class="text-muted">{{ $req->reviewed_at->format('d/m/Y H:i') }}</small>
                                @endif
                            </td>
                            <td>
                                @if($req->status === 'pending')
                                    <div class="d-flex gap-2">
                                        <form method="POST" action="{{ route('purchase-requests.approve', $req) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn btn-sm btn-success">
                                                <i class="bi bi-check-circle"></i>
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('purchase-requests.reject', $req) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button class="btn btn-sm btn-danger">
                                                <i class="bi bi-x-circle"></i>
                                            </button>
                                        </form>
                                    </div>
                                @else
                                    <span class="text-muted">Procesada</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="bi bi-inbox"></i> Sin solicitudes
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="mt-3">
        {{ $requests->links() }}
    </div>
</div>
@endsection
