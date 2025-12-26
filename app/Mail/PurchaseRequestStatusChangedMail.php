<?php

namespace App\Mail;

use App\Models\PurchaseRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PurchaseRequestStatusChangedMail extends Mailable
{
    use Queueable, SerializesModels;

    public PurchaseRequest $purchaseRequest;

    public function __construct(PurchaseRequest $purchaseRequest)
    {
        $this->purchaseRequest = $purchaseRequest->loadMissing(['user', 'document', 'reviewer']);
    }

    public function build()
    {
        return $this->subject('Solicitud de compra ' . ($this->purchaseRequest->status === 'approved' ? 'aprobada' : 'rechazada'))
            ->view('emails.purchase-request-status');
    }
}
