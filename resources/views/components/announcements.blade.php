{{-- resources/views/components/announcements.blade.php --}}
@php
    use App\Models\Announcement;
    $activeAnnouncements = Cache::remember('active_announcements', 300, function () {
        return Announcement::active()->get();
    });
@endphp

@if($activeAnnouncements->count() > 0)
    <div class="card shadow-sm mb-4 border-warning">
        <div class="card-header bg-warning text-dark">
            <h5 class="mb-0">
                <i class="bi bi-megaphone"></i> Anuncios Activos
                <span class="badge bg-brown ms-2">{{ $activeAnnouncements->count() }}</span>
            </h5>
        </div>
        <div class="card-body">
            @foreach($activeAnnouncements as $announcement)
                <div class="announcement-item mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <h6 class="fw-bold text-brown mb-1">{{ $announcement->title }}</h6>
                            @if($announcement->description)
                                <p class="mb-2 text-muted small">{{ $announcement->description }}</p>
                            @endif
                            <div class="d-flex gap-3 text-muted small">
                                <span>
                                    <i class="bi bi-clock"></i> 
                                    {{ $announcement->start_time->format('H:i') }} - {{ $announcement->end_time->format('H:i') }}
                                </span>
                                @if($announcement->link)
                                    <a href="{{ $announcement->link }}" target="_blank" class="text-primary text-decoration-none">
                                        <i class="bi bi-link-45deg"></i> Unirse a la reunión
                                    </a>
                                @endif
                            </div>
                        </div>
                        <span class="badge bg-success ms-2">En Curso</span>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif