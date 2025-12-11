{{-- resources/views/components/announcements.blade.php --}}
@php
    use App\Models\Announcement;
    $activeReuniones = Cache::remember('active_announcements_reunion', 300, function () {
        return Announcement::visible()->where('type', 'reunion')->orderBy('start_time', 'desc')->get();
    });
    $activeMultimedia = Cache::remember('active_announcements_multimedia', 300, function () {
        return Announcement::visible()->where('type', 'multimedia')->orderBy('start_time', 'desc')->get();
    });
@endphp

<div class="card shadow-sm mb-4 border-warning">
    <div class="card-header bg-warning text-dark">
        <h5 class="mb-0">
            <i class="bi bi-megaphone"></i> Reuniones
            <span class="badge bg-brown ms-2">{{ $activeReuniones->count() }}</span>
        </h5>
    </div>
    <div class="card-body">
        @forelse($activeReuniones as $announcement)
            <div class="announcement-item mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <h6 class="fw-bold text-brown mb-1">{{ $announcement->title }}</h6>
                        @if($announcement->description)
                            <p class="mb-2 text-muted small">{{ $announcement->description }}</p>
                        @endif
                        <div class="d-flex flex-wrap gap-3 text-muted small">
                            <span>
                                <i class="bi bi-clock"></i>
                                {{ $announcement->start_time->format('d/m H:i') }} - {{ $announcement->end_time->format('H:i') }}
                            </span>
                            @if($announcement->link)
                                @if($announcement->isActive())
                                    <a href="{{ $announcement->link }}" target="_blank" class="text-primary text-decoration-none">
                                        <i class="bi bi-link-45deg"></i> Unirse
                                    </a>
                                @else
                                    <span class="text-muted"><i class="bi bi-link-45deg"></i> Disponible al iniciar</span>
                                @endif
                            @endif
                        </div>
                    </div>
                    @if($announcement->isScheduled())
                        <span class="badge bg-warning text-dark ms-2">Programado</span>
                    @elseif($announcement->isActive())
                        <span class="badge bg-success ms-2">En curso</span>
                    @else
                        <span class="badge bg-secondary ms-2">Inactivo</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-3">
                <i class="bi bi-inbox me-1"></i> Aún no se ha publicado nada.
            </div>
        @endforelse
    </div>
</div>

<div class="card shadow-sm mb-4 border-info">
    <div class="card-header bg-info text-dark">
        <h5 class="mb-0">
            <i class="bi bi-collection-play"></i> Multimedia
            <span class="badge bg-brown ms-2">{{ $activeMultimedia->count() }}</span>
        </h5>
    </div>
    <div class="card-body">
        @forelse($activeMultimedia as $announcement)
            <div class="announcement-item mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                <div class="d-flex justify-content-between align-items-start">
                    <div class="flex-grow-1">
                        <h6 class="fw-bold text-brown mb-1">{{ $announcement->title }}</h6>
                        @if($announcement->description)
                            <p class="mb-2 text-muted small">{{ $announcement->description }}</p>
                        @endif
                        @if($announcement->link)
                            <a href="{{ $announcement->link }}" target="_blank" class="text-primary text-decoration-none">
                                <i class="bi bi-play-circle"></i> Ver video
                            </a>
                        @endif
                    </div>
                    <span class="badge bg-info text-dark ms-2">Publicado</span>
                </div>
            </div>
        @empty
            <div class="text-center text-muted py-3">
                <i class="bi bi-inbox me-1"></i> Aún no se ha publicado nada.
            </div>
        @endforelse
    </div>
</div>
