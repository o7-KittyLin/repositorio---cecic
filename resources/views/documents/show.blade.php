{{-- resources/views/documents/show.blade.php --}}
@extends('layouts.app')
@php use Illuminate\Support\Str; @endphp

@section('content')
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif
        <div class="row">
            <!-- Documento Principal -->
            <div class="col-lg-8">
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-brown text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">{{ $document->title }}</h4>
                        <div class="d-flex gap-2">
                            @auth
                                @if (!$document->is_free && !$isPurchased)
                                    @if($pendingRequest && $pendingRequest->status === 'pending')
                                        <span class="badge bg-warning text-dark align-self-center">Solicitud pendiente</span>
                                    @elseif($pendingRequest && $pendingRequest->status === 'rejected')
                                        <span class="badge bg-danger align-self-center">Solicitud rechazada</span>
                                    @else
                                        <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#purchaseModal">
                                            <i class="bi bi-cart-plus"></i> Comprar - ${{ number_format($document->price, 2) }}
                                        </button>
                                    @endif
                                @endif
                            @endauth

                            @if ($canViewFull)
                                <a href="{{ route('documents.download', $document->id) }}" class="btn btn-success btn-sm">
                                    <i class="bi bi-download"></i> Descargar
                                </a>
                            @endif
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- Vista previa del documento -->
                        @if (Str::endsWith($document->file_path, '.pdf'))
                            @if ($canViewFull)
                                {{-- PDF COMPLETO --}}
                                <iframe src="{{ asset('storage/' . $document->file_path) }}#toolbar=1&navpanes=0"
                                    class="w-100" style="height: 600px; border: 1px solid #ddd;">
                                </iframe>
                            @else
                                {{-- SOLO PRIMERA PÁGINA --}}
                                <div class="position-relative">
                                    <iframe
                                        src="{{ asset('storage/' . $document->file_path) }}#page=1&zoom=100&toolbar=0&navpanes=0&scrollbar=0"
                                        class="w-100" style="height: 600px; border: 1px solid #ddd;">
                                    </iframe>

                                    {{-- Overlay --}}
                                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex
                    justify-content-center align-items-center bg-dark bg-opacity-75 text-white"
                                        style="font-size: 1.2rem;">
                                        Vista previa — Compra el documento para ver completo
                                    </div>
                                </div>
                            @endif
                        @else
                            <div class="text-center py-5 bg-light rounded">
                                <i class="bi bi-file-earmark-text display-1 text-muted"></i>
                                <p class="mt-3 text-muted">Vista previa no disponible para este tipo de archivo</p>
                            </div>
                        @endif

                        <!-- Información del documento -->
                        <div class="mt-4">
                            <h5>Descripción</h5>
                            <p class="text-muted">{{ $document->description ?? 'Sin descripción' }}</p>

                            <div class="row">
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <strong>Categoría:</strong> {{ $document->category->name ?? 'Sin categoría' }}
                                    </small>
                                </div>
                                <div class="col-md-6">
                                    <small class="text-muted">
                                        <strong>Subido por:</strong> {{ $document->user->name }}
                                    </small>
                                </div>
                            </div>

                            <!-- Estadísticas -->
                            <div class="d-flex gap-4 mt-3 pt-3 border-top">
                                <div class="text-center">
                                    <span class="fw-bold text-brown">{{ $document->views_count }}</span>
                                    <div class="small text-muted">Vistas</div>
                                </div>
                                <div class="text-center">
                                    <span class="fw-bold text-brown">{{ $document->likes_count }}</span>
                                    <div class="small text-muted">Likes</div>
                                </div>
                                <div class="text-center">
                                    <span class="fw-bold text-brown">{{ $document->comments->count() }}</span>
                                    <div class="small text-muted">Comentarios</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Acciones de interacción -->
                    <div class="card-footer bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="d-flex gap-2">
                                <!-- Botón Like -->
                                <form action="{{ route('documents.like', $document->id) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-sm">
                                        <i
                                            class="bi bi-heart{{ $document->isLikedBy(auth()->user()) ? '-fill' : '' }}"></i>
                                        {{ $document->isLikedBy(auth()->user()) ? 'Quitar Like' : 'Like' }}
                                    </button>
                                </form>
                                <form action="{{ route('documents.favorite', $document->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-warning btn-sm">
                                        <i class="bi bi-star{{ $document->isFavoritedBy(auth()->user()) ? '-fill' : '' }}"></i>
                                        {{ $document->isFavoritedBy(auth()->user()) ? 'Quitar Favorito' : ' Favorito' }}
                                    </button>
                                </form>

                            </div>

                            <div class="text-muted small">
                                <i class="bi bi-eye"></i> {{ $document->views_count }} vistas
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sección de Comentarios -->
                <div class="card shadow-sm">
                    <div class="card-header bg-light">
                        <h5 class="mb-0">
                            <i class="bi bi-chat-left-text"></i> Comentarios
                            <span class="badge bg-brown ms-2">{{ $commentsCount }}</span>
                        </h5>
                    </div>

                    <div class="card-body">
                        <!-- Formulario para nuevo comentario -->
                        @auth
                            <form action="{{ route('documents.comment', $document->id) }}" method="POST" class="mb-4">
                                @csrf
                                <div class="mb-3">
                                    <textarea name="comment" class="form-control with-counter" rows="3" placeholder="Escribe tu comentario..." required maxlength="500" data-max="500"></textarea>
                                    <div class="form-text text-end"><small class="counter">0/500</small></div>
                                </div>
                                <div class="text-end">
                                    <button type="submit" class="btn btn-brown btn-sm">
                                        <i class="bi bi-send"></i> Publicar Comentario
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="alert alert-info">
                                <a href="{{ route('login') }}" class="alert-link">Inicia sesión</a> para comentar.
                            </div>
                        @endauth

                        <!-- Lista de comentarios -->
                        <div class="comments-section">
                            @forelse($comments as $comment)
                                <div class="comment-item border-bottom pb-3 mb-3">
                                    <div class="d-flex justify-content-between align-items-start">
                                        <div class="flex-grow-1">
                                            <div class="d-flex align-items-center mb-1">
                                                <strong class="me-2">{{ $comment->user->name }}</strong>
                                                <small class="text-muted">{{ $comment->created_at->diffForHumans() }}</small>
                                            </div>
                                            @php
                                                $isLongComment = Str::length($comment->comment) > 200;
                                                $shortComment = Str::limit($comment->comment, 200);
                                            @endphp
                                            <p id="comment-body-{{ $comment->id }}" class="mb-1 text-dark comment-body" data-full="{{ $comment->comment }}" data-short="{{ $shortComment }}">
                                                {{ $isLongComment ? $shortComment : $comment->comment }}
                                            </p>
                                            <div class="d-flex align-items-center gap-2">
                                                @if($isLongComment)
                                                    <button type="button" class="btn btn-sm btn-outline-info rounded-pill toggle-comment" data-target="comment-body-{{ $comment->id }}">Ver más</button>
                                                @endif
                                                <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill reply-toggle" data-comment-id="{{ $comment->id }}">Responder</button>
                                            </div>
                                        </div>

                                        @if ($comment->user_id === auth()->id() || (auth()->check() && auth()->user()->hasRole('Administrador')))
                                        <form id="deleteCommentForm-{{ $comment->id }}"
                                            action="{{ route('comments.destroy', $comment->id) }}"
                                            method="POST"
                                            class="d-inline">
                                            @csrf
                                            @method('DELETE')

                                            <button type="button"
                                                class="btn btn-sm btn-outline-danger"
                                                data-bs-toggle="modal"
                                                data-bs-target="#confirmDeleteCommentModal"
                                                data-comment-id="{{ $comment->id }}"
                                                data-comment-author="{{ $comment->user->name }}"
                                                data-is-owner="{{ $comment->user_id === auth()->id() ? '1' : '0' }}"
                                            >
                                                <i class="bi bi-trash"></i>
                                            </button>

                                        </form>
                                        @endif
                                    </div>

                                    {{-- Responder --}}
                                    <div class="reply-form mt-2" id="reply-form-{{ $comment->id }}" style="display:none;">
                                        <form action="{{ route('documents.comment', $document->id) }}" method="POST">
                                            @csrf
                                            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                                            <div class="mb-2">
                                                <textarea name="comment" class="form-control with-counter" rows="2" maxlength="500" data-max="500" placeholder="Escribe tu respuesta..."></textarea>
                                                <div class="form-text text-end"><small class="counter">0/500</small></div>
                                            </div>
                                            <div class="d-flex gap-2">
                                                <button type="submit" class="btn btn-sm btn-brown">Responder</button>
                                                <button type="button" class="btn btn-sm btn-outline-secondary cancel-reply" data-comment-id="{{ $comment->id }}">Cancelar</button>
                                            </div>
                                        </form>
                                    </div>

                                    {{-- Hijos con paginación 5 --}}
                                    @php
                                        $replies = $comment->children()->paginate(5, ['*'], 'reply_page_'.$comment->id);
                                    @endphp
                                    @if($replies->total() > 0)
                                        <button type="button" class="btn btn-sm btn-outline-secondary rounded-pill toggle-replies mt-2" data-target="replies-{{ $comment->id }}">
                                            Ver respuestas ({{ $replies->total() }})
                                        </button>
                                        <div class="mt-3 ps-3 border-start replies" id="replies-{{ $comment->id }}" style="display:none;">
                                            @foreach($replies as $child)
                                                @php
                                                    $isLongChild = Str::length($child->comment) > 200;
                                                    $shortChild = Str::limit($child->comment, 200);
                                                @endphp
                                                <div class="mb-3">
                                                    <div class="d-flex align-items-center mb-1">
                                                        <strong class="me-2">{{ $child->user->name }}</strong>
                                                        <small class="text-muted">{{ $child->created_at->diffForHumans() }}</small>
                                                    </div>
                                                    <p id="comment-body-{{ $child->id }}" class="mb-1 text-dark comment-body" data-full="{{ $child->comment }}" data-short="{{ $shortChild }}">
                                                        {{ $isLongChild ? $shortChild : $child->comment }}
                                                    </p>
                                                    @if($isLongChild)
                                                        <button type="button" class="btn btn-sm btn-outline-info rounded-pill toggle-comment" data-target="comment-body-{{ $child->id }}">Ver más</button>
                                                    @endif
                                                    @if ($child->user_id === auth()->id() || (auth()->check() && auth()->user()->hasRole('Administrador')))

                                                    <form id="deleteCommentForm-{{ $child->id }}"
                                                        action="{{ route('comments.destroy', $child->id) }}"
                                                        method="POST"
                                                        class="d-inline">
                                                        @csrf
                                                        @method('DELETE')

                                                        <button type="button"
                                                            class="btn btn-sm btn-outline-danger"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#confirmDeleteCommentModal"
                                                            data-comment-id="{{ $child->id }}"
                                                            data-comment-author="{{ $child->user->name }}"
                                                            data-is-owner="{{ $child->user_id === auth()->id() ? '1' : '0' }}"
                                                        >
                                                            <i class="bi bi-trash"></i>
                                                        </button>
                                                    </form>

                                                    @endif

                                                </div>
                                            @endforeach
                                            <div class="d-flex justify-content-end">
                                                {{ $replies->withQueryString()->links() }}
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="text-center text-muted py-4">
                                    <i class="bi bi-chat-left display-6 d-block mb-2"></i>
                                    <p>No hay comentarios aún. ¡Sé el primero en comentar!</p>
                                </div>
                            @endforelse
                        </div>
                        <div class="d-flex justify-content-end">
                            {{ $comments->links() }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sidebar con información adicional -->
            <div class="col-lg-4">
                <!-- Información de compra -->
                <div class="card shadow-sm mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0"><i class="bi bi-info-circle"></i> Información</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <strong>Tipo:</strong>
                            @if ($document->is_free)
                                <span class="badge bg-success">Gratis</span>
                            @else
                                <span class="badge bg-warning text-dark">De pago</span>
                            @endif
                        </div>

                        <div class="mb-3">
                            <strong>Precio:</strong>
                            @if ($document->is_free)
                                <span class="text-success fw-bold">Gratuito</span>
                            @else
                                <span class="text-brown fw-bold">${{ number_format($document->price, 2) }}</span>
                            @endif
                        </div>

                        <div class="mb-3">
                            <strong>Estado:</strong>
                            @if ($document->is_free || $isPurchased)
                                <span class="badge bg-success">Disponible</span>
                            @else
                                <span class="badge bg-secondary">No adquirido</span>
                            @endif
                        </div>

                        @if(!$document->is_free && !$isPurchased)
                            @if($pendingRequest && $pendingRequest->status === 'pending')
                                <div class="alert alert-warning py-2">Solicitud de compra pendiente de revision.</div>
                            @elseif($pendingRequest && $pendingRequest->status === 'rejected')
                                <div class="alert alert-danger py-2">Solicitud rechazada.</div>
                            @endif
                        @endif

                        <div class="mb-3">
                            <strong>Subido:</strong>
                            <div class="text-muted">{{ $document->created_at->format('d/m/Y') }}</div>
                        </div>

                        @if ($document->tags && count($document->tags) > 0)
                            <div class="mb-3">
                                <strong>Etiquetas:</strong>
                                <div class="mt-1">
                                    @foreach ($document->tags as $tag)
                                        <span class="badge bg-light text-dark border me-1">{{ $tag }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Documentos relacionados -->
                @if ($relatedDocuments->count() > 0)
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="bi bi-collection"></i> Documentos Relacionados</h6>
                        </div>
                        <div class="card-body">
                            @foreach ($relatedDocuments as $relatedDoc)
                                <div class="mb-2 pb-2 border-bottom">
                                    <a href="{{ route('documents.show', $relatedDoc->id) }}"
                                        class="text-decoration-none text-dark">
                                        <small class="fw-semibold">{{ Str::limit($relatedDoc->title, 40) }}</small>
                                    </a>
                                    <div class="d-flex justify-content-between mt-1">
                                        <small class="text-muted">
                                            @if ($relatedDoc->is_free)
                                                <span class="badge bg-success btn-sm">Gratis</span>
                                            @else
                                                <span
                                                    class="badge bg-warning btn-sm">${{ number_format($relatedDoc->price, 2) }}</span>
                                            @endif
                                        </small>
                                        <small class="text-muted">{{ $relatedDoc->views_count }} vistas</small>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

<!-- Modal eliminar comentario -->
    <div class="modal fade" id="confirmDeleteCommentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-danger">
                    <i class="bi bi-exclamation-triangle"></i> Eliminar comentario
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">
                <span id="deleteCommentMessage">
                    ¿Seguro que deseas eliminar este comentario?
                </span>
                <br>
                <small class="text-muted">Esta acción no se puede deshacer.</small>
            </div>

            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">
                    Cancelar
                </button>
                <button class="btn btn-danger" id="confirmDeleteCommentBtn">
                    Sí, eliminar
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal compra simulada -->
<div class="modal fade" id="purchaseModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header bg-warning text-dark">
        <h5 class="modal-title"><i class="bi bi-cart-plus"></i> Comprar documento</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        @if($paymentSetting)
            <p class="mb-2"><strong>Numero de cuenta:</strong> {{ $paymentSetting->account_number }}</p>
            @if($paymentSetting->key)
                <p class="mb-2"><strong>Llave:</strong> {{ $paymentSetting->key }}</p>
            @endif
            @if($paymentSetting->qr_path)
                <div class="mb-3">
                    <img src="{{ asset('storage/'.$paymentSetting->qr_path) }}" class="img-fluid rounded border" alt="QR">
                </div>
            @endif
        @else
            <div class="alert alert-info">Aun no hay configuracion de pago. Contacta al administrador.</div>
        @endif
        <p class="small text-muted mb-1">Selecciona “Ya realice el pago” para enviar la solicitud al administrador.</p>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
        @if($paymentSetting)
        <form action="{{ route('documents.purchase-request', $document->id) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-warning text-dark" data-submit="purchase-request">
                Ya realice el pago
            </button>
        </form>
        @endif
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {

    /* ===============================
       📈 Contador de vistas
    =============================== */
    fetch(`/documents/{{ $document->id }}/view`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json'
        }
    });


    /* ===============================
       🛒 Modal de compra – autofocus
    =============================== */
    const purchaseModal = document.getElementById('purchaseModal');
    if (purchaseModal) {
        purchaseModal.addEventListener('shown.bs.modal', function () {
            const btn = purchaseModal.querySelector('button[data-submit="purchase-request"]');
            if (btn) btn.focus();
        });
    }


    /* ===============================
       🔢 Contadores de caracteres
    =============================== */
    document.querySelectorAll('.with-counter').forEach(el => {
        const max = parseInt(el.dataset.max || el.getAttribute('maxlength'), 10);
        const counter = el.parentElement.querySelector('.counter');

        const update = () => {
            if (counter && max) {
                counter.textContent = `${el.value.length}/${max}`;
            }
        };

        el.addEventListener('input', update);
        update();
    });


    /* ===============================
       💬 Ver más / ver menos comentario
    =============================== */
    document.querySelectorAll('.toggle-comment').forEach(btn => {
        btn.addEventListener('click', () => {
            const p = document.getElementById(btn.dataset.target);
            if (!p) return;

            const full = p.dataset.full;
            const short = p.dataset.short;
            const isOpen = btn.dataset.state === 'open';

            p.textContent = isOpen ? short : full;
            btn.textContent = isOpen ? 'Ver más' : 'Ver menos';
            btn.dataset.state = isOpen ? 'closed' : 'open';
        });
    });


    /* ===============================
       ↩️ Mostrar / ocultar responder
    =============================== */
    document.querySelectorAll('.reply-toggle').forEach(btn => {
        btn.addEventListener('click', () => {
            const form = document.getElementById(`reply-form-${btn.dataset.commentId}`);
            if (form) form.style.display = 'block';
        });
    });

    document.querySelectorAll('.cancel-reply').forEach(btn => {
        btn.addEventListener('click', () => {
            const form = document.getElementById(`reply-form-${btn.dataset.commentId}`);
            if (form) form.style.display = 'none';
        });
    });


    /* ===============================
       🧵 Mostrar / ocultar respuestas
    =============================== */
    document.querySelectorAll('.toggle-replies').forEach(btn => {
        btn.addEventListener('click', () => {
            const container = document.getElementById(btn.dataset.target);
            if (!container) return;

            const isHidden = container.style.display === 'none' || container.style.display === '';
            container.style.display = isHidden ? 'block' : 'none';
            btn.textContent = isHidden ? 'Ocultar respuestas' : 'Ver respuestas';
        });
    });


    /* ===============================
    🗑️ Modal eliminar comentario
    =============================== */
    const deleteCommentModal = document.getElementById('confirmDeleteCommentModal');
    const confirmDeleteBtn = document.getElementById('confirmDeleteCommentBtn');
    const deleteMessage = document.getElementById('deleteCommentMessage');

    let currentCommentId = null;

    if (deleteCommentModal) {
        deleteCommentModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;

            currentCommentId = button.dataset.commentId;
            const author = button.dataset.commentAuthor;
            const isOwner = button.dataset.isOwner === '1';

            if (isOwner) {
                deleteMessage.textContent = '¿Seguro que deseas eliminar tu comentario?';
            } else {
                deleteMessage.textContent = `¿Seguro que deseas eliminar el comentario de ${author}?`;
            }
        });
    }

    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function () {
            if (!currentCommentId) return;

            const form = document.getElementById('deleteCommentForm-' + currentCommentId);
            if (form) form.submit();
        });
    }


});
</script>
@endpush
@endsection
