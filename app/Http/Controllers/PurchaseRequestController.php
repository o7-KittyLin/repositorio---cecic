<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\PaymentSetting;
use App\Models\Purchase;
use App\Models\PurchaseRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Mail\PurchaseApprovedMail;
use App\Mail\PurchaseRejectedMail;
use App\Mail\PurchaseRequestCreatedMail;

class PurchaseRequestController extends Controller
{
    public function store(Document $document, Request $request)
    {
        $user = $request->user();

        if ($document->is_free) {
            return back()->with('error', 'El documento es gratuito.');
        }

        if ($document->isPurchasedBy($user)) {
            return back()->with('success', 'Ya tienes acceso a este documento.');
        }

        $existing = PurchaseRequest::where('user_id', $user->id)
            ->where('document_id', $document->id)
            ->where('status', 'pending')
            ->first();

        if ($existing) {
            return back()->with('info', 'Ya tienes una solicitud pendiente para este documento.');
        }

        $purchaseRequest = PurchaseRequest::create([
            'user_id' => $user->id,
            'document_id' => $document->id,
            'status' => 'pending',
        ]);

        // Notificar a administradores
        $adminsAndStaff = User::role(['Administrador','Empleado'])->pluck('email')->filter()->unique();
        if ($adminsAndStaff->isNotEmpty()) {
            Mail::to($adminsAndStaff)->send(new PurchaseRequestCreatedMail($purchaseRequest));
        }

        return back()->with('success', 'Solicitud enviada. El administrador revisará tu compra.');
    }

    public function index()
    {
        $requests = PurchaseRequest::with(['user','document'])
            ->latest()
            ->paginate(15);
        return view('purchase_requests.index', compact('requests'));
    }

    public function update(PurchaseRequest $purchaseRequest, Request $request)
    {
        $request->validate([
            'action' => 'required|in:approve,reject',
            'admin_note' => 'nullable|string|max:500',
        ]);

        if ($purchaseRequest->status !== 'pending') {
            return back()->with('info', 'La solicitud ya fue revisada.');
        }

        DB::transaction(function() use ($purchaseRequest, $request) {
            $purchaseRequest->status = $request->action === 'approve' ? 'approved' : 'rejected';
            $purchaseRequest->admin_note = $request->admin_note;
            $purchaseRequest->reviewed_by = auth()->id();
            $purchaseRequest->reviewed_at = now();
            $purchaseRequest->save();

            if ($request->action === 'approve') {
                Purchase::create([
                    'user_id' => $purchaseRequest->user_id,
                    'document_id' => $purchaseRequest->document_id,
                    'payment_status' => 'completed',
                    'amount' => $purchaseRequest->document->price ?? 0,
                ]);

                if ($purchaseRequest->user && $purchaseRequest->user->email) {
                    Mail::to($purchaseRequest->user->email)->send(new PurchaseApprovedMail($purchaseRequest));
                }
            } else {
                if ($purchaseRequest->user && $purchaseRequest->user->email) {
                    Mail::to($purchaseRequest->user->email)->send(new PurchaseRejectedMail($purchaseRequest));
                }
            }

            // Notificar a administradores/empleados sobre el resultado
            $adminsAndStaff = User::role(['Administrador','Empleado'])->pluck('email')->filter()->unique();
            if ($adminsAndStaff->isNotEmpty()) {
                Mail::to($adminsAndStaff)->send(new \App\Mail\PurchaseRequestStatusChangedMail($purchaseRequest));
            }
        });

        return back()->with('success', 'Solicitud actualizada.');
    }
}
