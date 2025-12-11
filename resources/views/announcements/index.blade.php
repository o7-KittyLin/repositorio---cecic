{{-- resources/views/announcements/index.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-brown">
            <i class="bi bi-megaphone"></i> Gestión de Anuncios
        </h2>
        <a href="{{ route('announcements.create') }}" class="btn btn-brown">
            <i class="bi bi-plus-circle"></i> Nuevo Anuncio
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Título</th>
                            <th>Tipo</th>
                            <th>Inicio</th>
                            <th>Fin</th>
                            <th>Estado</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($announcements as $announcement)
                            <tr>
                                <td class="fw-semibold">{{ $announcement->title }}</td>
                                <td>
                                    <span class="badge {{ $announcement->type === 'multimedia' ? 'bg-info text-dark' : 'bg-primary' }}">
                                        {{ $announcement->type === 'multimedia' ? 'Multimedia' : 'Reunión' }}
                                    </span>
                                </td>
                                <td>{{ $announcement->start_time->format('d/m/Y H:i') }}</td>
                                <td>{{ $announcement->end_time->format('d/m/Y H:i') }}</td>
                                <td>
                                    @if($announcement->status == 'cancelled')
                                        <span class="badge bg-danger">Cancelado</span>
                                    @elseif($announcement->status == 'inactive' || $announcement->end_time < now())
                                        <span class="badge bg-secondary">Inactivo</span>
                                    @elseif($announcement->start_time > now())
                                        <span class="badge bg-warning text-dark">Programado</span>
                                    @else
                                        <span class="badge bg-success">En curso</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('announcements.edit', $announcement->id) }}" 
                                       class="btn btn-sm btn-outline-warning">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form id="deleteAnnouncementForm-{{ $announcement->id }}"
                                        action="{{ route('announcements.destroy', $announcement->id) }}" 
                                        method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')

                                        <button type="button"
                                                class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#confirmDeleteAnnouncementModal"
                                                data-announcement-id="{{ $announcement->id }}"
                                                data-announcement-title="{{ $announcement->title }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    <i class="bi bi-megaphone display-6 d-block mb-2"></i>
                                    No hay anuncios registrados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-end mt-3">
                {{ $announcements->links() }}
            </div>
        </div>
    </div>
</div>

<!-- Modal Confirmar Eliminación de Anuncio -->
<div class="modal fade" id="confirmDeleteAnnouncementModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">
            <i class="bi bi-exclamation-triangle"></i> Eliminar anuncio
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <p class="mb-2">
            ¿Seguro que deseas eliminar el anuncio
            <strong id="announcementTitleToDelete">seleccionado</strong>?
        </p>
        <p class="text-muted mb-0">
            Esta acción no se puede deshacer.
        </p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
        <button class="btn btn-danger" id="confirmDeleteAnnouncementBtn">
            <i class="bi bi-trash"></i> Eliminar
        </button>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const modalEl = document.getElementById('confirmDeleteAnnouncementModal');
    const confirmBtn = document.getElementById('confirmDeleteAnnouncementBtn');
    let currentAnnouncementId = null;

    if (modalEl) {
        modalEl.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const announcementId = button.getAttribute('data-announcement-id');
            const announcementTitle = button.getAttribute('data-announcement-title');

            currentAnnouncementId = announcementId;

            const titleSpan = modalEl.querySelector('#announcementTitleToDelete');
            if (titleSpan) {
                titleSpan.textContent = announcementTitle || 'este anuncio';
            }
        });
    }

    if (confirmBtn) {
        confirmBtn.addEventListener('click', function () {
            if (!currentAnnouncementId) return;

            const form = document.getElementById('deleteAnnouncementForm-' + currentAnnouncementId);
            if (form) {
                form.submit();
            }
        });
    }
});
</script>
@endpush


@endsection
