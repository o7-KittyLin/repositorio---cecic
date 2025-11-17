@extends('layouts.app')

@section('content')
<div class="container">

    <h3 class="fw-bold text-brown mb-4"><i class="bi bi-pencil"></i> Editar Usuario</h3>

    <div class="card p-4 shadow-sm">

        <form method="POST" action="{{ route('users.update', $user->id) }}">
            @csrf
            @method('PUT')

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Nombre*</label>
                    <input name="name" class="form-control"
                        value="{{ $user->name }}">
                    @error('name')
                    <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email*</label>
                    <input name="email" type="email" class="form-control"
                        value="{{ $user->email }}">
                    @error('email')
                    <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Nueva contraseña (opcional)</label>
                    <input name="password" type="password" class="form-control">
                    @error('password')
                    <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">Rol*</label>
                    <select name="role" class="form-select">
                        @foreach ($roles as $r)
                        <option value="{{ $r->name }}" {{ $user->roles->contains('name', $r->name) ? 'selected' : '' }}>
                            {{ $r->name }}
                        </option>
                        @endforeach
                    </select>
                    @error('role')
                    <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

            </div>

            <div class="text-end mt-4">
                <button class="btn btn-brown"><i class="bi bi-save"></i> Guardar cambios</button>
            </div>

        </form>

    </div>

</div>
@endsection
