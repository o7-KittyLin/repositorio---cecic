<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DocumentController;
use App\Http\Controllers\DocumentInteractionController;
use App\Http\Controllers\FavoriteController;
use App\Http\Controllers\PaymentSettingController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseRequestController;
use App\Http\Controllers\RepositoryController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/repository/gallery', [RepositoryController::class, 'gallery'])->name('repository.gallery');

// Multimedia pública (anuncios y videos sin login)
Route::get('/multimedia', function () {
    $reuniones = \App\Models\Announcement::visible()
        ->where('type', 'reunion')
        ->orderBy('start_time', 'desc')
        ->paginate(6, ['*'], 'reuniones_page');

    $multimedia = \App\Models\Announcement::visible()
        ->where('type', 'multimedia')
        ->orderBy('start_time', 'desc')
        ->paginate(6, ['*'], 'multimedia_page');

    return view('multimedia', compact('reuniones', 'multimedia'));
})->name('multimedia.index');

// Descargar documento (solo si gratis o comprado)
Route::get('documents/download/{document}', [DocumentController::class, 'download'])
    ->name('documents.download');

// Ver documento (detalle)
Route::get('documents/{document}', [DocumentController::class, 'show'])
    ->name('documents.show');

Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified',])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Documentos: solo Administrador/Empleado pueden crear/editar/borrar. Ver (show) y download definidos aparte.
    Route::middleware('role:Administrador|Empleado')->group(function () {
        Route::resource('documents', DocumentController::class)->except(['show']);
    });

    // Repositorio (CRUD solo Admin/Empleado)
    Route::middleware('role:Administrador|Empleado')->group(function () {
        Route::get('/repository', [RepositoryController::class, 'index'])->name('repository.index');
        Route::post('/repository', [RepositoryController::class, 'store'])->name('repository.store');
        Route::get('/repository/{document}/edit', [RepositoryController::class, 'edit'])->name('repository.edit');
        Route::put('/repository/{document}', [RepositoryController::class, 'update'])->name('repository.update');
        Route::patch('/repository/{document}/toggle', [RepositoryController::class, 'toggleActive'])
            ->name('repository.toggle');
        Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
        Route::delete('/categories/{id}', [CategoryController::class, 'destroy'])
            ->name('categories.destroy');
    });
    Route::get('/repository/download/{document}', [RepositoryController::class, 'download'])
        ->name('repository.download')
        ->middleware('auth'); // obliga a iniciar sesión

    // Anuncios (solo admin/empleado)
    Route::middleware('role:Administrador|Empleado')->group(function () {
        Route::resource('announcements', AnnouncementController::class);
    });

    // Interacciones con documentos
    Route::post('documents/{document}/like', [DocumentInteractionController::class, 'like'])
        ->name('documents.like');
    Route::post('documents/{document}/comment', [DocumentInteractionController::class, 'comment'])
        ->name('documents.comment');
    Route::post('/documents/{document}/favorite', [DocumentInteractionController::class, 'toggleFavorite'])
        ->name('documents.favorite');
    Route::delete('comments/{comment}', [DocumentInteractionController::class, 'deleteComment'])
        ->name('comments.destroy');

    // Compras
    Route::post('documents/{document}/purchase', [PurchaseController::class, 'purchase'])
        ->name('documents.purchase');
    Route::post('documents/{document}/purchase-request', [PurchaseRequestController::class, 'store'])
        ->name('documents.purchase-request');
    Route::get('my-purchases', [PurchaseController::class, 'myPurchases'])
        ->name('purchases.my');
    Route::get('purchases/success/{purchase}', [PurchaseController::class, 'success'])
        ->name('purchases.success');
    Route::get('/documents/{document}/confirm-purchase', [PurchaseController::class, 'confirmPurchase'])
        ->middleware('signed')
        ->name('documents.confirm-purchase');

    // Favoritos
    Route::get('my-favorites', [FavoriteController::class, 'myFavorites'])
        ->name('favorites.my');

    Route::resource('users', UserController::class);

    // Ventas (solo admin/empleado)
    Route::middleware('role:Administrador|Empleado')->group(function () {
        Route::get('/ventas', [PurchaseController::class, 'sales'])
            ->name('sales.index');
        Route::get('/sales/documents', [PurchaseController::class, 'salesByDocument'])
            ->name('sales.byDocument');
        Route::get('/sales/document/{document}', [PurchaseController::class, 'salesDocumentDetail'])
            ->name('sales.documentDetail');

        Route::get('purchase-requests', [PurchaseRequestController::class, 'index'])
            ->name('purchase-requests.index');
        Route::put('purchase-requests/{purchaseRequest}', [PurchaseRequestController::class, 'update'])
            ->name('purchase-requests.update');

        Route::middleware('role:Administrador')->group(function () {
            Route::get('payment-settings', [PaymentSettingController::class, 'edit'])
                ->name('payment-settings.edit');
            Route::put('payment-settings', [PaymentSettingController::class, 'update'])
                ->name('payment-settings.update');
        });
    });
});
