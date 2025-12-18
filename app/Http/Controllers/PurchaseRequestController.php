<?php

namespace App\Http\Controllers;

use App\Mail\PurchaseRequestApprovedMail;
use App\Mail\PurchaseRequestRejectedMail;
use App\Models\Document;
use App\Models\PaymentSetting;
use App\Models\Purchase;
use App\Models\PurchaseRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class PurchaseRequestController extends Controller
{
    public function index()
    {
        $requests = PurchaseRequest::with(['user', 'document', 'reviewer'])
            ->latest()
            ->paginate(15);

        return view('purchase-requests.index', compact('requests'));
    }

    public function store(Document $document, Request $request)
    {
        $user = $request->user();

        if ($document->is_free) {
            return back()->with('error', 'El documento es gratuito; descárgalo directamente.');
        }

        $paymentSetting = PaymentSetting::first();
        if (!$paymentSetting || !$paymentSetting->account_number) {
            return back()->with('error', 'Aún no hay datos de pago configurados.');
        }

        $hasPending = PurchaseRequest::where('user_id', $user->id)
            ->where('document_id', $document->id)
            ->where('status', 'pending')
            ->exists();

        if ($hasPending) {
            return back()->with('info', 'Ya tienes una solicitud pendiente para este documento.');
        }

        PurchaseRequest::create([
            'user_id' => $user->id,
            'document_id' => $document->id,
            'status' => 'pending',
        ]);

        return back()->with('success', 'Solicitud enviada. Un administrador la revisará.');
    }

    public function approve(PurchaseRequest $purchaseRequest)
    {
        if ($purchaseRequest->status !== 'pending') {
            return back()->with('info', 'La solicitud ya fue procesada.');
        }

        if (!$purchaseRequest->document) {
            return back()->with('error', 'El documento ya no está disponible.');
        }

        DB::transaction(function () use ($purchaseRequest) {
            $purchaseRequest->update([
                'status' => 'approved',
                'reviewed_by' => auth()->id(),
                'reviewed_at' => now(),
            ]);

            Purchase::firstOrCreate(
                [
                    'user_id' => $purchaseRequest->user_id,
                    'document_id' => $purchaseRequest->document_id,
                ],
                [
                    'payment_status' => 'completed',
                    'amount' => optional($purchaseRequest->document)->price ?? 0,
                    'purchase_request_id' => $purchaseRequest->id,
                ]
            );
        });

        Mail::to($purchaseRequest->user->email)
            ->send(new PurchaseRequestApprovedMail($purchaseRequest));

        return back()->with('success', 'Solicitud aprobada y acceso otorgado.');
    }

    public function reject(PurchaseRequest $purchaseRequest)
    {
        if ($purchaseRequest->status !== 'pending') {
            return back()->with('info', 'La solicitud ya fue procesada.');
        }

        $purchaseRequest->update([
            'status' => 'rejected',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        Mail::to($purchaseRequest->user->email)
            ->send(new PurchaseRequestRejectedMail($purchaseRequest));

        return back()->with('success', 'Solicitud rechazada.');
    }
}
