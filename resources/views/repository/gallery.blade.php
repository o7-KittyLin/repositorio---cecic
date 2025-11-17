@auth
    @extends('layouts.app')
@endauth
@section('content')
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Poppins', sans-serif;
        }

        /* Tarjeta */
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

        /* Card Body */
        .card-body {
            background: #fff;
            padding: 1rem;
            display: flex;
            flex-direction: column;
        }

        /* Preview */
        .preview-frame {
            height: 220px;
            width: 100%;
            border: none;
            background-color: #f0f0f0;
            object-fit: cover;
            border-radius: 12px 12px 0 0;
        }

        /* Botones personalizados */
        .btn-brown {
            background: #8B5E3C;
            color: #fff;
            transition: background 0.3s;
        }

        .btn-brown:hover {
            background: #6f452b;
        }

        /* Texto */
        .text-brown {
            color: #8B5E3C;
        }

        /* Badges */
        .badge-free {
            background-color: #198754;
            color: #fff;
        }

        .badge-paid {
            background-color: #dc3545;
            color: #fff;
        }

        .badge-purchased {
            background-color: #0d6efd;
            color: #fff;
        }

        /* Overlay Preview */
        .preview-overlay {
            position: relative;
            overflow: hidden;
            border-radius: 12px 12px 0 0;
        }

        .preview-overlay::after {
            content: "Vista Previa";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%) scale(0);
            background: rgba(0, 0, 0, 0.6);
            color: white;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 0.85rem;
            font-weight: 500;
            transition: transform 0.3s ease, opacity 0.3s ease;
            opacity: 0;
        }

        .preview-overlay:hover::after {
            transform: translate(-50%, -50%) scale(1);
            opacity: 1;
        }

        /* Texto pequeño */
        .card-text {
            font-size: 0.875rem;
            color: #6c757d;
        }

        /* Información adicional */
        .card .small.text-muted {
            font-size: 0.8rem;
        }

    </style>

    <div class="container py-5">

        <!-- Encabezado -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-3">
{{--                <a href="{{ auth()->check() ? route('dashboard') : route('home') }}" class="btn btn-outline-secondary">--}}
{{--                    <i class="bi bi-house-door"></i> Inicio--}}
{{--                </a>--}}

                <h2 class="fw-bold text-brown mb-0">
                    <i class="bi bi-collection"></i> Galería del Repositorio
                </h2>
            </div>

            <div class="d-flex gap-2 align-items-center">
                <form action="{{ route('repository.gallery') }}" method="GET" class="d-flex gap-2 align-items-center">
                    <select name="category_id" class="form-select" onchange="this.form.submit()"
                        style="min-width: 200px;">
                        <option value="">Todas las categorías</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->id }}"
                                {{ request('category_id') == $cat->id ? 'selected' : '' }}>
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
                            <div class="position-relative">
                                @if (Str::endsWith($doc->file_path, '.pdf'))
                                    <iframe src="{{ asset('storage/' . $doc->file_path) }}#toolbar=0&navpanes=0&scrollbar=0"
                                            class="w-100 preview-frame" style="height:180px; border:none; border-radius: .5rem  .5rem 0 0;">
                                    </iframe>
                                @else
                                    <div class="d-flex align-items-center justify-content-center bg-light preview-frame text-muted"
                                         style="height:180px; border-radius: .5rem  .5rem 0 0;">
                                        <i class="bi bi-file-earmark-text fs-1"></i>
                                    </div>
                                @endif
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
                                    <small class="text-muted d-block mb-2">{{ $doc->category->name }}</small>
                                @endif

                                <p class="card-text text-secondary flex-grow-1 mb-3">
                                    {{ Str::limit($doc->description, 100) }}
                                </p>

                                <div class="d-flex justify-content-end align-items-center mb-2">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('documents.show', $doc->id) }}" class="me-2 btn btn-sm btn-outline-primary" title="Ver detalles">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        @if ($canDownload)
                                            <a href="{{ route('repository.download', $doc->id) }}" class="btn me-2 btn-sm btn-outline-success" title="Descargar">
                                                <i class="bi bi-download"></i>
                                            </a>
                                        @elseif(!$doc->is_free && auth()->check())
                                            <button class="btn btn-sm btn-warning me-2" data-bs-toggle="modal"
                                                    data-bs-target="#purchaseModal"
                                                    data-document-id="{{ $doc->id }}"
                                                    data-document-title="{{ $doc->title }}"
                                                    data-document-price="{{ $doc->price }}">
                                                <i class="bi bi-cart-plus"></i>
                                            </button>
                                        @elseif(!$doc->is_free && !auth()->check())
                                            <a href="{{ route('login') }}?redirect=purchase&doc={{ $doc->id }}" class="btn btn-sm btn-warning">
                                                <i class="bi bi-cart-plus"></i>
                                            </a>
                                        @endif

                                        @hasrole('Administrador')
                                        <a href="{{ route('repository.edit', $doc->id) }}" class="btn btn-sm btn-outline-warning">
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


        <!-- Paginación -->
        <div class="d-flex justify-content-end mt-4">
            {{ $documents->links() }}
        </div>
    </div>

    <!-- Modal de Compra -->
    <div class="modal fade" id="purchaseModal" tabindex="-1">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title">
                        <i class="bi bi-cart-check"></i> Confirmar Compra
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <i class="bi bi-file-earmark-text display-4 text-brown mb-3"></i>
                        <h5 id="documentTitle" class="fw-bold"></h5>
                        <p class="text-muted">¿Estás seguro de que deseas adquirir este documento?</p>
                    </div>

                    <div class="row g-3">
                        <div class="col-6">
                            <div class="card border-0 bg-light">
                                <div class="card-body text-center">
                                    <h6 class="text-muted">Precio</h6>
                                    <h4 id="documentPrice" class="fw-bold text-brown"></h4>
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card border-0 bg-light">
                                <div class="card-body text-center">
                                    <h6 class="text-muted">Formato</h6>
                                    <h6 class="fw-bold text-brown">PDF</h6>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4">
                        <h6>Beneficios:</h6>
                        <ul class="list-unstyled small">
                            <li><i class="bi bi-check-circle text-success me-2"></i> Descarga ilimitada</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i> Acceso permanente</li>
                            <li><i class="bi bi-check-circle text-success me-2"></i> Soporte técnico</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Cancelar
                    </button>
                    <form id="purchaseForm" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-credit-card"></i> Confirmar Compra
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const purchaseModal = document.getElementById('purchaseModal');

            if (purchaseModal) {
                purchaseModal.addEventListener('show.bs.modal', function(event) {
                    const button = event.relatedTarget;
                    const documentId = button.getAttribute('data-document-id');
                    const documentTitle = button.getAttribute('data-document-title');
                    const documentPrice = button.getAttribute('data-document-price');

                    // Actualizar modal con la información del documento
                    document.getElementById('documentTitle').textContent = documentTitle;
                    document.getElementById('documentPrice').textContent = '$' + parseFloat(documentPrice)
                        .toFixed(2);

                    // Actualizar formulario con la ruta correcta
                    const form = document.getElementById('purchaseForm');
                    form.action = `/documents/${documentId}/purchase`;
                });
            }

            // Mostrar mensajes de éxito/error
            @if (session('success') || session('error'))
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
@endsection
