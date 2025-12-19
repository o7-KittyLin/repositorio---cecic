@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold text-brown mb-0">
            <i class="bi bi-person-lines-fill"></i>
            {{ $isAdmin ? 'Editar usuario' : 'Mi perfil' }}
        </h3>
        @if($isAdmin)
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i> Volver
            </a>
        @endif
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card p-4 shadow-sm">
        <form method="POST" action="{{ route('users.update', $user->id) }}">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nombre*</label>
                    <input name="name" class="form-control" value="{{ old('name', $user->name) }}">
                    @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email*</label>
                    <input name="email" type="email" class="form-control" value="{{ old('email', $user->email) }}">
                    @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nueva contraseña (opcional)</label>
                    <input name="password" type="password" class="form-control" placeholder="Deja en blanco para no cambiar">
                    @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>

                @if($isAdmin)
                    <div class="col-md-6">
                        <label class="form-label">Rol*</label>
                        <select name="role" class="form-select">
                            @foreach($roles as $r)
                                <option value="{{ $r->name }}" {{ $user->roles->contains('name', $r->name) ? 'selected' : '' }}>{{ $r->name }}</option>
                            @endforeach
                        </select>
                        @error('role') <div class="text-danger small">{{ $message }}</div> @enderror
                    </div>
                @endif
            </div>

            <div class="text-end mt-4">
                <button class="btn btn-brown"><i class="bi bi-save"></i> Guardar cambios</button>
            </div>
        </form>
    </div>

    @if(!$isAdmin)
    <div class="card p-4 shadow-sm mt-4">
        <h5 class="fw-bold text-danger mb-2"><i class="bi bi-exclamation-triangle"></i> Eliminar cuenta</h5>
        @if($pendingDeletion)
            <p class="mb-3 text-muted">
                Eliminación programada para <strong>{{ $pendingDeletion->scheduled_for->format('d/m/Y') }}</strong>. Puedes recuperarla antes de esa fecha.
            </p>
            <form method="POST" action="{{ route('account-deletion.recover') }}">
                @csrf
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-arrow-counterclockwise"></i> Recuperar cuenta
                </button>
            </form>
        @else
            <p class="mb-3 text-muted">Tu cuenta se eliminará en 3 días hábiles tras la solicitud. Ingresa tu contraseña para confirmar.</p>
            <form method="POST" action="{{ route('account-deletion.request') }}" class="row g-2 align-items-end">
                @csrf
                <div class="col-md-6">
                    <label class="form-label">Contraseña actual</label>
                    <input type="password" name="password" class="form-control" required>
                    @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-danger mt-2 mt-md-0">
                        <i class="bi bi-trash"></i> Solicitar eliminación
                    </button>
                </div>
            </form>
        @endif
    </div>
    @endif
</div>
@endsection
