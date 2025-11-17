@extends('layouts.app')

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
            height: 180px;
            width: 100%;
            border: none;
            background-color: #f0f0f0;
            object-fit: cover;
            border-radius: 0.5rem 0.5rem 0 0;
        }

        .text-brown {
            color: #8B5E3C;
        }

        /* Overlay Preview */
        .preview-overlay {
            position: relative;
            overflow: hidden;
            border-radius: 0.5rem 0.5rem 0 0;
        }

        .preview-overlay::after {
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

        .card-text {
            font-size: 0.875rem;
            color: #6c757d;
        }

        .small.text-muted {
            font-size: 0.8rem;
        }

    </style>

    <div class="container py-5">

        <h2 class="fw-bold text-brown mb-4">
            <i class="bi bi-star-fill"></i> Mis Favoritos
        </h2>

        @if ($favorites->isEmpty())
            <div class="alert alert-info">
                No tienes documentos en favoritos.
            </div>
        @endif

        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            @foreach($favorites as $favorite)
                @php
                    $doc = $favorite->document;
                     $isPurchased = auth()->check() ? $doc->isPurchasedBy(auth()->user()) : false;
                    $canDownload = $doc->is_free || $isPurchased || (auth()->check() && auth()->user()->hasRole('Administrador'));
                @endphp

                @if($doc)
                    <div class="col">
                        <a href="{{ route('documents.show', $doc->id) }}" class="text-decoration-none text-dark">
                            <div class="card h-100 shadow-sm border-0 rounded-3 overflow-hidden">
                                <div class="preview-overlay">
                                    @if (Str::endsWith($doc->file_path, '.pdf'))
                                        <iframe src="{{ asset('storage/' . $doc->file_path) }}#toolbar=0&navpanes=0&scrollbar=0"
                                                class="w-100 preview-frame"></iframe>
                                    @else
                                        <div class="d-flex align-items-center justify-content-center bg-light preview-frame text-muted">
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

                                    <p class="card-text flex-grow-1 mb-3">{{ Str::limit($doc->description, 100) }}</p>

                                    <div class="d-flex justify-content-end align-items-center mb-2">
                                        <a href="{{ route('documents.show', $doc->id) }}" class="me-2 btn btn-sm btn-outline-primary" title="Ver detalles">
                                            <i class="bi bi-eye"></i>
                                        </a>

                                        <!-- Quitar favorito -->
                                        <form action="{{ route('documents.favorite', $doc->id) }}" method="POST">
                                            @csrf
                                            <button class="btn btn-sm me-2 btn-outline-warning" title="Quitar de favoritos">
                                                <i class="bi bi-star-fill"></i>
                                            </button>
                                        </form>

                                        @if ($canDownload)
                                            <a href="{{ route('repository.download', $doc->id) }}" class="btn btn-sm btn-outline-success" title="Descargar">
                                                <i class="bi bi-download"></i>
                                            </a>
                                        @endif

                                    </div>

                                    <div class="d-flex justify-content-between mt-3 small text-muted">
                                        <span><i class="bi bi-eye"></i> {{ $doc->views_count }}</span>
                                        <span><i class="bi bi-heart"></i> {{ $doc->likes_count }}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
@endsection
