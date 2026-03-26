<?php

namespace App\Mail;

use App\Models\PurchaseRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PurchaseRequestCreatedMail extends Mailable
{
    use Queueable, SerializesModels;

    public PurchaseRequest $purchaseRequest;

    public function __construct(PurchaseRequest $purchaseRequest)
    {
        $this->purchaseRequest = $purchaseRequest->loadMissing(['user', 'document']);
    }

    public function build()
    {
        return $this->subject('Nueva solicitud de compra pendiente')
            ->view('emails.purchase-request-created');
    }
}
