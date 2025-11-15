@extends('layouts.app')

@section('content')
<div class="container">

    <h3 class="fw-bold text-brown mb-4"><i class="bi bi-person-plus"></i> Crear Usuario</h3>

    <div class="card p-4 shadow-sm">

        <form method="POST" action="{{ route('users.store') }}">
            @csrf

            <div class="row g-3">

                <div class="col-md-6">
                    <label class="form-label">Nombre</label>
                    <input name="name" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input name="email" type="email" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Contraseña</label>
                    <input name="password" type="password" class="form-control" required>
                </div>

                <div class="col-md-6">
                    <label class="form-label">Rol</label>
                    <select name="role" class="form-select" required>
                        <option value="">Seleccione...</option>
                        @foreach ($roles as $r)
                        <option value="{{ $r->name }}">{{ $r->name }}</option>
                        @endforeach
                    </select>
                </div>

            </div>

            <div class="text-end mt-4">
                <button class="btn btn-brown"><i class="bi bi-save"></i> Crear</button>
            </div>

        </form>

    </div>

</div>
@endsection
