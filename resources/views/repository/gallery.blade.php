@extends('layouts.app')
@section('content')
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }

        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 12px;
            overflow: hidden;
            cursor: pointer;
        }

        .card:hover {
            transform: translateY(-8px) scale(1.02);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.18);
        }

        .card-body {
            background: #fff;
            padding: 1rem;
            display: flex;
            flex-direction: column;
        }

        .preview-frame {
            height: 220px;
            width: 100%;
            border: none;
            background-color: #f0f0f0;
            object-fit: cover;
            border-radius: 12px 12px 0 0;
        }

        .btn-brown {
            background: #8B5E3C;
            color: #fff;
            transition: background 0.3s;
        }

        .btn-brown:hover {
            background: #6f452b;
        }

        .text-brown {
            color: #8B5E3C;
        }
    </style>

    <div class="container py-5">

        <!-- Encabezado -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-3">
                <h2 class="fw-bold text-brown mb-0">
                    <i class="bi bi-collection"></i> Galeria del Repositorio
                </h2>
            </div>

            <div class="d-flex gap-2 align-items-center">
                <form action="{{ route('repository.gallery') }}" method="GET" class="d-flex gap-2 align-items-center">
                    <select name="category_id" class="form-select" onchange="this.form.submit()" style="min-width: 200px;">
                        <option value="">Todas las categorias</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                    @if (request('category_id'))
                        <a href="{{ route('repository.gallery') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-x"></i>
                        </a>
                    @endif
                </form>

                @auth
                    @unless (Auth::user()->hasRole('Administrador'))
                    <a href="{{ route('purchases.my') }}" class="btn btn-outline-success">
                        <i class="bi bi-bag-check"></i> Mis Compras
                    </a>
                    @endunless
                @endauth
            </div>
        </div>

        <!-- Mensajes -->
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif
        @if (session('info'))
            <div class="alert alert-info">{{ session('info') }}</div>
        @endif

        <!-- Tarjetas -->
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            @forelse($documents as $doc)
                @php
                    $isPurchased = auth()->check() ? $doc->isPurchasedBy(auth()->user()) : false;
                    $canDownload = $doc->is_free || $isPurchased || (auth()->check() && auth()->user()->hasRole('Administrador'));
                @endphp

                <div class="col">
                    <a href="{{ route('documents.show', $doc->id) }}" class="text-decoration-none text-dark">
                        <div class="card h-100 shadow-sm border-0 rounded-3 overflow-hidden">
                            <div class="position-relative preview-overlay-container" style="height:180px; border-radius: .5rem .5rem 0 0; overflow:hidden;">
                                @if (Str::endsWith($doc->file_path, '.pdf'))
                                    <iframe src="{{ asset('storage/' . $doc->file_path) }}#page=1&toolbar=0&navpanes=0&scrollbar=0"
                                            class="w-100 h-100 preview-frame" style="border:none; pointer-events: none;"></iframe>
                                @else
                                    <div class="d-flex align-items-center justify-content-center bg-light preview-frame text-muted h-100 w-100">
                                        <i class="bi bi-file-earmark-text fs-1"></i>
                                    </div>
                                @endif
                                <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center bg-dark bg-opacity-25 text-white">
                                    <small>Vista previa protegida</small>
                                </div>
                            </div>

                            <div class="card-body d-flex flex-column">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h6 class="card-title fw-bold text-brown mb-0">{{ Str::limit($doc->title, 40) }}</h6>
                                    @if ($doc->is_free)
                                        <span class="badge bg-success">Gratis</span>
                                    @elseif($isPurchased)
                                        <span class="badge bg-primary">Adquirido</span>
                                    @else
                                        <span class="badge bg-danger">${{ number_format($doc->price, 2) }}</span>
                                    @endif
                                </div>

                                @if($doc->category)
                                    <small class="text-muted d-block mb-1">{{ $doc->category->name }}</small>
                                @endif
                                @hasanyrole('Administrador|Empleado')
                                    <small class="text-muted d-block mb-2">Creado por: {{ $doc->user->name ?? 'N/D' }}</small>
                                @endhasanyrole

                                <p class="card-text text-secondary flex-grow-1 mb-3">
                                    {{ Str::limit($doc->description, 100) }}
                                </p>

                                <div class="d-flex justify-content-end align-items-center mb-2">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('documents.show', $doc->id) }}" class="me-2 btn btn-sm btn-outline-primary" title="Ver detalles" onclick="event.stopPropagation();">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        @if ($canDownload)
                                            <a href="{{ route('repository.download', $doc->id) }}" class="btn me-2 btn-sm btn-outline-success" title="Descargar" onclick="event.stopPropagation();">
                                                <i class="bi bi-download"></i>
                                            </a>
                                        @elseif(!$doc->is_free && auth()->check())
                                            <button type="button"
                                                    class="btn btn-sm btn-warning me-2 purchase-btn"
                                                    title="Solicitar compra"
                                                    data-doc-id="{{ $doc->id }}"
                                                    data-doc-title="{{ Str::limit($doc->title, 60) }}"
                                                    onclick="event.stopPropagation();">
                                                <i class="bi bi-cart-plus"></i>
                                            </button>
                                        @elseif(!$doc->is_free && !auth()->check())
                                            <a href="{{ route('login') }}?redirect=purchase&doc={{ $doc->id }}" class="btn btn-sm btn-warning" onclick="event.stopPropagation();">
                                                <i class="bi bi-cart-plus"></i>
                                            </a>
                                        @endif

                                        @hasrole('Administrador')
                                        <a href="{{ route('repository.edit', $doc->id) }}" class="btn btn-sm btn-outline-warning" onclick="event.stopPropagation();">
                                            <i class="bi bi-pencil"></i>
                                        </a>
                                        @endhasrole
                                    </div>
                                </div>

                                <div class="d-flex justify-content-between mt-3 small text-muted">
                                    <span><i class="bi bi-eye"></i> {{ $doc->views_count }}</span>
                                    <span><i class="bi bi-heart"></i> {{ $doc->likes_count }}</span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

            @empty
                <div class="col-12 text-center py-5 text-muted">
                    <i class="bi bi-inbox display-6 mb-3"></i>
                    <p>No hay documentos disponibles.</p>
                </div>
            @endforelse
        </div>


        <!-- Paginacion -->
        <div class="d-flex justify-content-end mt-4">
            {{ $documents->links() }}
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            @if (session('success') || session('error') || session('info'))
                setTimeout(() => {
                    const alerts = document.querySelectorAll('.alert');
                    alerts.forEach(alert => {
                        alert.style.transition = 'opacity 0.5s ease';
                        alert.style.opacity = '0';
                        setTimeout(() => alert.remove(), 500);
                    });
                }, 5000);
            @endif
        });
    </script>

    <!-- Modal compra simulada desde galería -->
    <div class="modal fade" id="purchaseModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">
                        <i class="bi bi-cart-plus"></i>
                        Solicitar compra
                        <small class="d-block fs-6 text-dark-50" id="purchaseDocTitle"></small>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Cerrar"></button>
                </div>
                <div class="modal-body">
                    @if($paymentSetting)
                        <p class="mb-2"><strong>Numero de cuenta:</strong> {{ $paymentSetting->account_number }}</p>
                        @if($paymentSetting->key)
                            <p class="mb-3"><strong>Llave:</strong> {{ $paymentSetting->key }}</p>
                        @endif
                        @if($paymentSetting->qr_path)
                            <div class="mb-3 text-center">
                                <img src="{{ asset('storage/'.$paymentSetting->qr_path) }}" class="img-fluid rounded border" alt="QR de pago">
                            </div>
                        @endif
                    @else
                        <div class="alert alert-info mb-0">
                            Aún no hay configuración de pago disponible. Contacta al administrador.
                        </div>
                    @endif
                    <p class="small text-muted mt-2 mb-0">Selecciona "Ya realicé el pago" para enviar la solicitud.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancelar</button>
                    @if($paymentSetting)
                    <form id="purchaseForm" method="POST" class="d-inline" data-action-base="{{ url('documents') }}">
                        @csrf
                        <button type="submit" class="btn btn-warning text-dark px-4">
                            Ya realicé el pago
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modalEl = document.getElementById('purchaseModal');
            const form = document.getElementById('purchaseForm');
            const docTitleEl = document.getElementById('purchaseDocTitle');

            if (!modalEl) return;

            const modal = new bootstrap.Modal(modalEl);
            const baseAction = form ? form.dataset.actionBase : null;

            document.querySelectorAll('.purchase-btn').forEach(btn => {
                btn.addEventListener('click', function (e) {
                    e.preventDefault();
                    e.stopPropagation();
                    if (!form || !baseAction) return;

                    const docId = this.dataset.docId;
                    const docTitle = this.dataset.docTitle || '';
                    form.action = `${baseAction}/${docId}/purchase-request`;
                    if (docTitleEl) {
                        docTitleEl.textContent = docTitle;
                    }
                    modal.show();
                });
            });
        });
    </script>
@endsection
