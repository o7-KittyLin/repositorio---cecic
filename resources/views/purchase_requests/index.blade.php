@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold"><i class="bi bi-bag-check"></i> Solicitudes de compra</h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('info'))
        <div class="alert alert-info">{{ session('info') }}</div>
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

    <div class="card shadow-sm">
        <div class="card-body table-responsive">
            <table class="table align-middle">
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
                            <td>{{ $req->user->name ?? 'N/D' }}</td>
                            <td>
                                @if($req->status === 'pending')
                                    <span class="badge bg-warning text-dark">Pendiente</span>
                                @elseif($req->status === 'approved')
                                    <span class="badge bg-success">Aprobada</span>
                                @else
                                    <span class="badge bg-danger">Rechazada</span>
                                @endif
                            </td>
                            <td>{{ $req->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($req->status === 'pending')
                                    <span class="text-muted">-</span>
                                @else
                                    {{ $req->reviewer->name ?? 'N/D' }}
                                @endif
                            </td>
                            <td>
                                @if($req->status === 'pending')
                                    <form action="{{ route('purchase-requests.update', $req->id) }}" method="POST" class="d-inline">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="action" value="approve">
                                        <button class="btn btn-sm btn-success">Aprobar</button>
                                    </form>
                                    <form action="{{ route('purchase-requests.update', $req->id) }}" method="POST" class="d-inline">
                                        @csrf @method('PUT')
                                        <input type="hidden" name="action" value="reject">
                                        <button class="btn btn-sm btn-danger">Rechazar</button>
                                    </form>
                                @else
                                    <small class="text-muted">Revisada</small>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">No hay solicitudes.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
            <div class="d-flex justify-content-end">
                {{ $requests->links() }}
            </div>
        </div>
    </div>
</div>
@endsection
