@extends('layouts.app')

@section('content')

    <div class="container py-4">
        <h3 class="mb-4 fw-bold">Mis Favoritos</h3>

        @if ($favorites->isEmpty())
            <div class="alert alert-info">
                No tienes documentos en favoritos.
            </div>
        @endif

        <div class="row g-3">
            @foreach ($favorites as $favorite)
                @php
                    $doc = $favorite->document; // documento real
                @endphp

                @if ($doc)
                    <div class="col-md-4 col-lg-3">
                        <div class="card shadow-sm h-100">

                            <!-- Preview -->
                            <div class="preview-overlay">
                                @if (Str::endsWith($doc->file_path, '.pdf'))
                                    <iframe
                                        src="{{ asset('storage/' . $doc->file_path) }}#page=1&zoom=100&toolbar=0"
                                        class="preview-frame">
                                    </iframe>
                                @else
                                    <div class="d-flex align-items-center justify-content-center bg-light preview-frame text-muted">
                                        <i class="bi bi-file-earmark-text fs-1"></i>
                                    </div>
                                @endif
                            </div>

                            <!-- Body -->
                            <div class="card-body d-flex flex-column">

                                <h6 class="card-title fw-bold text-brown mb-1">
                                    {{ Str::limit($doc->title, 40) }}
                                </h6>

                                <small class="text-muted d-block mb-2">
                                    {{ $doc->category->name ?? 'Sin categoría' }}
                                </small>

                                <p class="card-text small text-secondary flex-grow-1">
                                    {{ Str::limit($doc->description, 80) }}
                                </p>

                                <div class="d-flex justify-content-between mt-3">

                                    <!-- Ver -->
                                    <a href="{{ route('documents.show', $doc->id) }}"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i>
                                    </a>

                                    <!-- Quitar de favoritos -->
                                    <form action="{{ route('documents.favorite', $doc->id) }}"
                                          method="POST"
                                          class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-warning">
                                            <i class="bi bi-star-fill"></i>
                                        </button>
                                    </form>

                                </div>

                            </div>
                        </div>
                    </div>
                @endif

            @endforeach
        </div>
    </div>

@endsection
