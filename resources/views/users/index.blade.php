@extends('layouts.app')

@section('content')
<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold text-brown"><i class="bi bi-people"></i> Gestión de Usuarios</h3>
        <a href="{{ route('users.create') }}" class="btn btn-brown">
            <i class="bi bi-person-plus"></i> Nuevo usuario
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card shadow-sm border-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>Nombre</th>
                        <th>Email</th>
                        <th>Rol</th>
                        <th width="150px">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $u)
                    <tr>
                        <td>{{ $u->name }}</td>
                        <td>{{ $u->email }}</td>
                        <td>{{ $u->roles->pluck('name')->implode(', ') }}</td>
                        <td>
                            <a href="{{ route('users.edit', $u->id) }}" class="btn btn-warning btn-sm">
                                <i class="bi bi-pencil"></i>
                            </a>

                            <form id="deleteUserForm-{{ $u->id }}"
                                action="{{ route('users.destroy', $u->id) }}"
                                method="POST"
                                class="d-inline">
                                @csrf
                                @method('DELETE')

                                <button type="button"
                                        class="btn btn-danger btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#confirmDeleteUserModal"
                                        data-user-id="{{ $u->id }}"
                                        data-user-name="{{ $u->name }}">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>

                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">{{ $users->links() }}</div>

</div>

<!-- Modal Confirmar Eliminación de Usuario -->
<div class="modal fade" id="confirmDeleteUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      
      <div class="modal-header bg-danger text-white">
        <h5 class="modal-title">
            <i class="bi bi-person-x"></i> Eliminar usuario
        </h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>

      <div class="modal-body">
        <p class="mb-2">
            ¿Seguro que deseas eliminar al usuario
            <strong id="userNameToDelete">seleccionado</strong>?
        </p>

        <p class="text-muted mb-0">
            Esta acción no se puede deshacer.
        </p>
      </div>

      <div class="modal-footer">
        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>

        <button class="btn btn-danger" id="confirmDeleteUserBtn">
            <i class="bi bi-trash"></i> Eliminar
        </button>
      </div>

    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    let currentUserId = null;
    const modalEl = document.getElementById('confirmDeleteUserModal');
    const confirmBtn = document.getElementById('confirmDeleteUserBtn');

    if (modalEl) {
        modalEl.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const userId = button.getAttribute('data-user-id');
            const userName = button.getAttribute('data-user-name');

            currentUserId = userId;

            const nameSpan = modalEl.querySelector('#userNameToDelete');
            if (nameSpan) {
                nameSpan.textContent = userName || 'este usuario';
            }
        });
    }

    if (confirmBtn) {
        confirmBtn.addEventListener('click', function () {
            if (!currentUserId) return;

            const form = document.getElementById('deleteUserForm-' + currentUserId);
            if (form) {
                form.submit();
            }
        });
    }

});
</script>
@endpush

@endsection
