{{-- resources/views/multimedia.blade.php --}}
@extends('layouts.app')
@php use Illuminate\Support\Str; @endphp

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold text-brown mb-0">
            <i class="bi bi-megaphone"></i> Anuncios y Multimedia
        </h2>
    </div>

    {{-- Reuniones --}}
    <div class="mb-4">
        <h4 class="fw-semibold text-brown mb-3"><i class="bi bi-people"></i> Reuniones</h4>
        <div class="row g-3">
            @forelse($reuniones as $item)
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-warning">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                @if($item->isScheduled())
                                    <span class="badge bg-warning text-dark">Programado</span>
                                @elseif($item->isActive())
                                    <span class="badge bg-success">En curso</span>
                                @else
                                    <span class="badge bg-secondary">Inactivo</span>
                                @endif
                                <span class="text-muted small">
                                    <i class="bi bi-calendar-event"></i>
                                    {{ $item->start_time->format('d/m/Y H:i') }}
                                </span>
                            </div>
                            <h5 class="card-title">{{ $item->title }}</h5>
                            @if($item->description)
                                @php
                                    $isLong = Str::length($item->description) > 140;
                                    $shortDesc = Str::limit($item->description, 140);
                                @endphp
                                <p class="card-text text-muted mb-2 multimedia-desc" data-full="{{ $item->description }}" data-short="{{ $shortDesc }}">
                                    {{ $isLong ? $shortDesc : $item->description }}
                                </p>
                                @if($isLong)
                                    <button type="button" class="btn btn-sm btn-outline-info rounded-pill toggle-desc">Ver más</button>
                                @endif
                            @endif
                            @if($item->link)
                                @if($item->isActive())
                                    <a href="{{ $item->link }}" target="_blank" class="mt-auto btn btn-outline-primary">
                                        <i class="bi bi-link-45deg"></i> Abrir enlace
                                    </a>
                                @else
                                    <button class="mt-auto btn btn-outline-secondary" disabled>
                                        <i class="bi bi-lock"></i> Disponible al iniciar
                                    </button>
                                @endif
                            @endif
                            @auth
                                @role('Administrador')
                                    <div class="mt-2">
                                        <a href="{{ route('announcements.edit', $item->id) }}" class="btn btn-sm btn-outline-warning">
                                            <i class="bi bi-pencil"></i> Editar
                                        </a>
                                        <form action="{{ route('announcements.destroy', $item->id) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endrole
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="bi bi-inbox"></i> Aún no se ha publicado nada.
                    </div>
                </div>
            @endforelse
        </div>
        <div class="mt-3 d-flex justify-content-end">
            {{ $reuniones->appends(['multimedia_page' => request('multimedia_page')])->links() }}
        </div>
    </div>

    {{-- Multimedia --}}
    <div>
        <h4 class="fw-semibold text-brown mb-3"><i class="bi bi-collection-play"></i> Multimedia</h4>
        <div class="row g-3">
            @forelse($multimedia as $item)
                <div class="col-md-4">
                    <div class="card h-100 shadow-sm border-0" style="overflow:hidden;">
                        <div class="card-body d-flex flex-column" style="background: linear-gradient(145deg, #f9fcff, #eef5ff); border: 1px solid #d5e5ff; border-radius: 12px;">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="badge bg-info text-dark">Video</span>
                                <small class="text-muted">{{ $item->created_at?->format('d/m/Y') }}</small>
                            </div>
                            <h5 class="card-title text-brown">{{ $item->title }}</h5>
                            @if($item->description)
                                @php
                                    $isLong = Str::length($item->description) > 140;
                                    $shortDesc = Str::limit($item->description, 140);
                                @endphp
                                <p class="card-text text-muted multimedia-desc" data-full="{{ $item->description }}" data-short="{{ $shortDesc }}">
                                    {{ $isLong ? $shortDesc : $item->description }}
                                </p>
                                @if($isLong)
                                    <div class="mb-2">
                                        <button type="button" class="btn btn-sm btn-outline-info rounded-pill toggle-desc">Ver más</button>
                                    </div>
                                @endif
                            @endif
                            @if($item->link)
                                <a href="{{ $item->link }}" target="_blank" class="mt-auto btn btn-outline-primary w-100">
                                    <i class="bi bi-play-circle"></i> Ver video
                                </a>
                            @endif
                            @auth
                                @role('Administrador')
                                    <div class="mt-2">
                                        <a href="{{ route('announcements.edit', $item->id) }}" class="btn btn-sm btn-outline-warning">
                                            <i class="bi bi-pencil"></i> Editar
                                        </a>
                                        <form action="{{ route('announcements.destroy', $item->id) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endrole
                            @endauth
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <i class="bi bi-inbox"></i> Aún no se ha publicado nada.
                    </div>
                </div>
            @endforelse
        </div>
        <div class="mt-3 d-flex justify-content-end">
            {{ $multimedia->appends(['reuniones_page' => request('reuniones_page')])->links() }}
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.multimedia-desc').forEach(p => {
        const full = p.getAttribute('data-full');
        const short = p.getAttribute('data-short');
        const isLong = (full && short && full.length > short.length);
        if (!isLong) return;
        // Ya hay un botón insertado en Blade; aseguramos estado inicial
        p.textContent = short;
    });

    document.querySelectorAll('.toggle-desc').forEach(btn => {
        btn.addEventListener('click', () => {
            const container = btn.closest('.card-body');
            const p = container ? container.querySelector('.multimedia-desc') : null;
            if (!p) return;
            const full = p.getAttribute('data-full');
            const short = p.getAttribute('data-short');
            const isExpanded = btn.dataset.state === 'open';
            p.textContent = isExpanded ? short : full;
            btn.textContent = isExpanded ? 'Ver más' : 'Ver menos';
            btn.dataset.state = isExpanded ? 'closed' : 'open';
        });
    });
});
</script>
@endpush
