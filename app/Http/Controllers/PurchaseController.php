<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;
use App\Mail\PurchaseConfirmationMail;

class PurchaseController extends Controller
{
    public function purchase(Document $document, Request $request)
    {
        $user = $request->user();

        // Verificar si ya lo compró
        if ($document->isPurchasedBy($user)) {
            return back()->with('error', 'Ya has adquirido este documento.');
        }

        // Verificar si el documento es gratis
        if ($document->is_free) {
            return back()->with('error', 'Este documento es gratuito. Puedes descargarlo directamente.');
        }

        // 🔐 Generar un enlace firmado y temporal para confirmar la compra
        $confirmUrl = URL::temporarySignedRoute(
            'documents.confirm-purchase',
            now()->addMinutes(15), // duración del enlace
            [
                'document' => $document->id,
                'user'     => $user->id, // 🔒 protección extra: ID del usuario
            ]
        );

        // 📧 Enviar correo con el enlace de confirmación
        Mail::to($user->email)->send(
            new PurchaseConfirmationMail($user, $document, $confirmUrl)
        );

        return back()->with('success', 'Te hemos enviado un enlace a tu correo para confirmar la compra.');
    }


    public function success(Purchase $purchase)
    {
        // Verificar que el usuario sea el dueño de la compra
        if ($purchase->user_id !== auth()->id()) {
            abort(403);
        }

        return view('purchases.success', compact('purchase'));
    }

    public function myPurchases()
    {
        $purchases = auth()->user()->purchases()
            ->with('document.category')
            ->whereHas('document', function ($query) {
                $query->where('is_active', 1); // Solo documentos activos
            })
            ->where('payment_status', 'completed')
            ->latest()
            ->paginate(12);

        return view('purchases.index', compact('purchases'));
    }

    public function sales()
{
    $sales = Purchase::with(['user', 'document' => function($q){
        $q->withCount('purchases');
    }])
        ->where('payment_status', 'completed')
        ->whereHas('document') // <- esto asegura que haya documento
        ->latest()
        ->paginate(15);

    return view('purchases.sales', compact('sales'));
}


    public function salesByDocument()
    {
        $documents = Document::withCount('purchases')
            ->with('category')
            ->orderByDesc('purchases_count')
            ->paginate(15);

        return view('purchases.sales-by-document', compact('documents'));
    }

    public function salesDocumentDetail(Document $document)
    {
        $sales = Purchase::where('document_id', $document->id)
            ->with('user')
            ->where('payment_status', 'completed')
            ->latest()
            ->get();

        return view('purchases.sales-document-detail', compact('document', 'sales'));
    }

    public function confirmPurchase(Document $document, Request $request)
    {
        $user = $request->user();

        // 🔐 Protección extra: verificar que el enlace corresponde a este usuario
        if ((int) $request->query('user') !== $user->id) {
            abort(403, 'No puedes confirmar esta compra.');
        }

        // Si ya lo compró, lo mandamos directo al historial
        if ($document->isPurchasedBy($user)) {
            return redirect()->route('purchases.my')
                ->with('success', 'Ya habías adquirido este documento. Puedes descargarlo desde tus compras.');
        }

        // Por si acaso, evitar compras de documentos gratis
        if ($document->is_free) {
            return redirect()->route('repository.gallery')
                ->with('error', 'Este documento es gratuito. No es necesario comprarlo.');
        }

        try {
            DB::beginTransaction();

            // Crear registro de compra (lo que antes hacías en purchase)
            $purchase = Purchase::create([
                'user_id'        => $user->id,
                'document_id'    => $document->id,
                'payment_status' => 'completed',  // en real: pending/hook
                'amount'         => $document->price,
            ]);

            DB::commit();

            return redirect()->route('purchases.success', $purchase)
                ->with('success', '¡Compra confirmada exitosamente! Ya puedes descargar el documento.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('repository.gallery')
                ->with('error', 'Error al confirmar la compra: ' . $e->getMessage());
        }
    }

}
