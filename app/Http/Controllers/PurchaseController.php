<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function purchase(Document $document)
    {
        $user = auth()->user();

        // Verificar si ya lo compró
        if ($document->isPurchasedBy($user)) {
            return back()->with('error', 'Ya has adquirido este documento.');
        }

        // Verificar si el documento es gratis
        if ($document->is_free) {
            return back()->with('error', 'Este documento es gratuito. Puedes descargarlo directamente.');
        }

        // Simular proceso de pago (aquí integrarías con Stripe, PayPal, etc.)
        try {
            DB::beginTransaction();

            // Crear registro de compra
            $purchase = Purchase::create([
                'user_id' => $user->id,
                'document_id' => $document->id,
                'payment_status' => 'completed', // En un caso real, esto sería 'pending' hasta confirmación
                'amount' => $document->price,
            ]);

            // Aquí iría la integración con la pasarela de pago
            // Por ahora simulamos una compra exitosa

            DB::commit();

            return redirect()->route('purchases.success', $purchase)
                ->with('success', '¡Compra realizada exitosamente! Ya puedes descargar el documento.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al procesar la compra: ' . $e->getMessage());
        }
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
}
