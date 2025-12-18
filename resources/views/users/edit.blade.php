@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold text-brown mb-0"><i class="bi bi-person-lines-fill"></i> Editar usuario</h3>
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
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

                <div class="col-md-6">
                    <label class="form-label">Rol*</label>
                    <select name="role" class="form-select">
                        @foreach($roles as $r)
                            <option value="{{ $r->name }}" {{ $user->roles->contains('name', $r->name) ? 'selected' : '' }}>{{ $r->name }}</option>
                        @endforeach
                    </select>
                    @error('role') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="text-end mt-4">
                <button class="btn btn-brown"><i class="bi bi-save"></i> Guardar cambios</button>
            </div>
        </form>
    </div>
</div>
@endsection
