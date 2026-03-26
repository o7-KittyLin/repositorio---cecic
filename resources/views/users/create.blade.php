@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="fw-bold text-brown mb-0"><i class="bi bi-person-plus"></i> Crear empleado / admin</h3>
        <a href="{{ route('users.index') }}" class="btn btn-outline-secondary btn-sm">
            <i class="bi bi-arrow-left"></i> Volver
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card p-4 shadow-sm">
        <form method="POST" action="{{ route('users.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nombre*</label>
                    <input name="name" class="form-control" value="{{ old('name') }}">
                    @error('name') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email*</label>
                    <input name="email" type="email" class="form-control" value="{{ old('email') }}">
                    @error('email') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Contraseña*</label>
                    <input name="password" type="password" class="form-control">
                    @error('password') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Rol*</label>
                    <select name="role" class="form-select" required>
                        <option value="" disabled {{ old('role') ? '' : 'selected' }}>Seleccione rol</option>
                        @foreach($roles as $r)
                            <option value="{{ $r->name }}" {{ old('role') == $r->name ? 'selected' : '' }}>{{ $r->name }}</option>
                        @endforeach
                    </select>
                    @error('role') <div class="text-danger small">{{ $message }}</div> @enderror
                </div>
            </div>
            <div class="text-end mt-4">
                <button class="btn btn-brown"><i class="bi bi-check-circle"></i> Crear</button>
            </div>
        </form>
    </div>
</div>
@endsection
