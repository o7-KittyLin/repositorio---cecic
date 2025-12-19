<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
{
    public function purchase(Document $document, Request $request)
    {
        $user = $request->user();

        if ($document->isPurchasedBy($user)) {
            return back()->with('error', 'Ya has adquirido este documento.');
        }

        if ($document->is_free) {
            return back()->with('error', 'Este documento es gratuito. Puedes descargarlo directamente.');
        }

        return redirect()->route('documents.show', $document->id)
            ->with('info', 'Usa el boton "Solicitar compra" para enviar la solicitud al administrador.');
    }

    public function success(Purchase $purchase)
    {
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
                $query->where('is_active', 1);
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
            ->whereHas('document')
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
        // Deshabilitado en flujo simulado
        return redirect()->route('documents.show', $document->id)
            ->with('info', 'El flujo usa solicitudes de compra, no enlaces directos.');
    }
}
